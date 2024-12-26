<?php namespace App\Pages\member\offline;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function index()
    {
        $this->data['page_title'] = 'You are Offline';
        return pageView('member/offline/index', $this->data);
    }

}