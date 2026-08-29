<?php namespace App\Pages\member\feeds\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController
{
    
    public function getContent()
    {
        return pageView('member/feeds/detail/index', $this->data);
    }

    public function getSupply($id = null)
    {
        // Get post data
		$query = "SELECT `mein_posts`.`id`, `featured_image`, `title`, `content`, 
            `author` as `author_id`, mein_users.avatar,
            `mein_users`.`name` as `author_name`, `mein_posts`.`status` as `status`, 
            `mein_posts`.`created_at` as `created_at`, 
            `mein_posts`.`published_at` as `published_at`
            FROM `mein_posts`
            LEFT JOIN `mein_users` ON `mein_users`.`id`=`mein_posts`.`author`
            WHERE `mein_posts`.`status` = 'publish'
            AND `mein_posts`.`id` = :id:";

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        // dd($query);
        $post = $db->query($query, ['id' => $id])->getResultArray();
        $post[0]['medias'] = $post[0]['featured_image'] ? [['url' => $post[0]['featured_image']]] : [];
        $data['post'] = $post;

		return $this->respond([
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		]);
    }

}