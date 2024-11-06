<?php 

namespace App\Views\Pages;

class AdminAction extends FrontAction 
{
    public function __construct($pagedata = [])
    {
        parent::__construct($pagedata);
        
        // Redirect if not logged in
        if(! user_id()) {
            response()->redirect('login');
        }
        
        // Setup sidebar menu structure
        $this->setupSidebar();
    }

    private function setupSidebar()
    {
        $this->data['sidebarMenu'] = config('Config\\SidebarMenu')->menu;
    }
}
