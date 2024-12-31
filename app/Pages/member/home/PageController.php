<?php 

namespace App\Pages\member\home;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Get setting
        $settings = $db->table('mein_options')
            ->whereIn('option_group', ['site','tarbiyya'])
            ->get()->getResultArray();

        $this->data['settings'] = array_combine(array_column($settings, 'option_name'), array_column($settings, 'option_value'));
            
        return pageView('member/home/index', $this->data);
    }

}