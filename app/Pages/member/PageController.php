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

		return pageView('member/layout', $this->data);
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
				$settingQuery['recaptcha_site_key'] = config('App')->recaptcha['siteKey']; 
		}

		$userToken = $Tarbiyya->getUserToken();
		if($userToken) {
			$userQuery = $db->table('mein_users')
							->join('mein_roles', 'mein_roles.id = mein_users.role_id', 'left')
							->where('mein_users.id', $userToken->user_id)
							->get()
							->getRowArray();
			$user = [
				'name' => $userQuery['name'] ?? '',
				'email' => $userQuery['email'] ?? '',
				'phone' => $userQuery['phone'] ?? '',
				'role' => $userQuery['role_slug'] ?? '',
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

	/**
	 * Membangun klausa "IN (...)" untuk pencocokan pres_employee_schedules.day_of_week.
	 *
	 * Kode ini memakai konvensi ISO-8601 (date('N')): 1 = Senin ... 7 = Minggu.
	 * Namun data jadwal bisa tersimpan dengan konvensi JavaScript Date.getDay()
	 * (0 = Minggu ... 6 = Sabtu), terutama bila diinput lewat panel admin berbasis JS.
	 * Oleh karena itu pada hari Minggu nilai 7 dan 0 sama-sama dicocokkan.
	 *
	 * @param string|null $date Tanggal 'Y-m-d' (null = hari ini).
	 * @return array{in: string, params: array<string,int>}
	 */
	protected function dayOfWeekIn(?string $date = null): array
	{
		$ts   = $date ? strtotime($date) : time();
		$iso  = (int)date('N', $ts);
		$vals = $iso === 7 ? [7, 0] : [$iso];

		$placeholders = [];
		$params       = [];
		foreach ($vals as $i => $v) {
			$key = 'dow' . $i;
			$placeholders[] = ':' . $key . ':';  // nantinya diganti jadi ? oleh binder CI4
			$params[$key] = $v;
		}

		return [
			'in'     => implode(', ', $placeholders),
			'params' => $params,
		];
	}

}