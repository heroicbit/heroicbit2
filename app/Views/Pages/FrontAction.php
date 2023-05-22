<?php

namespace App\Views\Pages;

use CodeIgniter\Config\Factories;

abstract class FrontAction
{
    public $template = 'mobilekit';
    
    public $data;

    /**
     * Initiate global data here
     */
    public function __construct()
    {
        helper('Heroic\Helpers\module');

        $this->data['theme_url'] = base_url($this->template . '/');
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
