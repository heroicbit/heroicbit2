<?php namespace App\Views\Pages\member\page;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Retrieve extension attributes
        $request = service('request');
        $uri = $request->getUri();
        $slug = $uri->getSegment(3);

        // Get post data
		$query = "SELECT * FROM `mein_posts` WHERE `type` = 'page' AND `slug` = :slug:";

        $db = $this->initDBPesantren();
        $data['page'] = $db->query($query, ['slug' => $slug])->getRowArray();

		return [
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		];
    }

}