<?php 

namespace App\Pages\member\home;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;

class PageController extends MemberPageController
{
    use ResponseTrait;

    public function getContent()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        // Get setting
        $settings = $db->table('mein_options')
            ->whereIn('option_group', ['site','tarbiyya'])
            ->get()->getResultArray();

        $this->data['settings'] = array_combine(array_column($settings, 'option_name'), array_column($settings, 'option_value'));
            
        return pageView('member/home/index', $this->data);
    }

    public function getSupply()
    {
        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

        $logoSetting = $db->table('mein_options')
                          ->where('option_group', 'tarbiyya')
                          ->where('option_name', 'navbar_logo')
                          ->get()->getRowArray();
        $data['logo'] = $logoSetting['option_value'] ?? null; 

        $psbURLSetting = $db->table('mein_options')
                            ->where('option_group', 'pendaftaran')
                            ->where('option_name', 'pendaftaran_form_url')
                            ->get()->getRowArray();
        $data['psb_url'] = $psbURLSetting['option_value'] ?? null; 
        
        /**
         * Get video data
         **/
		$videoQuery = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, 
        `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
        `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
        `mein_microblogs`.`created_at` as `created_at`, 
        `mein_microblogs`.`published_at` as `published_at`
        FROM `mein_microblogs`
        JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
        WHERE `mein_microblogs`.`status` = 'publish' 
        AND (`mein_microblogs`.`youtube_url` IS NOT NULL AND `mein_microblogs`.`youtube_url` != '')
        ORDER BY `mein_microblogs`.`published_at` DESC
        LIMIT 5";

        $videos = $db->query($videoQuery)->getResultArray();
        foreach($videos as $key => $post)
        {
            $videos[$key]['medias'] = json_decode($videos[$key]['medias'], true);
        }
        $data['videos'] = $videos;
        
        /**
         * Get post data
         **/
		$postQuery = "SELECT `mein_microblogs`.`id`, `medias`, `title`, `content`, 
        `total_like`, `total_comment`, `author` as `author_id`, mein_users.avatar,
        `mein_users`.`name` as `author_name`, `mein_microblogs`.`status` as `status`, 
        `mein_microblogs`.`created_at` as `created_at`, 
        `mein_microblogs`.`published_at` as `published_at`
        FROM `mein_microblogs`
        JOIN `mein_users` ON `mein_users`.`id`=`mein_microblogs`.`author`
        WHERE `mein_microblogs`.`status` = 'publish' 
        AND (`mein_microblogs`.`youtube_url` IS NULL OR `mein_microblogs`.`youtube_url` = '')
        ORDER BY `mein_microblogs`.`published_at` DESC
        LIMIT 5";

        $posts = $db->query($postQuery)->getResultArray();
        foreach($posts as $key => $post)
        {
            $posts[$key]['medias'] = json_decode($posts[$key]['medias'], true);
        }
        $data['posts'] = $posts;

        /**
         * Get pengumuman data
         **/
        $newestPengumumanQuery =  "SELECT id, title, publish_date 
        FROM `pengumuman`
        WHERE status = 'publish'
        ORDER BY publish_date DESC 
        LIMIT 1";
        $data['pengumuman'] = $db->query($newestPengumumanQuery)->getRowArray();

        return $this->respond($data);
    }

}