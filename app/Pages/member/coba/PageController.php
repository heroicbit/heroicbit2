<?php namespace App\Pages\member\coba;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController
{
    public function getContent()
    {
        $data = [];
        return pageView('member/coba/index', $data);
    }

    public function postIndex() {
        $method = $this->request->getMethod();
        $token = $this->request->getPost('token');
        print_r([$method, $token]);
    }
}
