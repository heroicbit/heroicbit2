<?php namespace App\Pages\member\videos\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/videos/detail/index', $this->data);
    }

    public function supplys()
    {
        // Retrieve extension attributes
        $request = service('request');
        $uri = $request->getUri();
        $id = $uri->getSegment(4);

        // Get post data
		$query = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, `youtube_url`,
            `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
            `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
            `mein_microblogs`.`created_at` as `created_at`, 
            `mein_microblogs`.`published_at` as `published_at`
            FROM `mein_microblogs`
            JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
            WHERE `mein_microblogs`.`status` = 'publish'
            AND (`mein_microblogs`.`youtube_url` IS NOT NULL OR `mein_microblogs`.`youtube_url` != '')
            AND `mein_microblogs`.`id` = :id:";

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $post = $db->query($query, ['id' => $id])->getResultArray();
        $post[0]['medias'] = json_decode($post[0]['medias'], true);
        $data['video'] = $post;

        // Parse URL untuk mendapatkan bagian query string
        parse_str(parse_url($data['video'][0]['youtube_url'], PHP_URL_QUERY), $queryParams);
        $data['video'][0]['youtube_id'] = isset($queryParams['v']) ? $queryParams['v'] : null;

		return [
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		];
    }

}