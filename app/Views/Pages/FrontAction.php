<?php

namespace App\Views\Pages;

abstract class FrontAction
{
    public $template = 'mobilekit';
    
    public $data;

    /**
     * Initiate global data here
     */
    public function __construct(){}

    /**
     * Child class should have these three methods 
     */
    public function render(){return [];}
    public function supply(){return [];}
    public function process(){return [];}

    public function _output()
    {
        // Set spesific global data for rendering here
        $this->data['theme_url'] = base_url($this->template . '/');
        
        return array_merge($this->data, $this->render());
    }
    
    public function _outputAjax()
    {
        // Set spesific global data for AJAX response here

        return array_merge($this->data, $this->supply());
    }

}
