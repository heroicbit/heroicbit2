<?php namespace App\Pages\member;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Yllumi\Ci4Pages\Controllers\BasePageController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class PageController extends BasePageController 
{
	public $session;
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
		$this->data['version'] = 1.22;

		$this->session = service('session');
    }

	// This method handle GET request
	public function index()
	{
		$this->data['page_title'] = 'Beranda';

		// Get bottom menu
		// Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();
		if($db) {
			$bottommenu = $db->table('menus')
							->where('slug', 'tarbiyya-bottommenu')
							->where('status', 1)
							->get()
							->getRowArray();
			$this->data['bottommenu'] = Yaml::parse($bottommenu['schema']);
		}

		return pageView('member/index', $this->data);
	}

	public function get_logout()
	{
		$_SESSION = [];
	}

}