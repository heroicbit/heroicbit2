<?php

namespace App\Pages\dashboard\statistic;

use Yllumi\Ci4Pages\Controllers\BasePageController;

class PageController extends BasePageController
{
    public function getIndex($name = null, $code = null): string
    {
        $data['name'] = 'in Statistic ' . $name . ', code ' . $code;
        return pageView('dashboard/statistic/index', $data);
    }
}
