<?php namespace App\Pages\member\page;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/page/index', $this->data);
    }

    public function getSupply($slug = null)
    {
        // Retrieve extension attributes
        $uri = $this->request->getUri();

        // Get post data
		$query = "SELECT * FROM `mein_posts` WHERE `type` = 'page' AND `slug` = :slug: AND `status` = 'publish'";

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $data['page'] = $db->query($query, ['slug' => $slug])->getRowArray();

		return $this->respond([
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		]);
    }

}