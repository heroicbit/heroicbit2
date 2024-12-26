<?php namespace App\Pages\member;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Heroic\Controllers\BasePageController;
use Psr\Log\LoggerInterface;

class PageController extends BasePageController 
{
	/**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

		$this->data['themeURL'] = service('settings')->get('Theme.frontendThemeURL'); 
		$this->data['themePath'] = service('settings')->get('Theme.frontendThemePath'); 
		$this->data['title'] = service('settings')->get('Site.siteName');
		$this->data['version'] = 21;
    }

	// This method handle GET request
	public function index()
	{
		$this->data['page_title'] = 'Beranda';

		return pageView('member/index', $this->data);
	}

}