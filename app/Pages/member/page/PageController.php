<?php namespace App\Pages\member\page;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/page/index', $this->data);
    }

}