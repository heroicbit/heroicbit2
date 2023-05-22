<?php 

namespace App\Views\Pages;

class AdminAction extends FrontAction 
{
    public $template = 'admin';

    public function __construct()
    {
        parent::__construct();

        $this->setupSidebar();
    }

    private function setupSidebar()
    {

    }
}
