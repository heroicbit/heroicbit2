<?php namespace App\Views\Pages\member\login;

use App\Views\Pages\member\PageAction as MemberPageAction;
use Firebase\JWT\JWT;

class PageAction extends MemberPageAction {

    public function process()
    {
        $request = service('request');
        $username = strtolower($request->getPost('username'));
        $password = $request->getPost('password');
        
        // Get header kodepesantren
        $headers = getallheaders();
        $kodePesantrenHashed = $headers['Pesantrenku-Id'];

        $Encrypter = service('encrypter');
        $dbname = $Encrypter->decrypt(hex2bin($kodePesantrenHashed));

        // Use database client
        $db = db_connect();
        $db->setDatabase($dbname);

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
                $key = service('settings')->get('Encryption.key');
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