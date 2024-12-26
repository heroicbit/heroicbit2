<?php namespace App\Pages\member;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController {

	// This method handle GET request via AJAX
	public function index()
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

}