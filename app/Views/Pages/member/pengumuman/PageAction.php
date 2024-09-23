<?php namespace App\Views\Pages\member\pengumuman;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply(){
        $posts = [
            [
                "id" => 1,
                "title" => "suntay aut facere repellat provident occaecati excepturi optio reprehenderit",
                "body" => "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto",
                "date" => "20 September 2019, 10:00 WIB"
            ],
            [
                "id" => 2,
                "title" => "qui est esse",
                "body" => "est rerum tempore vitae\nsequi sint nihil reprehenderit dolor beatae ea dolores neque\nfugiat blanditiis voluptate porro vel nihil molestiae ut reiciendis\nqui aperiam non debitis possimus qui neque nisi nulla",
                "date" => "20 September 2019, 10:00 WIB"
            ],
            [
                "id" => 3,
                "title" => "ea molestias quasi exercitationem repellat qui ipsa sit aut",
                "body" => "et iusto sed quo iure\nvoluptatem occaecati omnis eligendi aut ad\nvoluptatem doloribus vel accusantium quis pariatur\nmolestiae porro eius odio et labore et velit aut",
                "date" => "20 September 2019, 10:00 WIB"
            ],
            [
                "id" => 4,
                "title" => "eum et est occaecati",
                "body" => "ullam et saepe reiciendis voluptatem adipisci\nsit amet autem assumenda provident rerum culpa\nquis hic commodi nesciunt rem tenetur doloremque ipsam iure\nquis sunt voluptatem rerum illo velit",
                "date" => "20 September 2019, 10:00 WIB"
            ],
            [
                "id" => 5,
                "title" => "nesciunt quas odio",
                "body" => "repudiandae veniam quaerat sunt sed\nalias aut fugiat sit autem sed est\nvoluptatem omnis possimus esse voluptatibus quis\nest aut tenetur dolor neque",
                "date" => "20 September 2019, 10:00 WIB"
            ]
        ];

        $output = compact('posts');
        return $output;
    }

}