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

	// Supply site setting and current user
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

		$userToken = $Tarbiyya->getUserToken();
		if($userToken) {
			$userQuery = $db->table('mein_users')
							->where('id', $userToken->user_id)
							->get()
							->getRowArray();
			$user = [
				'name' => $userQuery['name'],
				'email' => $userQuery['email'],
				'phone' => $userQuery['phone'],
				'avatar' => $userQuery['avatar'],
			];
		}

		return $this->respond(['tarbiyyaSetting' => $settingQuery, 'user' => $user ?? []]);
	}

	public function getSession()
	{
		dd($_SESSION);
	}

}