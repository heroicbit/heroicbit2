<?php namespace App\Pages\member\feeds;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/feeds/index', $this->data);
    }

    public function getSupply()
    {
        // Retrieve extension attributes
		$page = (int)($this->request->getGet('page') ?? 1);
		$status = $this->request->getGet('status') ?? 'publish';
		$perpage = (int)($this->request->getGet('perpage') ?? 15);
		$offset = ($page-1) * $perpage;

        // Get post data
		$query = "SELECT `view_union_posts`.`id`, `medias`, `featured_image`, `title`, `content`, 
            `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
            `mein_users`.`name` as `author_name`, `view_union_posts`.`status` as `status`, 
            `view_union_posts`.`created_at` as `created_at`, 
            `view_union_posts`.`published_at` as `published_at`
            FROM `view_union_posts`
            JOIN `mein_users` ON `mein_users`.`id`=`view_union_posts`.`author`
            WHERE `view_union_posts`.`status` = :status:
            AND (`view_union_posts`.`youtube_url` IS NULL OR `view_union_posts`.`youtube_url` = '')
            ORDER BY `view_union_posts`.`published_at` DESC
            LIMIT :offset:, :perpage:";


        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        $posts = $db->query($query, [
            'status' => $status,
            'offset' => $offset,
            'perpage' => $perpage
        ])->getResultArray();
  
        foreach($posts as $key => $post)
        {
        	$posts[$key]['medias'] = json_decode($posts[$key]['medias'], true);
        }
        $data['posts'] = $posts;

		return $this->respond([
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		]);
    }

}