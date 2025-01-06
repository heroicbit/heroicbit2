<?php namespace App\Pages\member\reset_password;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class PageController extends MemberPageController {

    use ResponseTrait;

    // Load member layout
	public function getIndex()
	{
		$this->data['page_title'] = 'Reset Password';

		return pageView('member/index', $this->data);
	}
    
    public function getContent()
    {
        return pageView('member/reset_password/index', $this->data);
    }
    
    public function postIndex()
    {
        $phone = $this->request->getPost('phone');
        $recaptchaResponse = $this->request->getPost('recaptcha');

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Check google recaptcha response
        $recaptchaSetting = $db->table('mein_options')
            ->where('option_group', 'tarbiyya')
            ->where('option_name', 'recaptcha_secret_key')
            ->get()->getRowArray();
        $Recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSetting['option_value']);
        $resp = $Recaptcha->setExpectedHostname($_SERVER['HTTP_HOST'])
                        ->verify($recaptchaResponse, $_SERVER['REMOTE_ADDR']);
        if (! $resp->isSuccess()) {
            return $this->respond(['found' => 0, 'error' => 'Terjadi kesalahan saat mengecek recaptcha: '. implode(', ', $resp->getErrorCodes())]);
        }

        // Make sure the number begin with 62
		$phone = substr($phone, 0, 1)=='0' 
		? substr_replace($phone, '62', 0, 1) 
		: $phone;
		if(substr($phone, 0, 1)=='8') 
			$phone = '62'.$phone;

        // Get user
        $query = "SELECT id, name, phone FROM mein_users WHERE phone = :phone:";
        $user = $db->query($query, ['phone' => $phone])->getRowArray();
        if($user) 
        {
            // Update token and otp
            helper('text');
            $otp = random_string('numeric', 6);
            $token = sha1($otp);
            $db->table('mein_users')->where('id', $user['id'])->update([
                'token' => $token,
                'otp' => $otp
            ]);

            // Send otp to whatsapp
            $response = $this->sendOTP($user['name'], $user['phone'], $otp);

            return $this->respond([
                'success' => 1, 'token' => $token, 'id' => $user['id']
            ]);
        } else {
            
            return $this->respond([
                'success' => 0, 'message' => 'Akun dengan nomor ini telepon tidak ditemukan.'
            ]);
        }
    }

    public function sendOTP($name, $phone, $otp) 
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Send OTP
        $appSetting = $db->table('mein_options')
                ->where('option_name', 'app_title')
                ->where('option_group', 'tarbiyya')
                ->get()->getRowArray();
        $namaAplikasi = $appSetting['option_value'] ?? null; 

        $message = "Halo {$name},\n\n";
        $message .= "Kami menerima permintaan penggantian kata sandi untuk akun Anda di aplikasi {$namaAplikasi}\n";
        $message .= "Untuk melanjutkan, silahkan masukan kode reset kata sandi berikut ini ke dalam aplikasi:\n\n";
        $message .= "*{$otp}*\n\n";
        $message .= "Salam,";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://app.saungwa.com/api/create-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
            'appkey' => '1e946b6b-e8ab-4c6a-ac7b-b2ae4204f095',
            'authkey' => 'Bl25APBU3Tcahyo9Rd0ZcCbloR4Gj1i6Ll5lRq6Y3J4DikKUS4',
            'to' => $phone,
            'message' => $message,
            'sandbox' => 'false'
            ),
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}