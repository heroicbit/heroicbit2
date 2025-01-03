<?php namespace App\Pages\member\kodepesantren;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

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

    public function process()
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

}