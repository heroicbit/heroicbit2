<?php namespace App\Pages\member\kodepesantren;

use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController {

    public function index()
    {
        $Pesantren = model('Pesantren');
        $allPesantren = $Pesantren->select('nama_pesantren,kode_pesantren')
                                  ->where('status', 'active')
                                  ->orderBy('nama_pesantren', 'ASC')
                                  ->findAll();

        return $this->respond($allPesantren);
    }

    public function create()
    {
        $kode = strtolower($this->request->getPost('kodepesantren'));

        $Pesantren = model('Pesantren');
        $found = $Pesantren->where('kode_pesantren', $kode)->first();
        if($found) {
            $Encrypter = service('encrypter');
            $pesantren = bin2hex($Encrypter->encrypt($found['database']));

            // Save kodepesantren to session
            $_SESSION['kodepesantren'] = $pesantren;

            return $this->respond(['found' => 1, 'kode' => $kode, 'pesantrenID' => $pesantren]);
        } else {
            return $this->respond(['found' => 0]);
            die;
        }
    }
}