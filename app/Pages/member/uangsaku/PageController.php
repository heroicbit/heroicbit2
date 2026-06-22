<?php namespace App\Pages\member\uangsaku;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/uangsaku/index', $this->data);
    }

    public function getSupply()
    {
        $request = service('request');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $santri = $db->query("SELECT su.*, s.*, c.id as class_id, c.class_name
            FROM md_student_user su
            JOIN md_santri s ON s.id = su.student_id
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            WHERE user_id = :user_id:
            AND s.status = 'student'", ['user_id' => $user->user_id])->getResultArray();

        return $this->respond([
            'santri' => $santri
        ]);
    }

}