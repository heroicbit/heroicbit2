<?php namespace App\Pages\member;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PageController extends BaseController 
{
	use ResponseTrait;

	// This method handle GET request
	public function getIndex()
	{
		$this->data['page_title'] = 'Beranda';

		return pageView('member/index', $this->data);
	}

	// Supply site setting and current user
	public function getSettings($pesantrenID = null)
	{
		// Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = $Tarbiyya->initDBPesantren();

		$settingQuery = $db->table('mein_options')
							->whereIn('option_group', ['site','tarbiyya'])
							->get()
							->getResultArray();
		
		if($settingQuery)
		{
			$settingQuery = array_combine(array_column($settingQuery, 'option_name'), array_column($settingQuery, 'option_value'));
			unset($settingQuery['recaptcha_secret_key']);

			// Use Tarbiyya recaptcha if site not provide
			if(empty($settingQuery['recaptcha_site_key']))
				$settingQuery['recaptcha_site_key'] = config('App')->recaptcha_site_key; 
		}

		$userToken = $Tarbiyya->getUserToken();
		if($userToken) {
			$userQuery = $db->table('mein_users')
							->where('id', $userToken->user_id)
							->get()
							->getRowArray();
			$user = [
				'name' => $userQuery['name'] ?? '',
				'email' => $userQuery['email'] ?? '',
				'phone' => $userQuery['phone'] ?? '',
				'avatar' => $userQuery['avatar'] ?? '',
				'date_join' => $userQuery['created_at'] ?? '',
			];
		}

		return $this->respond(['tarbiyyaSetting' => $settingQuery, 'user' => $user ?? []]);
	}

	public function getSession()
	{
		dd($_SESSION);
	}

}