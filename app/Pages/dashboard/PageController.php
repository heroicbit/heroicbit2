<?php

namespace App\Pages\dashboard;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function getIndex($id = null, $nim = null): string
    {
        $data['name'] = 'Dashboard ' . $id  . ' dengan ' . $nim;

        return pageView('dashboard/index', $data);
    }

}
