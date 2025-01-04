<?php namespace App\Pages\member;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class PageController extends BaseController 
{
	use ResponseTrait;

	// This method handle GET request
	public function getIndex()
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
			
			return pageView('member/index', $this->data);
		} else {
			header('Location: /member/kodepesantren');
		}

	}

	// This method handle GET request via AJAX
	public function getSupply()
	{
		// Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

		$settingQuery = $db->table('mein_options')
							->whereIn('option_group', ['site','tarbiyya'])
							->get()
							->getResultArray();
		
		if($settingQuery)
			$settingQuery = array_combine(array_column($settingQuery, 'option_name'), array_column($settingQuery, 'option_value'));

		return $this->respond(['tarbiyyaSetting' => $settingQuery]);
	}

	public function getSession()
	{
		dd($_SESSION);
	}

}