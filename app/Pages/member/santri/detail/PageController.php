<?php namespace App\Pages\member\santri\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/santri/detail/index', $this->data);
    }

    public function getSupply($id = null)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $santri = $db->query("SELECT s.*, c.id as class_id, c.class_name
            FROM md_santri s
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            WHERE s.id = :id:
            AND s.status = 'student'", ['id' => $id])->getRow();

        if(!$santri){
            return $this->respond(['found' => 0, 'message' => 'Santri tidak ditemukan']);
        }

        return $this->respond([
            'found' => 1,
            'santri' => $santri
        ]);
    }

    public function getDetailPresensi($student_id)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $found = $db->query("SELECT * FROM `md_attendance` 
            WHERE `student_id` = :student_id: AND `present` IS NULL
            ORDER BY date DESC", 
            ['student_id' => $student_id])->getResultArray();

        if($found){
            $presensi = array_combine(array_column($found, 'date'), $found);
            return $this->respond([
                'found' => count($found),
                'presensi' => $presensi
            ]);
        }

        return $this->respond(['found' => 0, 'message' => 'Presensi tidak ditemukan']);
    }

    public function deleteIndex($id = null)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();

        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $deleted = $db->query(
            "DELETE FROM md_student_user WHERE user_id = :user_id: AND student_id = :student_id:",
            ['user_id' => $user->user_id, 'student_id' => $id]
        );

        if ($deleted && $db->affectedRows() > 0) {
            return $this->respond(['status' => 'success', 'message' => 'Santri berhasil dihapus dari perwalian']);
        }

        return $this->respond(['status' => 'failed', 'message' => 'Gagal menghapus santri dari perwalian'], 400);
    }

}