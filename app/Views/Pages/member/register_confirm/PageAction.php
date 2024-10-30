<?php namespace App\Views\Pages\member\register_confirm;

use App\Views\Pages\member\PageAction as MemberPageAction;
use Firebase\JWT\JWT;

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

        $token = $request->getPost('token');
        $otp = $request->getPost('otp');
        $id = $request->getPost('id');

        // Get database pesantren
        $db = $this->initDBPesantren();

        // Get user
        $query = "SELECT otp, token, email FROM mein_users WHERE id = :id:";
        $user = $db->query($query, ['id' => $id])->getRow();
        if($user?->otp != $otp || $user?->token != $token) {
            echo json_encode([
                'success' => 0, 'message' => 'Kode OTP yang anda masukkan salah.'
            ]);
            die;
        } else {
            // Activate user status
            $query = "UPDATE mein_users SET status = 'active' WHERE id = :id:";
            $db->query($query, ['id' => $id]);

            // Create JWT
            $userSession = [
                'logged_in' => true,
                'user_id' => $id,
                'email' => $user->email,
                'timestamp' => time()
            ];
            $key = config('AuthJWT')->keys['default'][0]['secret'];
            $jwt = JWT::encode($userSession, $key, 'HS256');

            echo json_encode([
                'success' => 1, 'jwt' => $jwt
            ]);
            die;
        }
    }
}