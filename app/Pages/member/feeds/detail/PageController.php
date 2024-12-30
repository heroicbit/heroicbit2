<?php namespace App\Pages\member\feeds\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController
{
    
    public function get_ajax()
    {
        return pageView('member/feeds/detail/index', $this->data);
    }

}