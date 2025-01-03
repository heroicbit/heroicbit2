<?php namespace App\Pages\member\program_pesantren;

use App\Pages\member\PageController as MemberPageController;
use Symfony\Component\Yaml\Yaml;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/program_pesantren/index', $this->data);
    }

    public function getSupply()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

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
            $data['pages'][$pageSlug]['menu_title'] = $pagelist[$pageSlug];
        }

        return $this->respond($data);
    }

}