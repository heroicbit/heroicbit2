<?php 

namespace App\Views\Pages;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdminAction extends FrontAction 
{
    // Set admin template
    public $template = 'admin';

    public function __construct()
    {        
        // Redirect if not logged in
        if(! user_id()) {
            $this->response->redirect('login');
        }
        
        // Setup sidebar menu structure
        $this->setupSidebar();
    }

    private function setupSidebar()
    {
        $this->data['sidebar'] = [
            'dashboard' => [
                'label' => 'Dashboard',
                'url' => 'admin',
                'permission' => 'dashboard.view'
            ],
            'users' => [
                'label' => 'Users',
                'url' => 'admin/users',
            ]
        ];
    }
}
