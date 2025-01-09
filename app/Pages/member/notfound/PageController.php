<?php namespace App\Pages\member\notfound;

use App\Controllers\BaseController;

class PageController extends BaseController {

    public function getIndex()
    {
        $this->data['page_title'] = 'Halaman Tidak Ditemukan';
        return pageView('member/index', $this->data);
    }
    
    public function getContent()
    {
        $this->data['page_title'] = 'Halaman Tidak Ditemukan';
        return pageView('member/notfound/index', $this->data);
    }

}