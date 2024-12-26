<?php namespace App\Views\Pages\member\profile;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        $db = $this->initDBPesantren();
        $user = $this->checkToken();
        $data = [];

        $data['profile'] = $db->table('mein_users')
            ->select('mein_users.id, mein_users.name, 
                mein_users.email, mein_users.avatar, mein_users.phone')
            // ->where('id', $user->id)
            ->get()->getRowArray();

        return $data;
    }

}