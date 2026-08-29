<?php namespace App\Pages\member\videos;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/videos/index', $this->data);
    }

    public function getSupply()
    {
        // Retrieve extension attributes
        $request = service('request');
		$page = (int)($request->getGet('page') ?? 1);
		$status = $request->getGet('status') ?? 'publish';
		$perpage = (int)($request->getGet('perpage') ?? 15);
		$offset = ($page-1) * $perpage;

        // Get post data
        // Video disimpan di tabel `mein_posts` (type='video'); ID/URL YouTube ada di kolom `embed_video`.
		$query = "SELECT `p`.`id`, `p`.`title`, `p`.`content`, `p`.`author` as `author_id`,
            `u`.`avatar`, `u`.`name` as `author_name`, `p`.`status` as `status`,
            `p`.`created_at` as `created_at`, `p`.`published_at` as `published_at`,
            `p`.`featured_image`, `p`.`intro`, `p`.`embed_video` as `youtube_url`,
            `p`.`video_duration`
            FROM `mein_posts` `p`
            JOIN `mein_users` `u` ON `u`.`id` = `p`.`author`
            WHERE `p`.`status` = :status:
            AND `p`.`type` = 'video'
            AND (`p`.`embed_video` IS NOT NULL AND `p`.`embed_video` != '')
            ORDER BY `p`.`published_at` DESC
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
            // embed_video di mein_posts berisi ID YouTube langsung (bukan URL).
            $youtubeId = (string)($post['youtube_url'] ?? '');
            $posts[$key]['youtube_id'] = $youtubeId !== '' ? $youtubeId : null;

            // Thumbnail: featured_image, fallback ke thumbnail YouTube.
            $thumb = !empty($post['featured_image'])
                ? $post['featured_image']
                : 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
            $posts[$key]['medias'] = [['url' => $thumb]];

            unset($posts[$key]['featured_image']);
            unset($posts[$key]['youtube_url']);
        }
        $data['videos'] = $posts;

		return $this->respond([
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		]);
    }

}