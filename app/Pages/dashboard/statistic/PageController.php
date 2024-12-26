<?php

namespace App\Pages\dashboard\statistic;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function index(): string
    {
        $data['name'] = 'in Statistic';
        return pageView('dashboard/statistic/index', $data);
    }
}
