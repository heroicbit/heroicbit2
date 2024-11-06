<?php namespace App\Views\Pages\member\login;

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

        $sitenameSetting = $db->table('mein_options')
                          ->where('option_name', 'site_title')
                          ->where('option_group', 'site')
                          ->get()->getRowArray();
        $data['sitename'] = $sitenameSetting['option_value'] ?? null; 

        return $data;
    }

    public function process()
    {
        $username = strtolower($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        // Use database client
        $db = $this->initDBPesantren();

        // Check login to database directly using $db
        $found = $db->query('SELECT * FROM mein_users where email = :username: OR phone = :username:', ['username' => $username])->getRow();
        $jwt = null;
        if($found) {
            $Phpass = new \App\Libraries\Phpass();
            if($Phpass->CheckPassword($password, $found->password))
            {
                // Create JWT
                $userSession = [
                    'logged_in' => true,
                    'user_id' => $found->id,
                    'email' => $found->email,
                    'timestamp' => time()
                ];
                $key = config('AuthJWT')->keys['default'][0]['secret'];
                $jwt = JWT::encode($userSession, $key, 'HS256');
            }
        }

        echo json_encode([
            'found' => $jwt ? 1 : 0,
            'jwt' => $jwt
        ]);
        die;
    }

}