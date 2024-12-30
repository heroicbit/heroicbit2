<?php namespace App\Pages\member\videos;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/videos/index', $this->data);
    }

}