<?php

namespace App\Pages\dashboard\statistic;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    use \CodeIgniter\API\ResponseTrait;
    
    public function getIndex($name = null, $code = null): string
    {
        $data['name'] = 'in Statistic ' . $name . ', code ' . $code;
        return pageView('dashboard/statistic/index', $data);
    }

    public function getShow($id = null, $nim = null)
    {
        $data['name'] = 'Toni Haryanto';
        $data['id'] = $id;
        $data['nim'] = $nim;
        
        return $this->respond($data);
    }
}
