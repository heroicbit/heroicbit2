<?php

namespace App\Views\Pages;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


abstract class FrontAction
{
    // Global data
    public $data = [];

    /**
     * Initiate global data here
     */
    public function __construct()
    {
        // Load module helper
        helper('Heroic\Helpers\module');
    }

    /**
     * Child class should have these three methods 
     */
    public function render(){return [];}
    public function supply(){return [];}
    public function process(){return [];}

    public function _output()
    {
        // Set spesific global data for rendering here
        
        return array_merge($this->data, $this->render());
    }
    
    public function _outputAjax()
    {
        // Set spesific global data for AJAX response here

        return array_merge($this->data, $this->supply());
    }

}
