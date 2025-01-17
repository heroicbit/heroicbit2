<?php namespace App\Pages\member\notfound;

use App\Controllers\BaseController;

class PageController extends BaseController 
{    
    public function getContent()
    {
        $this->data['page_title'] = 'Halaman Tidak Ditemukan';
        return pageView('member/notfound/index', $this->data);
    }

}