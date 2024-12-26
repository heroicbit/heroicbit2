<?php namespace App\Pages\member\profile_edit;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/profile_edit/index', $this->data);
    }

    public function supplyx(): array
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        $logoSetting = $db->table('mein_options')
                          ->where('option_name', 'auth_logo')
                          ->where('option_group', 'app')
                          ->get()->getRowArray();
        $data['logo'] = $logoSetting['option_value'] ?? null; 

        return $data;
    }

    public function process()
    {
        $validation = service('validation');

        $validation->setRules([
            'fullname' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'whatsapp' => 'required',
            'password' => 'required|max_length[50]|min_length[6]',
            'repeat_password' => 'required|matches[password]',
        ]);

        if (! $validation->run($this->request->getPost())) {
            $errors = $validation->getErrors();
            echo json_encode([
                'success' => 0, 'errors' => $errors
            ]);    
            die;
        }
        $validData = $validation->getValidated();

        // Set username from email without @blabla.bla
        $emailArr = explode('@', $validData['email']);
        $username = $emailArr[0];

        // Get database pesantren
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Register user to database
        $Phpass = new \App\Libraries\Phpass();
        $password = $Phpass->HashPassword($validData['password']);
        
        helper('text');
        $otp = random_string('numeric', 6);
        $token = sha1($otp);

        $userData = [
            'name' => $validData['fullname'],
            'email' => $validData['email'],
            'phone' => $validData['whatsapp'],
            'username' => $username,
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
        } else {
            echo json_encode([
                'success' => 0, 'message' => 'Gagal menambahkan akun. Silahkan coba kembali.'
            ]);
        }
        die;
    }
}