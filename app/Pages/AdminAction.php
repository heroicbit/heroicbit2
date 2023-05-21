<?php 

namespace App\Pages;

class AdminAction extends FrontAction 
{
    public $template = 'admin';

    public function __construct()
    {
        parent::__construct();
    }
}
