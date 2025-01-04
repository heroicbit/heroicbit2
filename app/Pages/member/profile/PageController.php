<?php namespace App\Pages\member\profile;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/profile/index', $this->data);
    }

    public function getSupply()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $user = $Tarbiyya->checkToken();
        $data = [];

        $data['profile'] = $db->table('mein_users')
            ->select('mein_users.id, mein_users.name, 
                mein_users.email, mein_users.avatar, mein_users.phone')
            ->where('id', $user->user_id)
            ->get()->getRowArray();

        return $this->respond($data);
    }

}