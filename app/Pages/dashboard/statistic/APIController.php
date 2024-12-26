<?php

namespace App\Pages\dashboard\statistic;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController
{
    public function index()
    {
        $data['name'] = 'Statistic';
        $data['nim'] = '0808538';

        return $this->respond($data);
    }
    
    public function show($id = null, $nim = null)
    {
        $data['name'] = 'Toni Haryanto';
        $data['id'] = $id;
        $data['nim'] = $nim;
        
        return $this->respond($data);
    }
}
