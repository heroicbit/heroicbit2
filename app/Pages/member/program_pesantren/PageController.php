<?php namespace App\Pages\member\program_pesantren;

use App\Pages\member\PageController as MemberPageController;
use Symfony\Component\Yaml\Yaml;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        return pageView('member/program_pesantren/index', $this->data);
    }

}