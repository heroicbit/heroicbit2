<?php namespace App\Views\Pages\member\kodepesantren;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function process()
    {
        $request = service('request');
        $kode = strtolower($request->getPost('kodepesantren'));

        $Pesantren = model('Pesantren');
        $found = $Pesantren->where('kode_pesantren', $kode)->first();
        if($found) {
            $Encrypter = service('encrypter');
            $pesantren = bin2hex($Encrypter->encrypt($found['database']));
            echo json_encode(['found' => 1, 'kode' => $kode, 'pesantrenID' => $pesantren]);
            die;
        } else {
            echo json_encode(['found' => 0]);
            die;
        }
    }
}