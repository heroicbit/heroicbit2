<?php namespace App\Pages\member\profile\edit_info;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/profile/edit_info/index', $this->data);
    }

    public function getSupply()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        $logoSetting = $db->table('mein_options')
                          ->where('option_name', 'auth_logo')
                          ->where('option_group', 'app')
                          ->get()->getRowArray();
        $data['logo'] = $logoSetting['option_value'] ?? null;

        return $this->respond($data);
    }

    public function postIndex()
    {
        $validation = service('validation');

        $validation->setRules([
            "name" => 'required|min_length[2]',
            "short_description" => 'permit_empty|max_length[255]',
            "jobs" => 'permit_empty|max_length[255]',
        ]);

        if (! $validation->run($this->request->getPost())) {
            $errors = $validation->getErrors();
            return $this->respond([
                'success' => 0, 'errors' => $errors
            ]);
        }
        $validData = $validation->getValidated();

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        if (! $db) {
            return $this->respond(['success' => 0, 'message' => 'Pesantren tidak dikenali.']);
        }

        // Update nama & branding di mein_users
        $db->table('mein_users')
            ->where('id', $user->user_id)
            ->update([
                "name"              => $validData['name'],
                "short_description" => $validData['short_description'],
            ]);

        // Update atau insert profil pengguna (mein_user_profile)
        $birthday = $this->request->getPost('birthday');
        $profileData = [
            "gender"   => $this->request->getPost('gender'),
            "birthday" => $birthday ? date('Y-m-d', strtotime($birthday)) : null,
            "jobs"     => $validData['jobs'],
        ];

        $exists = $db->table('mein_user_profile')
                     ->where('user_id', $user->user_id)
                     ->get()->getRow();

        if ($exists) {
            $db->table('mein_user_profile')
                ->where('user_id', $user->user_id)
                ->update($profileData);
        } else {
            $profileData['user_id'] = $user->user_id;
            $db->table('mein_user_profile')->insert($profileData);
        }

        return $this->respond([
            'success' => 1, 
            'message' => 'Data profil berhasil diperbaharui.',
            'data' => $validData
        ]);
    }
}