<?php namespace App\Pages\member\register;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/register/index', $this->data);
    }

    public function detail($token)
    {
        return pageView('member/register/confirm', $this->data);
    }

}