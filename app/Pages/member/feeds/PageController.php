<?php namespace App\Pages\member\feeds;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/feeds/index', $this->data);
    }

}