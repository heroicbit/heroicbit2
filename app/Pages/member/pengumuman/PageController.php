<?php namespace App\Pages\member\pengumuman;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/pengumuman/index', $this->data);
    }

    public function getSupply()
    {
        $page = (int)($this->request->getGet('page') ?? 1);
		$status = $this->request->getGet('status') ?? 'publish';
		$perpage = (int)($this->request->getGet('perpage') ?? 7);
		$offset = ($page-1) * $perpage;

        // Get post data
		$query = "SELECT *
            FROM `pengumuman`
            WHERE status = :status:
            ORDER BY publish_date DESC
            LIMIT :offset:, :perpage:";

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $posts = $db->query($query, [
            'status' => $status,
            'offset' => $offset,
            'perpage' => $perpage
        ])->getResultArray();

        $data['pengumuman'] = $posts;
        $data['icons'] = $this->getPengumumanIcons();

        return $this->respond($data);
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