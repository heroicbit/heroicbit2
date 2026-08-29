<?php namespace App\Pages\member\videos\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/videos/detail/index', $this->data);
    }

    public function getSupply($id = null)
    {
        // Retrieve extension attributes
        $request = service('request');
        $uri = $request->getUri();

        // Get post data
        // Video disimpan di tabel `mein_posts` (type='video'); ID/URL YouTube ada di kolom `embed_video`.
		$query = "SELECT `p`.`id`, `p`.`title`, `p`.`content`, `p`.`author` as `author_id`,
            `u`.`avatar`, `u`.`name` as `author_name`, `p`.`status` as `status`,
            `p`.`created_at` as `created_at`, `p`.`published_at` as `published_at`,
            `p`.`featured_image`, `p`.`intro`, `p`.`embed_video` as `youtube_url`,
            `p`.`video_duration`
            FROM `mein_posts` `p`
            LEFT JOIN `mein_users` `u` ON `u`.`id` = `p`.`author`
            WHERE `p`.`status` = 'publish'
            AND (`p`.`embed_video` IS NOT NULL AND `p`.`embed_video` != '')
            AND `p`.`id` = :id:";

        // Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
        $post = $db->query($query, ['id' => $id])->getResultArray();

        // Video tidak ditemukan -> kembalikan array kosong, jangan sampai crash.
        // Frontend akan menampilkan state notFound bila data.video kosong.
        $data['video'] = [];
        if (!empty($post)) {
            // embed_video di mein_posts berisi ID YouTube langsung (bukan URL).
            $youtubeId = (string)($post[0]['youtube_url'] ?? '');
            $post[0]['youtube_id'] = $youtubeId !== '' ? $youtubeId : null;

            // Thumbnail: featured_image, fallback ke thumbnail YouTube.
            $thumb = !empty($post[0]['featured_image'])
                ? $post[0]['featured_image']
                : 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg';
            $post[0]['medias'] = [['url' => $thumb]];

            $data['video'] = $post;
        }

		return $this->respond([
			'response_code'    => 200,
			'response_message' => 'success',
			'data'			   => $data 
		]);
    }

}