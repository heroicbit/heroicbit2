<?php namespace App\Pages\_components\common;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PageController extends BaseController 
{
	use ResponseTrait;

	// Supply site setting and current user
	public function getSettings($pesantrenID = null)
	{
		// Get database pesantren
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $db = \Config\Database::connect();

		$settingQuery = $db->table('mein_options')
							->whereIn('option_group', ['site','masagi'])
							->get()
							->getResultArray();
		
		if($settingQuery)
		{
			$settingQuery = array_combine(array_column($settingQuery, 'option_name'), array_column($settingQuery, 'option_value'));
			unset($settingQuery['recaptcha_secret_key']);
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

		return $this->respond(['settings' => $settingQuery, 'user' => $user ?? []]);
	}

}