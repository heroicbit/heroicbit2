<?php namespace App\Pages\member\santri\detail;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/santri/detail/index', $this->data);
    }

    public function getSupply($id = null)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $santri = $db->query("SELECT s.*, c.id as class_id, c.class_name
            FROM md_santri s
            JOIN md_student_class sc ON sc.student_id = s.id
            JOIN md_class c ON c.id = sc.class_id AND year_id = (SELECT option_value FROM mein_options WHERE option_group = 'rombel' AND option_name = 'active_year')
            WHERE s.id = :id:
            AND s.status = 'student'", ['id' => $id])->getRow();

        if(!$santri){
            return $this->respond(['found' => 0, 'message' => 'Santri tidak ditemukan']);
        }

        return $this->respond([
            'found' => 1,
            'santri' => $santri
        ]);
    }

    public function getDetailPresensi($student_id)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        
        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $found = $db->query("SELECT * FROM `md_attendance` 
            WHERE `student_id` = :student_id: AND `present` IS NULL
            ORDER BY date DESC", 
            ['student_id' => $student_id])->getResultArray();

        if($found){
            $presensi = array_combine(array_column($found, 'date'), $found);
            return $this->respond([
                'found' => count($found),
                'presensi' => $presensi
            ]);
        }

        return $this->respond(['found' => 0, 'message' => 'Presensi tidak ditemukan']);
    }

    public function deleteIndex($id = null)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();

        // Get database pesantren
        $db = $Tarbiyya->initDBPesantren();

        $deleted = $db->query(
            "DELETE FROM md_student_user WHERE user_id = :user_id: AND student_id = :student_id:",
            ['user_id' => $user->user_id, 'student_id' => $id]
        );

        if ($deleted && $db->affectedRows() > 0) {
            return $this->respond(['status' => 'success', 'message' => 'Santri berhasil dihapus dari perwalian']);
        }

        return $this->respond(['status' => 'failed', 'message' => 'Gagal menghapus santri dari perwalian'], 400);
    }

    public function getNilaiPondok($student_id)
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();

        // DB penilaianpondok (sumber data nilai pondok)
        $pdb = $Tarbiyya->initDBPesantren();

        // Tahun ajaran & semester aktif DIAMBIL DARI penilaianpondok,
        // persis seperti cara nilai disimpan saat submit di penilaianpondok:
        //   tahun_ajaran_id = mein_options.active_year
        //   semester        = mein_options.active_semester
        $yearRow = $pdb->table('mein_options')->where('option_name', 'active_year')->get()->getRow();
        $activeYear = $yearRow->option_value ?? null;
        $semRow = $pdb->table('mein_options')->where('option_name', 'active_semester')->get()->getRow();
        $activeSemester = (int) ($semRow->option_value ?? 0);

        // Nama bulan & tahun ajaran untuk tampilan
        $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $tahunLabel = null;
        if ($activeYear) {
            $yRow = $pdb->table('md_year')->where('id', $activeYear)->get()->getRow();
            $tahunLabel = $yRow->year_label ?? null;
        }

        // 1) Semua nilai per bulan pada semester berjalan
        $bulanan = [];
        if ($activeYear && $activeSemester) {
            // Bulan-bulan yang sudah punya catatan nilai di semester ini
            $bulanList = $pdb->query("SELECT DISTINCT bulan FROM asrama_nilai_bulanan
                WHERE santri_id = :sid: AND tahun_ajaran_id = :y: AND semester = :sem:
                  AND deleted_at IS NULL
                ORDER BY bulan",
                ['sid' => $student_id, 'y' => $activeYear, 'sem' => $activeSemester])->getResultArray();

            foreach ($bulanList as $rowBulan) {
                $bulan = (int) $rowBulan['bulan'];
                $bulanan[] = [
                    'bulan'       => $bulan,
                    'bulan_label' => $namaBulan[$bulan] ?? $bulan,
                    // Nilai terbaru tiap komponen pada bulan tersebut
                    'nilai'       => $this->ambilNilaiBulan($pdb, $student_id, $activeYear, $activeSemester, $bulan),
                ];
            }
        }

        // 2) Nilai semester kemarin (record terakhir sebelum semester berjalan)
        $semesterKemarin = null;
        if ($activeYear) {
            $row = $pdb->query("
                SELECT * FROM asrama_nilai_semester
                WHERE santri_id = :sid:
                  AND (tahun_ajaran_id < :y: OR (tahun_ajaran_id = :y: AND semester < :sem:))
                  AND deleted_at IS NULL
                ORDER BY tahun_ajaran_id DESC, semester DESC
                LIMIT 1
            ", ['sid' => $student_id, 'y' => $activeYear, 'sem' => $activeSemester])->getRowArray();

            if ($row) {
                $semesterKemarin = [
                    'tahun_ajaran_id' => (int) $row['tahun_ajaran_id'],
                    'semester'        => (int) $row['semester'],
                    'keterangan'      => $row['keterangan'],
                    'nilai'           => $this->hitungNilaiPerKomponen($row['summary_nilai']),
                ];
            }
        }

        return $this->respond([
            'found' => 1,
            'semester' => [
                'semester'        => $activeSemester,
                'semester_label'  => $activeSemester == 1 ? 'Semester Ganjil' : 'Semester Genap',
                'tahun_ajaran_id' => $activeYear,
                'tahun_label'     => $tahunLabel,
            ],
            'bulanan'          => $bulanan,
            'semester_kemarin' => $semesterKemarin,
        ]);
    }

    /**
     * Ambil nilai terbaru per komponen untuk satu santri pada bulan tertentu.
     */
    private function ambilNilaiBulan($pdb, $santriId, $tahunAjaranId, $semester, $bulan)
    {
        $rows = $pdb->query("
            SELECT t.komponen_id, k.nama_komponen, t.nilai, t.keterangan
            FROM asrama_nilai_bulanan t
            JOIN asrama_nilai_komponen k ON k.id = t.komponen_id
            WHERE t.santri_id = :sid:
              AND t.tahun_ajaran_id = :y:
              AND t.semester = :sem:
              AND t.bulan = :bln:
              AND t.deleted_at IS NULL
            ORDER BY t.komponen_id, t.id DESC
        ", ['sid' => $santriId, 'y' => $tahunAjaranId, 'sem' => $semester, 'bln' => $bulan])
            ->getResultArray();

        $seen = [];
        $hasil = [];
        foreach ($rows as $r) {
            if (!isset($seen[$r['komponen_id']])) {
                $seen[$r['komponen_id']] = true;
                $hasil[] = [
                    'nama_komponen' => $r['nama_komponen'],
                    'nilai'         => $r['nilai'],
                    'keterangan'    => $r['keterangan'],
                ];
            }
        }

        return $hasil;
    }

    private function hitungNilaiPerKomponen($summaryJson)
    {
        $summary = json_decode($summaryJson, true);
        $logs = $summary['logs'] ?? [];

        // Kumpulkan nilai per komponen
        $counter = [];
        foreach ($logs as $log) {
            if (!is_array($log)) continue;
            foreach ($log as $komponenID => $huruf) {
                $counter[$komponenID][] = $huruf;
            }
        }

        // Nama komponen dari DB penilaianpondok
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $pdb = $Tarbiyya->initDBPesantren();
        $komponen = $pdb->query("SELECT id, nama_komponen FROM asrama_nilai_komponen WHERE deleted_at IS NULL")
            ->getResultArray();
        $namaMap = array_column($komponen, 'nama_komponen', 'id');

        // Rata-rata -> huruf (sama seperti NilaiModel di penilaianpondok)
        $hasil = [];
        foreach ($counter as $komponenID => $arr) {
            $angka = array_map(fn($n) => match ($n) {
                'A' => 4,
                'B' => 3,
                'C' => 2,
                'D' => 1,
                default => 0,
            }, $arr);

            $avg = count($angka) ? array_sum($angka) / count($angka) : 0;
            $huruf = $avg >= 3.5 ? 'A' : ($avg >= 2.5 ? 'B' : ($avg >= 1.5 ? 'C' : 'D'));

            $hasil[] = [
                'nama_komponen' => $namaMap[$komponenID] ?? ('Komponen ' . $komponenID),
                'nilai'         => $huruf,
            ];
        }

        return $hasil;
    }

}