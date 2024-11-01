<?php namespace App\Views\Pages\member\profile_edit;

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
            'email' => 'required|valid_email',
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

        // Set username from email without @blabla.bla
        $emailArr = explode('@', $validData['email']);
        $username = $emailArr[0];

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