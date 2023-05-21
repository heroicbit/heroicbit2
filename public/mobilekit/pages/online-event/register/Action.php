<?php

/** 
 * Register event page action
 * 
 */
class PageAction {

	public function __construct()
	{

	}

	// This method handle get request
	public function render()
	{
		$id = ci()->uri->segment(3);
		if(!$id) redirect('online-event');

		// Get event detail
        $EventModel = setup_entry_model('event');
        $event = $EventModel->get($id);
        if(!$event) show_404();
        
        $presenters = ci()->db->from('event_presenter ep')
        					  ->join('presenter p', 'p.id = ep.presenter_id')
        					  ->where('event_id', $event['id'])
        					  ->get()->result_array();

        // Get user referral data
        $this->ReferralModel = setup_entry_model('referral_user');
	    $referral = $this->ReferralModel
	                     ->where('user_id',ci()->session->user_id)
	                     ->get();

	    ci()->shared['page_title'] = $event['title'];
	    ci()->shared['og_image'] = $event['poster'];
	    ci()->shared['meta_description'] = $event['notes'];

        return compact('event','presenters','referral');
	}


	// This method handle POST request
	public function process()
	{
		$id = ci()->uri->segment(3);
		if (!$id) redirect('online-event');

		// Get event detail
		$EventModel = setup_entry_model('event');
		$event = $EventModel->get($id);
		if (!$event) show_404();

		$redirect = ci()->session->redirect ?? '';
		$post = ci()->input->post(null, true);

		// Register user if not logged in
		if(! (ci()->session->logged_in ?? null)) {
			$user = $this->registerUser($post);
		} else {
			$user = isLoggedIn();
		}

		// TODO: Check payment

		// Register the event
		$eventRegister = $this->registerEvent($event, $user);

		if(setting_item('online_event.enable_wa_confirmation') == '1'){
			$keydata = array_keys(array_merge($event, $user));
			$valdata = array_values(array_merge($event, $user));
			array_walk($keydata, function(&$item, $key){
				$item = '{'.$item.'}';
			});
			$waMessage = str_replace($keydata, $valdata, setting_item('online_event.whatsapp_conf_message'));
			$waNumber = setting_item('online_event.whatsapp_conf_destination');
			redirect('https://wa.me/'.$waNumber.'?text='.urlencode($waMessage));
		}
		
		redirect('online-event/my');
	}

	private function registerUser($post)
	{
		foreach ($post as $p => $value)
			ci()->session->set_flashdata($p, htmlspecialchars($value));

		// ci()->load->library('Recaptcha');
		// $response = ci()->recaptcha->verifyResponse($post['g-recaptcha-response'] ?? '');
		// if ($_ENV['CI_ENV'] == 'production' && empty($response['success'])) {
		// 	ci()->session->set_flashdata('message', '<div class="alert alert-danger">Recaptcha is empty /not valid, please fill reCaptcha correcly.</div>');
		// 	redirect(getenv('HTTP_REFERER'));
		// }

		// Set username from email if not provided
		$username = ci()->input->post('username') ?? preg_replace('/[\W]/', '', $post['email']);
		$generatedPassword = random_string();

		$country_code = trim(ci()->input->post('country_code', true));
		$phone = trim(ci()->input->post('phone', true));
		if (substr($phone, 0, 1) == '0') $phone = substr_replace($phone, '', 0, 1);
		$phone = $country_code.$phone;

		$registerData = [
			'name' => htmlspecialchars($post['name']),
			'username' => htmlspecialchars($username) . random_string('alnum', 5),
			'email' => htmlspecialchars($post['email']),
			'phone' => $phone,
			'password' => $generatedPassword,
			'confirm_password' => $generatedPassword,
			'role_id' => 3,
			'referrer_code' => get_cookie('referrer') ?? ''
		];
		$profileData = ['city' => $post['city'] ?? ''];

		$register = ci()->ci_auth->register($registerData, $profileData ?? []);

		if ($register['status'] != 'success') {
			ci()->session->set_flashdata('message', '<div class="alert alert-danger">' . $register['message'] . '</div>');
			redirect(getenv('HTTP_REFERER'));
		}
		
		// Force login, send link activation to email for later activation
		ci()->ci_auth->forceLogin($register['id']);
		$this->sendActivationLink($register);
		return $register;
	}

	private function sendActivationLink($data)
	{
		$to = [$data['email'], $data['name']];
		$subject = "Konfirmasi Registrasi";
		$body = ci()->load->render('pages/auth/register/email_link.html', $data, true);
		ci()->load->helper('email');
		sendEmail($to, $subject, $data, null, $body);
	}

	private function registerEvent($event, $user)
	{
		$EventParticipantModel = setup_entry_model('event_participant');
		$data = [
			'event_id' => $event['id'],
			'user_id' => $user['id'],
			'registration_code' => date("md"). date("Hi").strtoupper(random_string('alnum', 4)),
		];

		return $EventParticipantModel->insert($data);
	}

}