<?php

/** 
 * Edit profile page action
 * 
 */
class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function render()
	{
        // Inject dependency
        ci()->load->library('Recaptcha');

        // Do not show form register if user already login
        if(isLoggedIn())
            redirect('dashboard');

        $data['captcha'] = ci()->recaptcha->getWidget();
        $data['script_captcha'] = ci()->recaptcha->getScriptTag();

        // Create google sign in url
        if (setting_item('user.enable_google_signin') && setting_item('user.google_signin_client_id') && setting_item('user.google_signin_client_secret')) 
        {
            $redirectUri = site_url('/auth/google');
            $client = new Google_Client();
            $client->setClientId(setting_item('user.google_signin_client_id'));
            $client->setClientSecret(setting_item('user.google_signin_client_secret'));
            $client->setRedirectUri($redirectUri);
            $client->addScope("email");
            $client->addScope("profile");
            $data['google_signin'] = $client->createAuthUrl();
        }

        return $data;
	}


	// This method handle POST request
	public function process()
	{
        $redirect = ci()->session->redirect ?? '';

        // Inject dependency
        ci()->load->library('Recaptcha');

        $post = ci()->input->post(null, true);

        foreach($post as $p => $value)
            ci()->session->set_flashdata($p, htmlspecialchars($value));

        $response = ci()->recaptcha->verifyResponse($post['g-recaptcha-response'] ?? '');

        if ($_ENV['CI_ENV'] == 'production' && empty($response['success']))
        {
            ci()->session->set_flashdata('message', '<div class="alert alert-danger">Recaptcha is empty /not valid, please fill reCaptcha correcly.</div>');
            redirect('auth/register');
        }

        // Set username from email if not provided
        $username = ci()->input->post('username') 
                    ?? preg_replace( '/[\W]/', '', $post['email']);
        
        $registerData = [
            'name' => htmlspecialchars($post['name']),
            'username' => htmlspecialchars($username).random_string('alnum', 5),
            'email' => htmlspecialchars($post['email']),
            'password' => ci()->input->post('password'),
            'confirm_password' => ci()->input->post('confirm_password'),
            'role_id' => 3,
            'referrer_code' => get_cookie('referrer') ?? ''
        ];
        if(setting_item('user.include_phone_on_register') == 1
            || in_array(setting_item('user.otp_mode'), ['WASender','Woowa','ZenzivaWA'])){
            $country_code = trim(ci()->input->post('country_code', true));
            $phone = trim(ci()->input->post('phone', true));
            if(substr($phone, 0, 1)=='0') 
                $phone = substr_replace($phone, '', 0, 1);
            $profileData = ['phone' => $country_code.$phone];
        }

        $register = ci()->ci_auth->register($registerData, $profileData ?? []);
        
        if ($register['status'] == 'success')
        {
            // Force login, send link activation to email for later activation
            if(setting_item('user.allow_login_before_activation')){
                $this->sendActivationLink($register);
                ci()->ci_auth->forceLogin($register['id']);

                if ($redirect) {
                    ci()->session->unset_userdata('redirect');
                    redirect($redirect);
                }

                redirect('dashboard');

            // Send link activation to email, but cannot login before activation
            } else if(setting_item('user.confirmation_type') == 'link') {
                $this->sendActivationLink($register);
                ci()->session->set_flashdata('toastr', json_encode(['type'=>'primary', 'position'=>'top', 'message'=>'Untuk dapat masuk, segera aktivasi akun dengan mengklik tautan aktivasi yang telah kami kirim ke alamat email.']));
                redirect('auth/login');

            // Use OTP for user to activates immediately
            } else {
                $this->sendOTP($register);
                redirect('auth/register/confirm_otp/'.$register['token']);
            }
        }

        ci()->session->set_flashdata('message', '<div class="alert alert-danger">'. $register['message'] .'</div>');
        redirect('auth/register');
	}

    private function sendActivationLink($data)
    {
        $emailer = new App\libraries\Emailer(true);
        $emailer->to = [$data['email'], $data['name']];
        $emailer->subject = "Konfirmasi Registrasi";
        $emailer->body = ci()->load->render('pages/auth/register/email_link.html', $data, true);
        $emailer->send();
    }

    private function sendOTP($data)
    {
        if(setting_item('user.otp_mode') == 'email')
        {
            $emailer = new App\libraries\Emailer(true);
            $emailer->to = [$data['email'], $data['name']];
            $emailer->subject = "Konfirmasi Registrasi";
            $emailer->body = ci()->load->render('pages/auth/register/email_otp.html', $data, true);
            $emailer->send();
        }

        elseif(in_array(setting_item('user.otp_mode'), ['WASender','Woowa','ZenzivaWA']))
        {
            $sender = new App\modules\notifier\libraries\Sender(setting_item('user.otp_mode'));
            $message = $this->getRandomOTPMessage();
            $message = str_replace('{otp}',$data['otp'], $message);
            $res = $sender->sendText($data['phone'], $message);
        }
    }

    private function getRandomOTPMessage()
    {
        $firstMessage = "Anda telah mendaftar di aplikasi ".setting_item("site.site_title")."\n";
        $message_confirm_register_otp = [
            "Masukkan kode berikut untuk mengkonfirmasi pendaftaran: {otp}",
            "Silakan aktifkan akun Anda dengan memasukkan kode berikut di halaman konfirmasi: {otp}",
            "Satu langkah lagi, masukkan kode berikut untuk mengaktifkan akun: {otp}",
            "Aktifkan akun dengan memasukkan kode berikut ini: {otp}",
            "Terima kasih sudah mendaftar, aktifkan akun dengan memasukkan kode ini: {otp}",
            "Pendaftaran akun berhasil. Silakan aktifkan akun dengan memasukkan kode berikut: {otp}",
        ];

        return $firstMessage.$message_confirm_register_otp[array_rand($message_confirm_register_otp)];
    }

}