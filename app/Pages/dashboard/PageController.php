<?php

namespace App\Pages\dashboard;

use Heroic\Controllers\BasePageController;

class PageController extends BasePageController
{
    public function index($id = null, $nim = null): string
    {
        $data['name'] = 'Detail ' . $id  . ' dengan ' . $nim;

        return pageView('dashboard/index', $data);
    }

}
