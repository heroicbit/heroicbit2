<?php namespace App\Pages\member\kodepesantren;

use App\Pages\member\PageController as MemberPageController;
use CodeIgniter\API\ResponseTrait;

class PageController extends MemberPageController 
{
    use ResponseTrait;

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
            if($found) {
                $Encrypter = service('encrypter');
                $kodeHash = bin2hex($Encrypter->encrypt($found['database']));
                
                $this->data['found'] = 1;
                $this->data['kode'] = $kode;
                $this->data['pesantrenID'] = $kodeHash;

                // Save kodepesantren to session
                $_SESSION['pesantrenID'] = $kodeHash;
                setcookie('kodepesantren', $kodeHash);
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

            // Save kodepesantren to session
            $_SESSION['kodepesantren'] = $kode;
            $_SESSION['pesantrenID'] = $kodeHash;

            setcookie('kodepesantren', $kodeHash);
        }

        header('Location: /member/login');
    }

    public function getReset()
    {
        $_SESSION = [];
        setcookie("kodepesantren", "", time()-3600);

        header('Location: /member/kodepesantren');
    }

}