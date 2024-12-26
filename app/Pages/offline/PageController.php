<?php namespace App\Pages\offline;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function index()
    {
        $this->data['page_title'] = 'You are Offline';
        return pageView('offline/index', $this->data);
    }

}