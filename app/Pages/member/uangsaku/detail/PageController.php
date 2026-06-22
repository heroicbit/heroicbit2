<?php namespace App\Pages\member\uangsaku\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/uangsaku/detail/index', $this->data);
    }

    public function getSupply($nis = null)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        $db = $Tarbiyya->initDBPesantren();

        $santri = $db->query("SELECT s.*, c.id as class_id, c.class_name
            FROM md_santri s
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            JOIN md_student_user su ON su.student_id = s.id AND su.user_id = :user_id:
            WHERE s.nis = :nis:
            AND s.status = 'student'", [
                'user_id' => $user->user_id,
                'nis' => $nis
            ])->getRow();

        if (!$santri) {
            return $this->respond(['found' => 0, 'message' => 'Santri tidak ditemukan']);
        }

        return $this->respond([
            'found' => 1,
            'santri' => $santri
        ]);
    }

}