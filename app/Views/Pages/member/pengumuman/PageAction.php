<?php namespace App\Views\Pages\member\pengumuman;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Get post data
		$query = "SELECT *
            FROM `pengumuman`
            WHERE status = 'publish'
            ORDER BY publish_date DESC
            LIMIT 5";

        $db = $this->initDBPesantren();
        $posts = $db->query($query)->getResultArray();

        $data['pengumuman'] = $posts;
        $data['icons'] = $this->getPengumumanIcons();

        return $data;
    }

    private function getPengumumanIcons()
    {
        $icons = [
            "Undangan" => 'bi bi-envelope-paper',
            "Pemberitahuan" => 'bi bi-megaphone',
            "Agenda Sekolah" => 'bi bi-calendar-event',
            "Jadwal Ujian" => 'bi bi-calendar2-check',
            "Libur Sekolah" => 'bi bi-calendar',
            "Perpulangan" => 'bi bi-calendar',
            "Beasiswa" => 'bi bi-bank',
            "Penerimaan Siswa Baru" => 'bi bi-person-rolodex',
            "Pengumuman Darurat" => 'bi bi-exclamation-circle',
            "Rekrutmen" => 'bi bi-person-lines-fill',
            "Administrasi" => 'bi bi-display',
            "Evaluasi" => 'bi bi-clipboard-check',
            "Ekstrakurikuler" => 'bi bi-person-walking',
            "Peraturan" => 'bi bi-building-exclamation',
            "Kehilangan/Temuan" => 'bi bi-archive',
            "Sosialisasi Program" => 'bi bi-display',
        ];

        return $icons;
    }

}