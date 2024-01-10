<?php

namespace App\Views\Pages;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


abstract class FrontAction
{
    // Initiate properties to hold request, response and logger object from controller
    protected $request, $response, $logger;

    // Set default template
    public $template = 'mobilekit';
    
    // Global data
    public $data = [];

    /**
     * Initiate global data here
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->logger   = $logger;

        // Run init()
        $this->init();

        // Load module helper
        helper('Heroic\Helpers\module');

        setting('Theme.themeUrl', base_url($this->template . '/'));
    }

    // Require to be called after instantiate
    public function init(){}

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
