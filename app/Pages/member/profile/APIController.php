<?php namespace App\Pages\member\profile;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController 
{

    public function index()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $user = $Tarbiyya->checkToken();
        $data = [];

        $data['profile'] = $db->table('mein_users')
            ->select('mein_users.id, mein_users.name, 
                mein_users.email, mein_users.avatar, mein_users.phone')
            // ->where('id', $user->id)
            ->get()->getRowArray();

        return $this->respond($data);
    }

}