<?php namespace App\Pages\member\intro;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/intro/index', $this->data);
    }

}