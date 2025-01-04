<?php namespace App\Pages\member\intro;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    // Load member layout
    public function getIndex()
	{
		$this->data['page_title'] = 'Kode Pesantren';

		return pageView('member/index', $this->data);
	}
    
    public function getContent()
    {
        return pageView('member/intro/index', $this->data);
    }

}