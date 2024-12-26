<?php

namespace App\Pages;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class FrontController extends BaseController
{
    // Global data
    public $data = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
        helper('Heroic\Helpers\pageview');

        // Get page data from route collection, which is injected from PagesRouter filter 
        $routeCollection = service('routes');
        $this->data = $routeCollection->pageData ?? [];
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
