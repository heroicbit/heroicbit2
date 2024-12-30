<?php 

namespace App\Pages\member\home;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/home/index', $this->data);
    }

}