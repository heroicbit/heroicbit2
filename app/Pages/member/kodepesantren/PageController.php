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

                // Save kodepesantren to session
                $_SESSION['kodepesantren'] = $pesantren;
            }
        }

        return pageView('member/kodepesantren/index', $this->data);
    }

}