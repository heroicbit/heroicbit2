<?php namespace App\Pages\member\register\confirm;

use App\Pages\member\PageController as MemberPageController;
use Firebase\JWT\JWT;

class PageController extends MemberPageController 
{
    public function getContent()
    {
        return pageView('member/register/confirm/index', $this->data);
    }
    
    public function postIndex()
    {
        $request = service('request');

        $token = $request->getPost('token');
        $otp = $request->getPost('otp');
        $id = $request->getPost('id');

        // Get database pesantren
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Get user
        $query = "SELECT otp, token, email FROM mein_users WHERE id = :id:";
        $user = $db->query($query, ['id' => $id])->getRow();
        if($user?->otp != $otp || $user?->token != $token) {
            return $this->respond([
                'success' => 0, 'message' => 'Kode OTP yang anda masukkan salah.'
            ]);
        } else {
            // Activate user status
            $query = "UPDATE mein_users SET status = 'active', token = NULL, otp = NULL WHERE id = :id:";
            $db->query($query, ['id' => $id]);

            // Create JWT
            $userSession = [
                'logged_in' => true,
                'user_id' => $id,
                'email' => $user->email,
                'timestamp' => time()
            ];
            $key = config('App')->jwtKey['secret'];
            $jwt = JWT::encode($userSession, $key, 'HS256');

            return $this->respond([
                'success' => 1, 'jwt' => $jwt
            ]);
        }
    }

    public function postResend() 
    {
        $id = $this->request->getPost('id');
        $token = $this->request->getPost('token');

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $query = "SELECT name, phone, token FROM mein_users WHERE id = :id:";
        $user = $db->query($query, ['id' => $id])->getRow();
        if(strcmp($user?->token, $token) !== 0) {
            header('Content-Type', 'application/json');
            return $this->respond([
                'success' => 0, 'message' => 'Token invalid.'
            ]);
        }
        
        // Generate new OTP and token
        helper('text');
        $otp = random_string('numeric', 6);
        $token = sha1($otp);

        // Update new otp and token to database
        $query = "UPDATE mein_users SET otp = :otp:, token = :token: WHERE id = :id:";
        $db->query($query, ['otp' => $otp, 'token' => $token, 'id' => $id]);

        // Send OTP
        $appSetting = $db->table('mein_options')
                          ->where('option_name', 'app_title')
                          ->where('option_group', 'tarbiyya')
                          ->get()->getRowArray();
        $namaAplikasi = $appSetting['option_value'] ?? null; 

        $message = "Halo {$user->name},\n            
Terima kasih telah mendaftar di aplikasi {$namaAplikasi}
Untuk melanjutkan proses pendaftaran, silahkan masukan kode registrasi berikut ini ke dalam aplikasi:\n
*{$otp}*\n
Salam,";
        $Tarbiyya->sendWhatsapp($user->phone, $message);

        return $this->respond([
            'success' => 1, 'message' => 'Kode OTP berhasil dikirim ulang.', 'token' => $token, 'id' => $id
        ]);
    }
}