<?php namespace App\Pages\member\login;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class PageController extends MemberPageController 
{
    use ResponseTrait;
    
    public function getContent()
    {
        return pageView('member/login/index', $this->data);
    }

    // Check login
    public function postIndex()
    {
        $username = strtolower($this->request->getPost('username'));
        $password = $this->request->getPost('password');
        
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Check login to database directly using $db
        $found = $db->query('SELECT * FROM mein_users where (email = :username: OR phone = :username:) AND status = "active"', ['username' => $username])->getRow();
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
                $jwt = JWT::encode($userSession, config('App')->jwtKey['secret'], 'HS256');

                $user = [
                    'name' => $found->name,
                    'email' => $found->email,
                    'phone' => $found->phone
                ];
            }
        }

        return $this->respond([
            'found' => $jwt ? 1 : 0,
            'jwt' => $jwt,
            'user' => $user ?? []
        ]);
    }

}
