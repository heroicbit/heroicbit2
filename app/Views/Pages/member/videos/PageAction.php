<?php namespace App\Views\Pages\member\videos;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Retrieve extension attributes
        $request = service('request');
		$page = (int)($request->getGet('page') ?? 1);
		$status = $request->getGet('status') ?? 'publish';
		$perpage = (int)($request->getGet('perpage') ?? 5);
		$offset = ($page-1) * $perpage;

        // Get post data
		$query = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, `youtube_url`,
            `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
            `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
            `mein_microblogs`.`created_at` as `created_at`, 
            `mein_microblogs`.`published_at` as `published_at`
            FROM `mein_microblogs`
            JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
            WHERE `mein_microblogs`.`status` = :status:
            AND (`mein_microblogs`.`youtube_url` IS NOT NULL OR `mein_microblogs`.`youtube_url` != '')
            ORDER BY `mein_microblogs`.`published_at` DESC
            LIMIT :offset:, :perpage:";

        $db = $this->initDBPesantren();
        $posts = $db->query($query, [
            'status' => $status,
            'offset' => $offset,
            'perpage' => $perpage
        ])->getResultArray();
  
        foreach($posts as $key => $post)
        {
        	$posts[$key]['medias'] = json_decode($posts[$key]['medias'], true);
            parse_str(parse_url($posts[$key]['youtube_url'], PHP_URL_QUERY), $queryParams);
            $posts[$key]['youtube_id'] = isset($queryParams['v']) ? $queryParams['v'] : null;
        }
        $data['videos'] = $posts;

		return [
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		];
    }

}