<?php namespace App\Views\Pages\member\program_pesantren;

use App\Views\Pages\member\PageAction as MemberPageAction;
use Symfony\Component\Yaml\Yaml;

class PageAction extends MemberPageAction {

    public function supply()
    {
        $db = $this->initDBPesantren();

        $pagelistQuery = $db->table('mein_options')
							->where('option_group', 'tarbiyya')
							->where('option_name', 'program_pagelist')
							->get()
							->getRowArray();

        $pagelist = Yaml::parse($pagelistQuery['option_value']);
        $pagelistSlug = array_keys($pagelist);
        
        // Get post data
		$query = "SELECT *
            FROM `mein_posts`
            WHERE type = 'page'
            AND status = 'publish'
            AND slug in :slugs:";
        $pages = $db->query($query, ['slugs' => $pagelistSlug])->getResultArray();
        $data['pages'] = [];
        foreach($pagelistSlug as $pageSlug)
        {
            $index = array_search($pageSlug, array_column($pages, 'slug'));
            $data['pages'][$pageSlug] = $pages[$index];
        }

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