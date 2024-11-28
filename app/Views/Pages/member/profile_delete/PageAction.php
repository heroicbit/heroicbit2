<?php namespace App\Views\Pages\member\profile_delete;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function process()
    {
        // Get database pesantren
        $db = $this->initDBPesantren();
        $user = $this->checkToken();

        $phone = $this->request->getPost('whatsapp');
        $password = $this->request->getPost('password');

         // Make sure the number begin with 62
		$phone = substr($phone, 0, 1)=='0' 
		? substr_replace($phone, '62', 0, 1) 
		: $phone;
		if(substr($phone, 0, 1)=='8') 
			$phone = '62'.$phone;

        $found = $db->query('SELECT password FROM mein_users where id = :id: AND phone = :phone:', ['id' => $user->user_id, 'phone' => $phone])->getRow();
        if($found) {
            $Phpass = new \App\Libraries\Phpass();
            if($Phpass->CheckPassword($password, $found->password))
            {
                // Delete user
                $db->query('DELETE FROM mein_users where id = :id:', ['id' => $user->user_id]);

                header('Content-Type', 'application/json');
                echo json_encode([
                    'success' => 1, 'message' => 'Akun Anda berhasil dihapus.'
                ]);
                die;
            } else {
                header('Content-Type', 'application/json');
                echo json_encode([
                    'success' => 0, 'message' => 'Password yang anda masukkan salah.'
                ]);
                die;
            }
        } else {
            header('Content-Type', 'application/json');
            echo json_encode([
                'success' => 0, 'message' => 'Nomor WhatsApp yang anda masukkan salah.'
            ]);
            die;
        }
    }
}