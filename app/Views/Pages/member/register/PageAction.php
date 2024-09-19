<?php namespace App\Views\Pages\member\register;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function process()
    {
        $request = service('request');
        $validation = service('validation');

        $validation->setRules([
            'password' => 'required|max_length[50]|min_length[6]',
            'repeat_password' => 'required|matches[password]',
            'email' => 'required|valid_email',
            'whatsapp' => 'required',
            'fullname' => 'required|min_length[2]',
        ]);

        if (! $validation->run($request->getPost())) {
            $errors = $validation->getErrors();
            print_r($errors);die;    
        }
        $validData = $validation->getValidated();

        // Set username from email without @blabla.bla
        $emailArr = explode('@', $validData['email']);
        $username = $emailArr[0];
        
        // Get header kodepesantren
        $headers = getallheaders();
        $kodePesantrenHashed = $headers['Pesantrenku-Id'];

        $Encrypter = service('encrypter');
        $dbname = $Encrypter->decrypt(hex2bin($kodePesantrenHashed));

        // Use database client
        $db = db_connect();
        $db->setDatabase($dbname);

        // Register user to database
        $Phpass = new \App\Libraries\Phpass();
        $password = $Phpass->HashPassword($validData['password']);
        $userData = [
            'name' => $validData['fullname'],
            'email' => $validData['email'],
            'phone' => $validData['whatsapp'],
            'username' => $username,
            'password' => $password
        ];
        $db->table('mein_users')->insert($userData);
        if($db->affectedRows() > 0) {
            echo json_encode([
                'success' => 1
            ]);
        } else {
            echo json_encode([
                'success' => 0
                , 'message' => 'Gagal menambahkan akun. Silahkan coba kembali.'
            ]);
        }
        die;
    }
}