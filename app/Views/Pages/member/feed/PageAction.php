<?php namespace App\Views\Pages\member\feed;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Retrieve extension attributes
        $uri = $this->request->getUri();
        $id = $uri->getSegment(4);

        // Get post data
		$query = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, 
            `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
            `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
            `mein_microblogs`.`created_at` as `created_at`, 
            `mein_microblogs`.`published_at` as `published_at`
            FROM `mein_microblogs`
            JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
            WHERE `mein_microblogs`.`status` = 'publish'
            AND `mein_microblogs`.`id` = :id:";

        $db = $this->initDBPesantren();
        $post = $db->query($query, ['id' => $id])->getResultArray();
        $post[0]['medias'] = json_decode($post[0]['medias'], true);
        $data['post'] = $post;

		return [
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		];
    }

}