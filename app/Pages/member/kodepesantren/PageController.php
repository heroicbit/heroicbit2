<?php namespace App\Pages\member\kodepesantren;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function get_ajax()
    {
        // check if servername is available in writable/custom_domain
        $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $kode = null;
        if(file_exists('../writable/custom_domain/'.$domain)){
            $kode = file_get_contents('../writable/custom_domain/'.$domain);
        }

        if($kode){
            $Pesantren = model('Pesantren');
            $found = $Pesantren->where('kode_pesantren', $kode)->first();
            if($found) {
                $Encrypter = service('encrypter');
                $pesantren = bin2hex($Encrypter->encrypt($found['database']));
                
                $this->data['found'] = 1;
                $this->data['kode'] = $kode;
                $this->data['pesantrenID'] = $pesantren;
            }
        }

        return pageView('member/kodepesantren/index', $this->data);
    }

    // This method handle GET request via AJAX
	public function supplyx()
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

		return ['tarbiyyaSetting' => $settingQuery];
	}
    
    public function process()
    {
        $kode = strtolower($this->request->getPost('kodepesantren'));

        $Pesantren = model('Pesantren');
        $found = $Pesantren->where('kode_pesantren', $kode)->first();
        if($found) {
            $Encrypter = service('encrypter');
            $pesantren = bin2hex($Encrypter->encrypt($found['database']));

            echo json_encode(['found' => 1, 'kode' => $kode, 'pesantrenID' => $pesantren, 'tarbiyyaSetting' => $settingQuery]);
            die;
        } else {
            echo json_encode(['found' => 0]);
            die;
        }
    }
}