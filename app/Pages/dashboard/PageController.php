<?php

namespace App\Pages\dashboard;

use Yllumi\Ci4Pages\Controllers\BasePageController;

class PageController extends BasePageController
{
    public function getIndex($id = null, $nim = null): string
    {
        $data['name'] = 'Detail ' . $id  . ' dengan ' . $nim;

        return pageView('dashboard/index', $data);
    }

}
