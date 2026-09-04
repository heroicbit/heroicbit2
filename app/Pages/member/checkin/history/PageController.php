<?php namespace App\Pages\member\checkin\history;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        $this->data['page_title'] = 'Riwayat Presensi';
        return pageView('member/checkin/history/index', $this->data);
    }

    /**
     * GET: Daftar hari kerja presensi karyawan yang login dalam rentang tanggal,
     * lengkap dengan status per hari (hadir / hadir sebagian / tidak hadir).
     *
     * Status dihitung dari cakupan check-in terhadap jadwal unit unit karyawan
     * (sama seperti logika Rekap Kehadiran): 100% = hadir, 1-99% = parsial,
     * 0% = tidak hadir. Unit tanpa jadwal unit memakai fallback kehadiran biasa.
     * Hari libur & bukan hari kerja tidak ditampilkan sebagai baris (hanya dihitung
     * di summary) karena bukan "hari kerja".
     *
     * Query params:
     *   - range      : 'this_month' (default) | 'last_month' | 'custom'
     *   - start_date : Y-m-d (dipakai bila range=custom)
     *   - end_date   : Y-m-d (dipakai bila range=custom)
     */
    public function getSupply()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $employee = $db->query("
            SELECT id, user_name as name, unit_name as unit, unit_id, role, employee_code
            FROM view_employees
            WHERE user_id = :user_id:
            AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code'    => 404,
                'response_message' => 'Data karyawan tidak ditemukan.',
                'data'             => null
            ], 404);
        }

        [$start, $end] = $this->resolveRange();

        $unitId     = (int)($employee['unit_id'] ?? 0);
        $employeeId = (int)$employee['id'];
        $today      = date('Y-m-d');

        // Jangan menilai tanggal di masa depan (hari ini pun belum tentu selesai)
        if ($end > $today) {
            $end = $today;
        }

        // --- Ringkasan awal ---
        $summary = [
            'total_days'       => 0, // jumlah hari kalender dalam rentang (s.d. hari ini)
            'work_days'        => 0, // jumlah hari kerja yang dinilai
            'hadir'            => 0,
            'parsial'          => 0,
            'tidak_hadir'      => 0,
            'libur'            => 0,
            'bukan_hari_kerja' => 0,
            'percent'          => 0, // rata-rata cakupan check-in hari kerja
        ];

        $canEval = $start <= $end;

        // 1) Daftar jadwal unit milik unit karyawan = tolok ukur cakupan harian.
        //    Dipakai juga utk rincian "hadir/tidak hadir" per jadwal di modal detail.
        //    Unit tanpa jadwal unit -> fallback kehadiran biasa (1 record = hadir).
        $unitSchedules = [];
        if ($unitId > 0) {
            try {
                $rows = $db->query("
                    SELECT id, title, description, time_in, time_out, is_mandatory
                    FROM pres_unit_schedules
                    WHERE unit_id = :unit_id:
                    AND deleted_at IS NULL
                    ORDER BY (time_in IS NULL) ASC, time_in ASC, id ASC
                ", ['unit_id' => $unitId])->getResultArray();
                foreach ($rows as $r) {
                    $unitSchedules[] = [
                        'id'           => (int)$r['id'],
                        'title'        => $r['title'],
                        'description'  => $r['description'],
                        'time_in'      => $r['time_in'],
                        'time_out'     => $r['time_out'],
                        'is_mandatory' => (int)$r['is_mandatory'],
                    ];
                }
            } catch (\Throwable $e) {
                $unitSchedules = [];
            }
        }
        $totalSchedules = count($unitSchedules);

        // 2) Semua presensi karyawan dalam rentang (dipakai utk cakupan per hari)
        $attByDate = [];
        if ($canEval) {
            $attRows = $db->query("
                SELECT a.date, a.status, a.unit_schedule_id,
                       DATE_FORMAT(a.check_in_time, '%H:%i') as check_in_time,
                       DATE_FORMAT(a.check_out_time, '%H:%i') as check_out_time,
                       a.check_in_distance_meter
                FROM pres_attendances a
                WHERE a.employee_id = :employee_id:
                AND a.date BETWEEN :start: AND :end:
                ORDER BY a.date ASC, a.check_in_time ASC, a.id ASC
            ", [
                'employee_id' => $employeeId,
                'start'       => $start,
                'end'         => $end,
            ])->getResultArray();
            foreach ($attRows as $a) {
                $attByDate[$a['date']][] = $a;
            }
        }

        // 3) Hari libur dalam rentang (berlaku umum / khusus unit karyawan)
        $holidays = [];
        if ($canEval) {
            $holRows = $db->query("
                SELECT h.start_date, h.end_date
                FROM pres_holidays h
                LEFT JOIN pres_holiday_units hu ON hu.holiday_id = h.id
                WHERE (h.applies_to_all = 1 OR hu.unit_id = :unit_id:)
                AND h.start_date <= :end: AND h.end_date >= :start:
            ", [
                'unit_id' => $unitId,
                'start'   => $start,
                'end'     => $end,
            ])->getResultArray();
            foreach ($holRows as $h) {
                $d  = new \DateTime($h['start_date']);
                $de = new \DateTime($h['end_date']);
                while ($d <= $de) {
                    $holidays[$d->format('Y-m-d')] = true;
                    $d->modify('+1 day');
                }
            }
        }

        // 4) Hari kerja (day_of_week) per periode aktif yang tumpang tindih rentang.
        //    Disimpan per day_of_week beserta rentang tanggal periodenya supaya
        //    penentuan "hari kerja" per tanggal akurat lintas periode jadwal.
        //    Minggu bisa tersimpan 7 (ISO) atau 0 (JS getDay()) -> normalisasi ke 7.
        $schedRangesByDow = [];
        if ($canEval) {
            $schedRows = $db->query("
                SELECT es.day_of_week, sp.start_date, sp.end_date
                FROM pres_employee_schedules es
                JOIN pres_schedule_periods sp ON sp.id = es.period_id
                WHERE es.employee_id = :employee_id:
                AND sp.is_active = 1
                AND sp.start_date <= :end: AND sp.end_date >= :start:
            ", [
                'employee_id' => $employeeId,
                'start'       => $start,
                'end'         => $end,
            ])->getResultArray();
            foreach ($schedRows as $s) {
                $dow = (int)$s['day_of_week'];
                if ($dow === 0) {
                    $dow = 7; // 0 (JS) == Minggu == 7 (ISO)
                }
                if ($dow < 1 || $dow > 7) {
                    continue;
                }
                $schedRangesByDow[$dow][] = [$s['start_date'], $s['end_date']];
            }
        }

        // --- Susun daftar hari demi hari ---
        $days       = [];
        $sumPercent = 0;
        if ($canEval) {
            $d  = new \DateTime($start);
            $de = new \DateTime($end);

            while ($d <= $de) {
                $iso = $d->format('Y-m-d');
                $summary['total_days']++;
                $dow = (int)$d->format('N'); // 1=Senin .. 7=Minggu

                // Tanggal libur -> bukan hari kerja yang dinilai
                $isHoliday = isset($holidays[$iso]);

                // Apakah tanggal ini hari kerja? Ada periode aktif yang mencakup
                // tanggal tsb dan karyawan dijadwalkan pada day_of_week-nya.
                $isWorkDay = false;
                if (!$isHoliday && isset($schedRangesByDow[$dow])) {
                    foreach ($schedRangesByDow[$dow] as $rng) {
                        if ($iso >= $rng[0] && $iso <= $rng[1]) {
                            $isWorkDay = true;
                            break;
                        }
                    }
                }

                if ($isHoliday) {
                    $summary['libur']++;
                } elseif (!$isWorkDay) {
                    $summary['bukan_hari_kerja']++;
                } else {
                    $dayAtt = $attByDate[$iso] ?? [];

                    // Hari ini yang belum ada check-in belum bisa dinilai
                    if ($iso === $today && !$dayAtt) {
                        $d->modify('+1 day');
                        continue;
                    }

                    // Cakupan check-in terhadap jadwal unit hari tsb
                    $checked   = [];
                    $firstIn   = null;
                    $firstDist = null;
                    $lastOut   = null;
                    $isLate    = false;
                    foreach ($dayAtt as $a) {
                        $sid = $a['unit_schedule_id'] ?? null;
                        if ($sid !== null && $sid !== '') {
                            $checked[(int)$sid] = true;
                        }
                        if ($a['status'] === 'terlambat') {
                            $isLate = true;
                        }
                        if ($a['check_in_time'] !== null && $firstIn === null) {
                            $firstIn   = $a['check_in_time'];
                            $firstDist = $a['check_in_distance_meter'] !== null
                                ? (int)$a['check_in_distance_meter']
                                : null;
                        }
                        if ($a['check_out_time'] !== null
                            && ($lastOut === null || $a['check_out_time'] > $lastOut)) {
                            $lastOut = $a['check_out_time'];
                        }
                    }

                    if ($totalSchedules > 0) {
                        $cnt = count($checked);
                        $pct = (int)round($cnt / $totalSchedules * 100);
                        if ($pct > 100) {
                            $pct = 100;
                        }
                    } else {
                        // Unit tanpa jadwal unit: ada check-in (record apa pun) = hadir
                        $cnt = count($dayAtt);
                        $pct = $cnt > 0 ? 100 : 0;
                    }

                    if ($pct >= 100) {
                        $status = 'hadir';
                    } elseif ($cnt > 0) {
                        $status = 'parsial';
                    } else {
                        $status = 'tidak_hadir';
                    }

                    $summary['work_days']++;
                    $summary[$status]++;
                    $sumPercent += $pct;

                    // Rincian per jadwal unit: hadir / tidak hadir pada hari tsb.
                    // (Hanya dipakai saat unit punya jadwal unit; urutan sama dgn daftar
                    // jadwal unit sehingga modal detail bisa menampilkan mana yg hadir.)
                    $schedDetail = [];
                    foreach ($unitSchedules as $us) {
                        $found = null;
                        foreach ($dayAtt as $a) {
                            if ($a['unit_schedule_id'] !== null && $a['unit_schedule_id'] !== ''
                                && (int)$a['unit_schedule_id'] === (int)$us['id']) {
                                $found = $a;
                                break;
                            }
                        }
                        $schedDetail[] = [
                            'id'                      => (int)$us['id'],
                            'title'                   => $us['title'],
                            'time_in'                 => $us['time_in'],
                            'time_out'                => $us['time_out'],
                            'is_mandatory'            => $us['is_mandatory'],
                            'present'                 => $found !== null,
                            'status'                  => $found ? $found['status'] : null,
                            'check_in_time'           => $found ? $found['check_in_time'] : null,
                            'check_in_distance_meter' => $found && $found['check_in_distance_meter'] !== null
                                ? (int)$found['check_in_distance_meter']
                                : null,
                        ];
                    }

                    $days[] = [
                        'date'                    => $iso,
                        'status'                  => $status,
                        'percent'                 => $pct,
                        'check_in_count'          => $cnt,
                        'total_schedules'         => $totalSchedules,
                        'check_in_time'           => $firstIn,
                        'check_out_time'          => $lastOut,
                        'check_in_distance_meter' => $firstDist,
                        'is_late'                 => $isLate,
                        'schedules'               => $schedDetail,
                    ];
                }

                $d->modify('+1 day');
            }
        }

        if ($summary['work_days'] > 0) {
            $summary['percent'] = round($sumPercent / $summary['work_days'], 1);
        }

        // Tampilkan hari terbaru terlebih dahulu
        $days = array_reverse($days);

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => [
                'employee' => [
                    'id'   => $employeeId,
                    'name' => $employee['name'],
                    'unit' => $employee['unit'],
                    'role' => $employee['role'],
                ],
                'range'  => [
                    'start_date' => $start,
                    'end_date'   => $end,
                ],
                'summary' => $summary,
                'days'    => $days,
            ]
        ]);
    }

    /**
     * Menentukan rentang tanggal yang diminta dari query params.
     *
     * @return array{0: string, 1: string} [start_date, end_date] format Y-m-d
     */
    private function resolveRange(): array
    {
        $today = date('Y-m-d');
        $range = (string)($this->request->getGet('range') ?? 'this_month');

        if ($range === 'custom') {
            $startRaw = $this->request->getGet('start_date');
            $endRaw   = $this->request->getGet('end_date');

            $start = $this->validDate($startRaw) ? $startRaw : null;
            $end   = $this->validDate($endRaw) ? $endRaw : $today;

            // Bila tanggal mulai tidak diisi, default 1 bulan sebelum tanggal akhir
            if ($start === null) {
                $start = date('Y-m-d', strtotime('-1 month', strtotime($end)));
            }

            // Pastikan start <= end (swap bila terbalik)
            if ($start > $end) {
                [$start, $end] = [$end, $start];
            }

            // Batas wajar: maksimal ~1 tahun agar respons tidak membesar tak terkendali
            $limitStart = date('Y-m-d', strtotime('-366 days', strtotime($end)));
            if ($start < $limitStart) {
                $start = $limitStart;
            }

            return [$start, $end];
        }

        // "Bulan ini": awal bulan berjalan s.d. hari ini (tanggal masa depan
        // sudah dibatasi di getSupply). "Bulan kemarin": satu bulan kalender penuh
        // sebelum bulan berjalan.
        if ($range === 'last_month') {
            $start = date('Y-m-01', strtotime('first day of last month'));
            $end   = date('Y-m-t', strtotime('last day of last month'));
        } else { // 'this_month' (default)
            $start = date('Y-m-01');
            $end   = $today;
        }

        return [$start, $end];
    }

    /**
     * Memeriksa apakah string merupakan tanggal valid berformat Y-m-d.
     */
    private function validDate(?string $d): bool
    {
        if ($d === null || $d === '') {
            return false;
        }
        $dt = \DateTime::createFromFormat('Y-m-d', $d);
        return $dt !== false && $dt->format('Y-m-d') === $d;
    }
}
