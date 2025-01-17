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

        // Make sure the number begin with 62
		$username = substr($username, 0, 1)=='0' 
		? substr_replace($username, '62', 0, 1) 
		: $username;
		if(substr($username, 0, 1)=='8') 
			$username = '62'.$username;

        // Use database client
        // Get database pesantren
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
                $key = config('App')->jwtKey['secret'];
                $jwt = JWT::encode($userSession, $key, 'HS256');

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