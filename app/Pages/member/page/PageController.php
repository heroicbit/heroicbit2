<?php namespace App\Pages\member\page;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/page/index', $this->data);
    }

}