<?php

namespace App\Pages\dashboard\statistic;

use Heroic\Controllers\BasePageController;

class PageController extends BasePageController
{
    public function index($name = null): string
    {
        $data['name'] = 'in Statistic ' . $name;
        return pageView('dashboard/statistic/index', $data);
    }
}
