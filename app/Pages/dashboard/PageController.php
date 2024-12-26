<?php

namespace App\Pages\dashboard;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function index(): string
    {
        $data['name'] = 'Civue Cihuyyy';
        // return pageView('dashboard/index', $data);
        return view('welcome_message', $data);
    }

    public function detail($id, $nim = null): string
    {
        $data['name'] = 'Detail ' . $id  . ' dengan ' . $nim;
        return pageView('dashboard/index', $data);
    }
}
