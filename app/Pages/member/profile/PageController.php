<?php namespace App\Pages\member\profile;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/profile/index', $this->data);
    }

}