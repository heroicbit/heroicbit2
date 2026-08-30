<?php namespace App\Pages\member\profile\edit_password;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/profile/edit_password/index', $this->data);
    }

    public function postIndex()
    {
        $validation = service('validation');

        $validation->setRules([
            'password'        => 'required|max_length[50]|min_length[6]',
            'repeat_password' => 'required|matches[password]',
        ]);

        if (! $validation->run($this->request->getPost())) {
            return $this->respond([
                'success' => 0,
                'errors'  => $validation->getErrors(),
            ]);
        }
        $validData = $validation->getValidated();

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        if (! $db) {
            return $this->respond(['success' => 0, 'message' => 'Pesantren tidak dikenali.']);
        }

        // Hash password baru dengan Phpass (gaya WordPress)
        $Phpass = new \App\Libraries\Phpass();
        $password = $Phpass->HashPassword($validData['password']);

        $db->table('mein_users')
            ->where('id', $user->user_id)
            ->update(['password' => $password]);

        return $this->respond([
            'success' => 1,
            'message' => 'Kata sandi berhasil diubah.',
        ]);
    }
}
