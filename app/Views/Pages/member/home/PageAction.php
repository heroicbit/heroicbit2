<?php namespace App\Views\Pages\member\home;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Get post data
		$query = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, 
        `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
        `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
        `mein_microblogs`.`created_at` as `created_at`, 
        `mein_microblogs`.`published_at` as `published_at`
        FROM `mein_microblogs`
        JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
        WHERE `mein_microblogs`.`status` = 'publish'
        ORDER BY `mein_microblogs`.`published_at` DESC
        LIMIT 5";

        $db = $this->initDBPesantren();
        $posts = $db->query($query)->getResultArray();

        foreach($posts as $key => $post)
        {
            $posts[$key]['medias'] = json_decode($posts[$key]['medias'], true);
        }
        $data['microblog'] = $posts;

        return $data;
    }

}