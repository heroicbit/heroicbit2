<?php namespace App\Views\Pages\member\register;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        $db = $this->initDBPesantren();

        $logoSetting = $db->table('mein_options')
                          ->where('option_name', 'auth_logo')
                          ->where('option_group', 'app')
                          ->get()->getRowArray();
        $data['logo'] = $logoSetting['option_value'] ?? null; 

        return $data;
    }

    public function process()
    {
        $request = service('request');
        $validation = service('validation');

        $validation->setRules([
            'fullname' => 'required|min_length[2]',
            'whatsapp' => 'required',
            'password' => 'required|max_length[50]|min_length[6]',
            'repeat_password' => 'required|matches[password]',
        ]);

        if (! $validation->run($request->getPost())) {
            $errors = $validation->getErrors();
            echo json_encode([
                'success' => 0, 'errors' => $errors
            ]);    
            die;
        }
        $validData = $validation->getValidated();

        // Make sure the number begin with 62
		$phone = substr($validData['whatsapp'], 0, 1)=='0' 
		? substr_replace($validData['whatsapp'], '62', 0, 1) 
		: $validData['whatsapp'];
		if(substr($phone, 0, 1)=='8') 
			$phone = '62'.$phone;

        // Get database pesantren
        $db = $this->initDBPesantren();

        // Register user to database
        $Phpass = new \App\Libraries\Phpass();
        $password = $Phpass->HashPassword($validData['password']);
        
        helper('text');
        $otp = random_string('numeric', 6);
        $token = sha1($otp);

        $userData = [
            'name' => $validData['fullname'],
            'phone' => $phone,
            'username' => $phone,
            'password' => $password,
            'token' => $token,
            'otp' => $otp
        ];
        $db->table('mein_users')->insert($userData);
        if($db->affectedRows() > 0) {
            echo json_encode([
                'success' => 1,
                'id' => $db->insertID(),
                'token' => $token
            ]);

            // Send OTP
            $appSetting = $db->table('mein_options')
                          ->where('option_name', 'app_title')
                          ->where('option_group', 'tarbiyya')
                          ->get()->getRowArray();
            $namaAplikasi = $appSetting['option_value'] ?? null; 

            $message = "Halo {$userData['name']},\n            
Terima kasih telah mendaftar di aplikasi {$namaAplikasi}
Untuk melanjutkan proses pendaftaran, silahkan masukan kode registrasi berikut ini ke dalam aplikasi:\n
*{$userData['otp']}*\n
Salam,";
            $this->sendOTP($phone, $message);
            
        } else {
            echo json_encode([
                'success' => 0, 'message' => 'Gagal menambahkan akun. Silahkan coba kembali.'
            ]);
        }
        die;
    }

    private function sendOTP($number, $message) 
    {
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
            'to' => $number,
            'message' => $message,
            'sandbox' => 'false'
            ),
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}