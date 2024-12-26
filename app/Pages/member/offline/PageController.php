<?php namespace App\Pages\member\offline;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function supply()
    {
        return pageView('member/offline/index', $this->data);
    }

}