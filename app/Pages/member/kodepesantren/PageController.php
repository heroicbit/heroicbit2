<?php namespace App\Pages\member\kodepesantren;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;

class PageController extends MemberPageController 
{
    use ResponseTrait;

    // Load member layout
	public function getIndex()
	{
		$this->data['page_title'] = 'Kode Pesantren';

		return pageView('member/index', $this->data);
	}

    public function getContent()
    {
        // check if domain is available in writable/custom_domain
        $domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $kode = null;
        if(file_exists('../writable/custom_domain/'.$domain)){
            $kode = file_get_contents('../writable/custom_domain/'.$domain);
        }

        $Pesantren = model('Pesantren');
        if($kode) {
            $found = $Pesantren->where('kode_pesantren', $kode)->first();

            // If found, berarti ini site khusus bukan dari aplikasi umum   
            if($found) {
                $Encrypter = service('encrypter');
                $kodeHash = bin2hex($Encrypter->encrypt($found['database']));
                
                $this->data['found'] = 1;
                $this->data['kode'] = $kode;

                // Untuk dipasang di view yang kemudian di-assign ke localstorage
                $this->data['pesantrenID'] = $kodeHash;
            }
        }

        return pageView('member/kodepesantren/index', $this->data);
    }

    // Supply json data of all pesantren
    public function getSupply()
    {
        $Pesantren = model('Pesantren');
        $allPesantren = $Pesantren->select('nama_pesantren,kode_pesantren')
                                  ->where('status', 'active')
                                  ->orderBy('nama_pesantren', 'ASC')
                                  ->findAll();

        return $this->respond($allPesantren);
    }

    // Handle postdata from form choose kodepesantren
    public function postIndex()
    {
        $kode = strtolower($this->request->getPost('kodepesantren'));

        $Pesantren = model('Pesantren');
        $found = $Pesantren->where('kode_pesantren', $kode)->first();
        if($found) {
            $Encrypter = service('encrypter');
            $kodeHash = bin2hex($Encrypter->encrypt($found['database']));

            return $this->respond([
                'found' => 1,
                'pesantrenID' => $kodeHash
            ]);
        }

        return $this->respond(['found' => 0, 'message' => 'Kode Pesantren tidak ditemukan']);
    }

    // Set pesantrenID to session
    public function getSetPesantrenID($pesantrenID)
    {
        $_SESSION['pesantrenID'] = $pesantrenID;

        header('Location: /member/login');
    }

}