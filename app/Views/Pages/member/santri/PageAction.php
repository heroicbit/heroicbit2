<?php namespace App\Views\Pages\member\santri;

use App\Views\Pages\member\PageAction as MemberPageAction;

class PageAction extends MemberPageAction {

    public function supply()
    {
        // Handle another get method
        $request = service('request');

        $method = $request->getGet('m');
        if($method && in_array($method, get_class_methods($this))){
            return $this->$method($request);
        }

        $user = $this->checkToken();
        $db = $this->initDBPesantren();

        $santri = $db->query("SELECT su.*, s.*, c.id as class_id, c.class_name, 
            a.present as presensi_hadir, a.ill as presensi_sakit, a.permit as presensi_izin, a.noinfo as presensi_alpa
            FROM md_student_user su
            JOIN md_santri s ON s.id = su.student_id
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            LEFT JOIN md_attendance a ON a.student_id = s.id AND date = :date:
            where user_id = :user_id:
            AND s.status = 'student'", ['user_id' => $user->user_id, 'date' => date('Y-m-d')])->getResultArray();

        $settingLibur = $db->query("SELECT option_value FROM mein_options WHERE option_group = 'presensi' AND option_name = 'hari_libur'")->getRowArray();
        $libur = $settingLibur ? json_decode($settingLibur['option_value'], true) : ['5' => 'Jumat'];
        $isLibur = in_array(date('N'), array_keys($libur)) ? $libur[date('N')] : false;

        $output = compact('santri', 'libur', 'isLibur');
        return $output;
    }

    private function checkNIS($request)
    {
        $user = $this->checkToken();
        $db = $this->initDBPesantren();
        $nis = $request->getGet('nis');
        $found = $db->query("SELECT s.*, c.id as class_id, c.class_name
            FROM md_santri s
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            WHERE (nis = :nis: OR nisn = :nis:)
            AND s.status = 'student'", ['nis' => $nis])->getRow();

        if($found){
            $Encrypter = service('encrypter');
            $token = bin2hex($Encrypter->encrypt($found->id));

            return [
                'found' => 1,
                'token' => $token,
                'nama_santri' => $found->nama_santri,
                'class_name' => $found->class_name
            ];
        }

        return ['found' => 0, 'message' => 'NIS/NISN tidak ditemukan atau sudah tidak aktif'];
    }

    public function detailPresensi($request)
    {
        $user = $this->checkToken();
        $db = $this->initDBPesantren();
        $student_id = $request->getGet('student_id');

        $found = $db->query("SELECT * FROM `md_attendance` 
            WHERE `student_id` = :student_id: AND `present` IS NULL
            ORDER BY date DESC", 
            ['student_id' => $student_id])->getResultArray();

        if($found){
            $presensi = array_combine(array_column($found, 'date'), $found);
            return [
                'found' => count($found),
                'presensi' => $presensi
            ];
        }

        return ['found' => 0, 'message' => 'NIS/NISN tidak ditemukan atau sudah tidak aktif'];   

    }

    public function process()
    {
        $user = $this->checkToken();
        $db = $this->initDBPesantren();

        // Get postdata
        $request = service('request');
        $token = $request->getPost('token');
        $Encrypter = service('encrypter');
        $id = $Encrypter->decrypt(hex2bin($token));

        // Insert user_id and student_id to database
        $query = "INSERT INTO md_student_user (user_id, student_id) VALUES (:user_id:, :student_id:)";
        $inserted = $db->query($query, ['user_id' => $user->user_id, 'student_id' => $id]);
        if($inserted){
            // Get data santri
            $santri = $db->query("SELECT s.*, c.id as class_id, c.class_name
                FROM md_santri s
                JOIN md_student_class sc ON sc.student_id = s.id
                JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
                WHERE s.id = :id:", ['id' => $id])->getRow();

            echo json_encode(['status' => 'success', 'message' => 'Santri berhasil ditambahkan', 'santri' => $santri]);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Gagal menambahkan data santri.']);
        }
        die;
    }

}