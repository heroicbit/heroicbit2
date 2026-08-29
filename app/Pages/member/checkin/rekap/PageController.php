<?php namespace App\Pages\member\checkin\rekap;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        $this->data['page_title'] = 'Rekap Kehadiran';
        return pageView('member/checkin/rekap/index', $this->data);
    }

    /**
     * GET: Rekap kehadiran harian seluruh karyawan
     */
    public function getSupply()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        // Hanya super/admin yang bisa akses
        $admin = $db->query("
            SELECT role FROM view_employees
            WHERE user_id = :user_id: AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$admin || !in_array($admin['role'], ['super', 'admin'])) {
            return $this->respond([
                'response_code'    => 403,
                'response_message' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.',
                'data'             => null
            ], 403);
        }

        $date = $this->request->getGet('date') ?? date('Y-m-d');

        // Cek apakah tanggal tersebut hari libur
        $holiday = $db->query("
            SELECT h.id, h.name
            FROM pres_holidays h
            WHERE :date: BETWEEN h.start_date AND h.end_date
            AND h.applies_to_all = 1
            LIMIT 1
        ", ['date' => $date])->getRowArray();

        // Ambil semua karyawan aktif (termasuk unit_id untuk jadwal unit)
        $employees = $db->query("
            SELECT ve.id, ve.user_name as name, ve.unit_name as unit, ve.unit_id, ve.employee_code
            FROM view_employees ve
            WHERE ve.is_active = 1
            ORDER BY ve.unit_name, ve.user_name
        ")->getResultArray();

        // Ambil jadwal unit per unit (untuk menghitung cakupan check-in)
        $unitSchedulesByUnit = [];
        try {
            $unitRows = $db->query("
                SELECT id, unit_id FROM pres_unit_schedules
                WHERE deleted_at IS NULL
            ")->getResultArray();
            foreach ($unitRows as $r) {
                $unitSchedulesByUnit[(int)$r['unit_id']][] = (int)$r['id'];
            }
        } catch (\Throwable $e) {
            $unitSchedulesByUnit = [];
        }

        // Ambil presensi untuk tanggal tersebut (semua record, termasuk unit_schedule_id)
        $attendances = $db->query("
            SELECT a.employee_id, a.status, a.unit_schedule_id,
                   DATE_FORMAT(a.check_in_time, '%H:%i') as check_in_time,
                   a.check_in_distance_meter
            FROM pres_attendances a
            WHERE a.date = :date:
            ORDER BY a.check_in_time ASC
        ", ['date' => $date])->getResultArray();

        // Group by employee_id (bisa lebih dari satu check-in / jadwal)
        $attMap = [];
        foreach ($attendances as $att) {
            $attMap[$att['employee_id']][] = $att;
        }

        // Ambil jadwal untuk hari tersebut (untuk menentukan bukan_hari_kerja)
        // Minggu kadang tersimpan sebagai 0 (getDay JS) alih-alih 7 (ISO), jadi cocokkan keduanya.
        $dowIn     = $this->dayOfWeekIn($date);
        $schedules = $db->query("
            SELECT es.employee_id
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE es.day_of_week IN ({$dowIn['in']})
            AND sp.is_active = 1
            AND :date: BETWEEN sp.start_date AND sp.end_date
        ", array_merge(['date' => $date], $dowIn['params']))->getResultArray();

        $scheduledIds = array_column($schedules, 'employee_id');

        // Hitung status per karyawan berdasarkan cakupan check-in terhadap jadwal unit:
        // 100% = hadir, 1-99% = parsial, 0% = tidak hadir.
        $counts = ['hadir' => 0, 'parsial' => 0, 'tidak_hadir' => 0, 'libur' => 0, 'bukan_hari_kerja' => 0];
        $result = [];

        foreach ($employees as $emp) {
            $eid        = (int)$emp['id'];
            $unitId     = (int)($emp['unit_id'] ?? 0);
            $empAtt     = $attMap[$eid] ?? [];
            $status     = null;
            $checkInTime = null;
            $checkInDist = null;
            $percent    = 0;
            $checkInCount = 0;
            $totalSchedules = 0;

            if ($empAtt) {
                $last = $empAtt[count($empAtt) - 1];
                $checkInTime = $last['check_in_time'];
                $checkInDist = $last['check_in_distance_meter'];
            }

            if ($holiday) {
                $status = 'libur';
            } elseif (!in_array($eid, $scheduledIds)) {
                $status = 'bukan_hari_kerja';
            } else {
                // Cakupan check-in vs total jadwal unit unit-nya
                $unitScheduleIds = $unitSchedulesByUnit[$unitId] ?? [];
                $totalSchedules  = count($unitScheduleIds);

                $checked = [];
                foreach ($empAtt as $att) {
                    $sid = $att['unit_schedule_id'] ?? null;
                    if ($sid !== null && $sid !== '') {
                        $checked[(int)$sid] = true;
                    }
                }
                $checkInCount = count($checked);

                if ($totalSchedules > 0) {
                    $percent = (int)round($checkInCount / $totalSchedules * 100);
                    if ($percent > 100) {
                        $percent = 100;
                    }
                    if ($percent >= 100) {
                        $status = 'hadir';
                    } elseif ($checkInCount > 0) {
                        $status = 'parsial';
                    } else {
                        $status = 'tidak_hadir';
                    }
                } else {
                    // Unit tanpa jadwal unit -> fallback ke kehadiran biasa:
                    // ada record check-in (tanpa unit_schedule_id) berarti hadir.
                    $checkInCount = count($empAtt);
                    $percent = $checkInCount > 0 ? 100 : 0;
                    $status  = $checkInCount > 0 ? 'hadir' : 'tidak_hadir';
                }
            }

            $counts[$status] = ($counts[$status] ?? 0) + 1;

            $result[] = [
                'id'                      => $eid,
                'name'                    => $emp['name'],
                'unit'                    => $emp['unit'],
                'employee_code'           => $emp['employee_code'],
                'status'                  => $status,
                'percent'                 => $percent,
                'check_in_count'          => $checkInCount,
                'total_schedules'         => $totalSchedules,
                'check_in_time'           => $checkInTime,
                'check_in_distance_meter' => $checkInDist,
            ];
        }

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => [
                'date'         => $date,
                'is_holiday'   => $holiday !== null,
                'holiday_name' => $holiday ? $holiday['name'] : null,
                'counts'       => $counts,
                'employees'    => $result,
            ]
        ]);
    }

    /**
     * GET: Detail presensi seorang karyawan
     */
    public function getDetail($employeeId = null)
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        if (!$employeeId) {
            return $this->respond([
                'response_code' => 400,
                'response_message' => 'ID karyawan diperlukan.'
            ], 400);
        }

        $periodId = (int)($this->request->getGet('period_id') ?? 0);
        $year     = (int)($this->request->getGet('year') ?? date('Y'));
        $month    = (int)($this->request->getGet('month') ?? date('m'));

        // Data karyawan (termasuk unit_id untuk jadwal unit)
        $employee = $db->query("
            SELECT id, user_name as name, unit_name as unit, unit_id, position, employee_code
            FROM view_employees
            WHERE id = :id: AND is_active = 1
        ", ['id' => $employeeId])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code' => 404,
                'response_message' => 'Karyawan tidak ditemukan.'
            ], 404);
        }

        // Periode
        if ($periodId) {
            $period = $db->query("
                SELECT id, name FROM pres_schedule_periods WHERE id = :id:
            ", ['id' => $periodId])->getRowArray();
        } else {
            $period = $db->query("
                SELECT id, name FROM pres_schedule_periods
                WHERE is_active = 1 AND CURDATE() BETWEEN start_date AND end_date
                LIMIT 1
            ")->getRowArray();
            $periodId = $period ? $period['id'] : null;
        }

        // Statistik presensi dalam periode + data kalender.
        // Kehadiran dihitung dari cakupan check-in terhadap jadwal unit:
        // 100% = hadir (hijau), 0% = tidak hadir (merah), sebagian = parsial (kuning).
        $stats = ['hadir' => 0, 'parsial' => 0, 'tidak_hadir' => 0, 'libur' => 0, 'bukan_hari_kerja' => 0];
        $calendarData = [];
        $percent = 0;
        $unitId = (int)($employee['unit_id'] ?? 0);

        if ($periodId && $period) {
            $startDate = "$year-$month-01";
            $endDate   = date('Y-m-t', strtotime($startDate));

            // Jadwal unit milik unit karyawan (total jadwal yang harus di-check-in)
            $unitScheduleIds = [];
            try {
                $unitRows = $db->query("
                    SELECT id FROM pres_unit_schedules
                    WHERE unit_id = :unit_id: AND deleted_at IS NULL
                    ORDER BY (time_in IS NULL) ASC, time_in ASC, id ASC
                ", ['unit_id' => $unitId])->getResultArray();
                foreach ($unitRows as $r) {
                    $unitScheduleIds[] = (int)$r['id'];
                }
            } catch (\Throwable $e) {
                $unitScheduleIds = [];
            }
            $totalSchedules = count($unitScheduleIds);

            // Presensi bulan tsb (termasuk unit_schedule_id yang di-check-in)
            $attRows = $db->query("
                SELECT a.date, a.unit_schedule_id
                FROM pres_attendances a
                WHERE a.employee_id = :eid:
                AND a.date BETWEEN :start: AND :end:
                ORDER BY a.date
            ", ['eid' => $employeeId, 'start' => $startDate, 'end' => $endDate])->getResultArray();

            $attByDate = [];
            foreach ($attRows as $a) {
                $attByDate[$a['date']][] = $a;
            }

            // Hari libur bulan tsb (untuk unit karyawan)
            $holidays = [];
            $holRows = $db->query("
                SELECT h.start_date, h.end_date
                FROM pres_holidays h
                LEFT JOIN pres_holiday_units hu ON hu.holiday_id = h.id
                WHERE (h.applies_to_all = 1 OR hu.unit_id = :unit_id:)
                AND h.start_date <= :end: AND h.end_date >= :start:
            ", ['unit_id' => $unitId, 'start' => $startDate, 'end' => $endDate])->getResultArray();

            foreach ($holRows as $h) {
                $d  = new \DateTime($h['start_date']);
                $de = new \DateTime($h['end_date']);
                while ($d <= $de) {
                    $holidays[$d->format('Y-m-d')] = true;
                    $d->modify('+1 day');
                }
            }

            // Hari kerja karyawan (day_of_week) dalam periode aktif
            $workDow = [];
            $schedRows = $db->query("
                SELECT es.day_of_week
                FROM pres_employee_schedules es
                JOIN pres_schedule_periods sp ON sp.id = es.period_id
                WHERE es.employee_id = :eid: AND sp.is_active = 1
                AND :start: <= sp.end_date AND sp.start_date <= :end:
            ", ['eid' => $employeeId, 'start' => $startDate, 'end' => $endDate])->getResultArray();

            foreach ($schedRows as $s) {
                $dow = (int)$s['day_of_week'];
                $workDow[$dow] = true;
                // Minggu bisa tersimpan sebagai 7 (ISO date('N')) atau 0 (JS getDay());
                // set keduanya supaya Minggu tetap dianggap hari kerja.
                if ($dow === 7 || $dow === 0) {
                    $workDow[0] = true;
                    $workDow[7] = true;
                }
            }

            // Susun kalender untuk seluruh hari bulan tsb (hanya sampai hari ini)
            $today = date('Y-m-d');
            $d  = new \DateTime($startDate);
            $de = new \DateTime($endDate);
            while ($d <= $de) {
                $iso = $d->format('Y-m-d');

                if ($iso <= $today) {
                    $dow = (int)$d->format('N'); // 1=Senin .. 7=Minggu

                    if (isset($holidays[$iso])) {
                        $status = 'libur';
                        $cnt = 0;
                        $pct = 0;
                    } elseif (!isset($workDow[$dow])) {
                        $status = 'bukan_hari_kerja';
                        $cnt = 0;
                        $pct = 0;
                    } else {
                        // Cakupan check-in vs total jadwal unit
                        $dayAtt = $attByDate[$iso] ?? [];

                        if ($totalSchedules > 0) {
                            $checked = [];
                            foreach ($dayAtt as $a) {
                                $sid = $a['unit_schedule_id'] ?? null;
                                if ($sid !== null && $sid !== '') {
                                    $checked[(int)$sid] = true;
                                }
                            }
                            $cnt = count($checked);
                            $pct = (int)round($cnt / $totalSchedules * 100);
                            if ($pct > 100) {
                                $pct = 100;
                            }
                        } else {
                            // Unit tanpa jadwal unit: ada record check-in berarti hadir
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
                    }

                    $stats[$status]++;
                    $calendarData[] = [
                        'date'            => $iso,
                        'status'          => $status,
                        'percent'         => $pct,
                        'check_in_count'  => $cnt,
                        'total_schedules' => $totalSchedules,
                    ];
                }

                $d->modify('+1 day');
            }

            // Persentase kehadiran periode = rata-rata cakupan hari kerja
            // (hadir + parsial + tidak hadir; libur/bukan_hari_kerja tidak dihitung)
            $workDays = $stats['hadir'] + $stats['parsial'] + $stats['tidak_hadir'];
            if ($workDays > 0) {
                $sumPct = 0;
                foreach ($calendarData as $c) {
                    if ($c['status'] === 'hadir' || $c['status'] === 'parsial' || $c['status'] === 'tidak_hadir') {
                        $sumPct += $c['percent'];
                    }
                }
                $percent = round($sumPct / $workDays, 1);
            }
        }

        // Teks jadwal
        $scheduleText = '';
        $schedRows = $db->query("
            SELECT es.day_of_week, es.expected_time_in
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE es.employee_id = :eid: AND sp.is_active = 1
            AND CURDATE() BETWEEN sp.start_date AND sp.end_date
            ORDER BY es.day_of_week
        ", ['eid' => $employeeId])->getResultArray();

        if ($schedRows) {
            $dayNames = [0 => 'Min', 1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];
            $parts = [];
            foreach ($schedRows as $s) {
                $d = $dayNames[$s['day_of_week']] ?? '?';
                $parts[] = $s['expected_time_in'] ? "$d {$s['expected_time_in']}" : $d;
            }
            $scheduleText = implode(', ', $parts);
        }

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => [
                'employee' => [
                    'id'            => $employee['id'],
                    'name'          => $employee['name'],
                    'unit'          => $employee['unit'],
                    'position'      => $employee['position'],
                    'schedule_text' => $scheduleText,
                ],
                'period'   => $period ? ['id' => $period['id'], 'name' => $period['name']] : null,
                'stats'    => [
                    'percent' => $percent,
                    'hadir'   => $stats['hadir'],
                    'parsial' => $stats['parsial'],
                    'alpa'    => $stats['tidak_hadir'],
                    'libur'   => $stats['libur'] + $stats['bukan_hari_kerja'],
                ],
                'calendar' => $calendarData,
            ]
        ]);
    }

    /**
     * GET: Daftar periode jadwal
     */
    public function getPeriods()
    {
        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $periods = $db->query("
            SELECT id, name, start_date, end_date, is_active
            FROM pres_schedule_periods
            ORDER BY start_date DESC
        ")->getResultArray();

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => $periods
        ]);
    }

    /**
     * GET: Unduh rekap presensi bulanan seluruh karyawan (format Excel .xls)
     *
     * Kolom: Nama, Email, tanggal 1..N (jumlah kolom sesuai bulan yang dipilih),
     * Total Checkin, Total Absen.
     * Nama file: rekap-checkin-[bulan]-[tahun].xls
     */
    public function getExport()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        // Hanya super/admin yang bisa akses
        $admin = $db->query("
            SELECT role FROM view_employees
            WHERE user_id = :user_id: AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$admin || !in_array($admin['role'], ['super', 'admin'])) {
            return $this->respond([
                'response_code'    => 403,
                'response_message' => 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.',
                'data'             => null
            ], 403);
        }

        $month = (int)($this->request->getGet('month') ?? date('m'));
        $year  = (int)($this->request->getGet('year') ?? date('Y'));
        if ($month < 1) $month = 1;
        if ($month > 12) $month = 12;

        $startDate   = sprintf('%04d-%02d-01', $year, $month);
        $endDate     = date('Y-m-t', strtotime($startDate));
        $daysInMonth = (int)date('t', strtotime($startDate));
        $today       = date('Y-m-d');

        // Nama bulan Indonesia (untuk nama file)
        $monthNames = [
            1 => 'januari', 2 => 'februari', 3 => 'maret', 4 => 'april', 5 => 'mei', 6 => 'juni',
            7 => 'juli', 8 => 'agustus', 9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember',
        ];
        $monthName = $monthNames[$month] ?? 'bulan';
        $filename  = "rekap-checkin-{$monthName}-{$year}.xls";
        // Label periode untuk judul laporan (mengikuti pilihan bulan/tahun user)
        $periodLabel = ucfirst($monthName) . ' ' . $year;

        // Semua karyawan aktif beserta email & unit
        $employees = $db->query("
            SELECT ve.id, ve.user_name as name, ve.user_email as email, ve.unit_name as unit,
                   ve.unit_id, ve.employee_code
            FROM view_employees ve
            WHERE ve.is_active = 1
            ORDER BY ve.unit_name, ve.user_name
        ")->getResultArray();

        // Semua presensi dalam bulan tsb (termasuk unit_schedule_id utk cakupan check-in)
        $attendances = $db->query("
            SELECT a.employee_id, a.date, a.unit_schedule_id
            FROM pres_attendances a
            WHERE a.date BETWEEN :start: AND :end:
            ORDER BY a.employee_id, a.date
        ", ['start' => $startDate, 'end' => $endDate])->getResultArray();

        // Group per karyawan+tanggal: set unit_schedule_id yang sudah di-check-in,
        // plus penanda record lama yang tidak punya unit_schedule_id.
        $attByEmployee = [];
        foreach ($attendances as $att) {
            $eid  = $att['employee_id'];
            $date = $att['date'];
            $sid  = $att['unit_schedule_id'] ?? null;
            if ($sid !== null && $sid !== '') {
                $attByEmployee[$eid][$date]['schedules'][(int)$sid] = true;
            } else {
                $attByEmployee[$eid][$date]['plain'] = true;
            }
        }

        // Hari libur bulan tsb (berlaku umum atau khusus unit)
        $holidaysAll  = [];
        $holidaysUnit = [];
        $holRows = $db->query("
            SELECT h.start_date, h.end_date, h.applies_to_all, hu.unit_id
            FROM pres_holidays h
            LEFT JOIN pres_holiday_units hu ON hu.holiday_id = h.id
            WHERE h.start_date <= :end: AND h.end_date >= :start:
        ", ['start' => $startDate, 'end' => $endDate])->getResultArray();

        foreach ($holRows as $h) {
            $d  = new \DateTime($h['start_date']);
            $de = new \DateTime($h['end_date']);
            while ($d <= $de) {
                $iso = $d->format('Y-m-d');
                if ((int)$h['applies_to_all'] === 1) {
                    $holidaysAll[$iso] = true;
                }
                $uid = (int)($h['unit_id'] ?? 0);
                if ($uid > 0) {
                    $holidaysUnit[$uid][$iso] = true;
                }
                $d->modify('+1 day');
            }
        }

        // Hari kerja per karyawan (day_of_week) pada periode aktif yang tumpang tindih bulan tsb
        $schedRows = $db->query("
            SELECT es.employee_id, es.day_of_week
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE sp.is_active = 1
            AND :start: <= sp.end_date AND sp.start_date <= :end:
        ", ['start' => $startDate, 'end' => $endDate])->getResultArray();

        $workDowByEmployee = [];
        foreach ($schedRows as $s) {
            $eid = (int)$s['employee_id'];
            $dow = (int)$s['day_of_week'];
            $workDowByEmployee[$eid][$dow] = true;
            // Minggu bisa tersimpan sebagai 7 (ISO date('N')) atau 0 (JS getDay());
            // set keduanya supaya Minggu tetap dianggap hari kerja.
            if ($dow === 7 || $dow === 0) {
                $workDowByEmployee[$eid][0] = true;
                $workDowByEmployee[$eid][7] = true;
            }
        }

        // Bangun baris data
        $rows = [];
        foreach ($employees as $emp) {
            $eid     = (int)$emp['id'];
            $unitId  = (int)($emp['unit_id'] ?? 0);
            $attSet  = $attByEmployee[$eid] ?? [];
            $workDow = $workDowByEmployee[$eid] ?? null;

            // Total jadwal unit milik unit karyawan (untuk cakupan check-in per hari)
            $unitScheduleIds = [];
            try {
                $unitRows = $db->query("
                    SELECT id FROM pres_unit_schedules
                    WHERE unit_id = :unit_id: AND deleted_at IS NULL
                ", ['unit_id' => $unitId])->getResultArray();
                foreach ($unitRows as $r) {
                    $unitScheduleIds[] = (int)$r['id'];
                }
            } catch (\Throwable $e) {
                $unitScheduleIds = [];
            }
            $totalSchedules = count($unitScheduleIds);

            $dayCells     = [];
            $totalCheckin = 0;
            $totalAbsen   = 0;

            $d = new \DateTime($startDate);
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $iso = $d->format('Y-m-d');
                $dow = (int)$d->format('N');

                $isHoliday = isset($holidaysAll[$iso])
                    || (isset($holidaysUnit[$unitId]) && isset($holidaysUnit[$unitId][$iso]));

                $dayAtt = $attSet[$iso] ?? null;
                if ($dayAtt !== null) {
                    if ($totalSchedules > 0) {
                        // Cakupan: berapa jadwal unit yang sudah di-check-in hari itu
                        $checkedCount = isset($dayAtt['schedules']) ? count($dayAtt['schedules']) : 0;
                        $hasAnyCheck  = ($checkedCount > 0) || !empty($dayAtt['plain']);
                        if ($checkedCount >= $totalSchedules) {
                            // Semua check-in jadwal unit terisi -> hadir penuh
                            $dayCells[$day] = '✓';
                            $totalCheckin++;
                        } elseif ($hasAnyCheck) {
                            // Belum semua check-in terisi -> parsial, tampilkan "terisi/total"
                            $dayCells[$day] = $checkedCount . '/' . $totalSchedules;
                        } else {
                            $dayCells[$day] = '';
                        }
                    } else {
                        // Unit tanpa jadwal: ada check-in berarti hadir
                        $dayCells[$day] = '✓';
                        $totalCheckin++;
                    }
                } elseif ($iso > $today) {
                    // Hari mendatang: belum bisa dinilai, tidak dihitung
                    $dayCells[$day] = '';
                } elseif ($isHoliday) {
                    // Libur: tidak dihitung absen
                    $dayCells[$day] = '-';
                } else {
                    // Bukan hari kerja karyawan -> tidak dihitung absen.
                    // Jika tidak ada jadwal aktif sama sekali, semua hari dianggap
                    // bukan hari kerja (konsisten dengan halaman Rekap Kehadiran).
                    $isWorkingDay = ($workDow === null) ? false : isset($workDow[$dow]);
                    if (!$isWorkingDay) {
                        $dayCells[$day] = '-';
                    } elseif ($iso == $today) {
                        // Hari ini: belum bisa dinilai sampai selesai,
                        // kosongkan saja, jangan tandai 'x' dan jangan dihitung absen.
                        $dayCells[$day] = '';
                    } else {
                        // Alpa / tidak hadir: hari kerja tanpa check-in -> tandai 'x'
                        $dayCells[$day] = 'x';
                        $totalAbsen++;
                    }
                }

                $d->modify('+1 day');
            }

            $rows[] = [
                'name'          => $emp['name'],
                'email'         => $emp['email'] ?? '',
                'unit'          => $emp['unit'],
                'days'          => $dayCells,
                'total_checkin' => $totalCheckin,
                'total_absen'   => $totalAbsen,
            ];
        }

        // --- Bangun dokumen Excel dengan format XML Spreadsheet 2003 ---
        // Project tidak memakai library spreadsheet (PhpSpreadsheet dkk.), sehingga file .xls
        // dibuat dengan format XML Spreadsheet 2003 (urn:schemas-microsoft-com:office:spreadsheet)
        // — format terstruktur yang dibaca penuh oleh Microsoft Excel maupun LibreOffice Calc.
        // Mendukung lebar kolom, tinggi baris, font, fill, border, alignment, freeze panes,
        // AutoFilter, dan page setup (landscape, fit-to-width, margin). Semua formatting di-set
        // di sini saat file dibuat, bukan mengandalkan AutoFit dari aplikasi spreadsheet.
        $totalCols = 3 + $daysInMonth + 2; // Nama + Unit + Email + kolom tanggal + 2 kolom total
        // Header bertingkat: baris 1 kosong, baris 2-3 = header, data mulai baris 4
        $headerStartRow = 2;                 // baris pertama blok header (tempat AutoFilter)
        $dataStartRow   = 4;                 // baris data pertama
        $lastRow        = $dataStartRow - 1 + count($rows); // baris data terakhir

        // Border dalam tipis; border bawah header lebih tebal sebagai pembatas.
        $thinBorders = '<Borders>'
            . '<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '</Borders>';
        $headerBorders = '<Borders>'
            . '<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2"/>'
            . '<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>'
            . '</Borders>';

        // Koleksi style: header (bold, fill biru tua, tengah), teks (kiri),
        // tengah, '✓' (hijau tebal), '-' (abu-abu). Semua border lengkap.
        $styles  = '<Styles>';
        $styles .= '<Style ss:ID="Default" ss:Name="Normal">'
            . '<Alignment ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        $styles .= '<Style ss:ID="sHeader">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $headerBorders
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#000000"/>'
            . '<Interior ss:Color="#ffffff" ss:Pattern="Solid"/></Style>';
        $styles .= '<Style ss:ID="sText">'
            . '<Alignment ss:Horizontal="Left" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        $styles .= '<Style ss:ID="sCenter">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        $styles .= '<Style ss:ID="sCheck">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#1E7D32"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        $styles .= '<Style ss:ID="sDash">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Color="#000000"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        // Gaya alpa: tidak hadir pada hari kerja (merah tebal)
        $styles .= '<Style ss:ID="sX">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#B71C1C"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        // Gaya parsial: sebagian check-in jadwal unit belum terisi (kuning/brass)
        $styles .= '<Style ss:ID="sPartial">'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . $thinBorders
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#C79A3E"/>'
            . '<Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>';
        // Gaya judul laporan: utama (bold 14, kiri) & periode (11, soft, kiri).
        // Tanpa border/fill agar terpisah dari area tabel.
        $styles .= '<Style ss:ID="sTitle">'
            . '<Alignment ss:Horizontal="Left" ss:Vertical="Center"/>'
            . '<Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#080808"/>'
            . '</Style>';
        $styles .= '<Style ss:ID="sSubtitle">'
            . '<Alignment ss:Horizontal="Left" ss:Vertical="Center"/>'
            . '<Font ss:FontName="Calibri" ss:Size="11" ss:Color="#5A6B75"/>'
            . '</Style>';
        $styles .= '</Styles>';

        // Pembuat cell
        $cell = function (string $styleId, string $type, string $value): string {
            return '<Cell ss:StyleID="' . $styleId . '"><Data ss:Type="' . $type . '">'
                . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
        };

        // Pembuat cell header dengan atribut tambahan (untuk merge vertikal/horizontal)
        $headerCell = function (string $content, string $extra = ''): string {
            return '<Cell ss:StyleID="sHeader"' . $extra . '><Data ss:Type="String">'
                . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . '</Data></Cell>';
        };

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:o="urn:schemas-microsoft-com:office:office"'
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel"'
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:html="http://www.w3.org/TR/REC-html40">';
        $xml .= $styles;
        $xml .= '<Worksheet ss:Name="Rekap Checkin">';

        // NamedRange _FilterDatabase agar AutoFilter dikenali oleh Excel
        $xml .= '<Names><NamedRange ss:Name="_FilterDatabase"'
            . ' ss:RefersTo="=\'Rekap Checkin\'!R' . $headerStartRow . 'C1:R' . $lastRow . 'C' . $totalCols . '"'
            . ' ss:Hidden="1"/></Names>';

        $xml .= '<Table ss:ExpandedColumnCount="' . $totalCols . '"'
            . ' ss:ExpandedRowCount="' . $lastRow . '"'
            . ' x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="20">';

        // Lebar kolom (dalam poin; 1 karakter default Excel ≈ 5,7 poin).
        // Nama 25, Unit 15, Email 30, tiap tanggal 5 (seragam), Total 15.
        $widths = [142, 85, 171];          // Nama, Unit, Email
        for ($i = 0; $i < $daysInMonth; $i++) {
            $widths[] = 29;                 // kolom tanggal (ukuran seragam)
        }
        $widths[] = 85;                     // Total Checkin
        $widths[] = 85;                     // Total Absen
        foreach ($widths as $w) {
            $xml .= '<Column ss:AutoFitWidth="0" ss:Width="' . $w . '"/>';
        }

        // Baris 1: kosong (sesuai layout header bertingkat)
        $xml .= '<Row ss:Height="8"></Row>';

        // Baris 2: header utama bertingkat.
        // Nama, Email, Total Checkin, Total Absen di-merge vertikal (baris 2-3);
        // "Tanggal" di-merge horizontal mencakup seluruh kolom tanggal.
        $xml .= '<Row ss:Height="24">';
        $xml .= $headerCell('Nama', ' ss:MergeDown="1"');
        $xml .= $headerCell('Unit', ' ss:MergeDown="1"');
        $xml .= $headerCell('Email', ' ss:MergeDown="1"');
        $xml .= $headerCell('Tanggal', ' ss:MergeAcross="' . ($daysInMonth - 1) . '"');
        $xml .= $headerCell('Total Checkin', ' ss:MergeDown="1"');
        $xml .= $headerCell('Total Absen', ' ss:MergeDown="1"');
        $xml .= '</Row>';

        // Baris 3: nomor tanggal di bawah "Tanggal" (mulai kolom ke-4)
        $xml .= '<Row ss:Height="20">';
        $xml .= '<Cell ss:StyleID="sHeader" ss:Index="4"><Data ss:Type="String">1</Data></Cell>';
        for ($day = 2; $day <= $daysInMonth; $day++) {
            $xml .= $headerCell((string)$day);
        }
        $xml .= '</Row>';

        // Baris data: Nama & Email rata kiri, tanggal & total rata tengah
        foreach ($rows as $r) {
            $xml .= '<Row ss:Height="20">';
            $xml .= $cell('sText', 'String', $r['name']);
            $xml .= $cell('sText', 'String', $r['unit']);
            $xml .= $cell('sText', 'String', $r['email']);
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $v     = $r['days'][$day] ?? '';
                $style = $v === '✓' ? 'sCheck'
                    : ($v === 'x' ? 'sX'
                    : ($v === '-' ? 'sDash'
                    : (strpos($v, '/') !== false ? 'sPartial' : 'sCenter')));
                $xml .= $cell($style, 'String', $v);
            }
            $xml .= $cell('sCenter', 'Number', (string)$r['total_checkin']);
            $xml .= $cell('sCenter', 'Number', (string)$r['total_absen']);
            $xml .= '</Row>';
        }

        $xml .= '</Table>';

        // WorksheetOptions: page setup (landscape, fit-to-width 1, margin) + freeze header
        $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">';
        $xml .= '<PageSetup>';
        $xml .= '<Header x:Margin="0.2"/>';
        $xml .= '<Footer x:Margin="0.2"/>';
        $xml .= '<PageMargins x:Bottom="0.4" x:Left="0.2" x:Right="0.2" x:Top="0.4"/>';
        $xml .= '<Layout x:Orientation="Landscape" x:FitToPage="1"/>';
        $xml .= '<Print>';
        $xml .= '<FitHeight>0</FitHeight>';
        $xml .= '<FitWidth>1</FitWidth>';
        $xml .= '<ValidPrinterInfo/>';
        $xml .= '<PaperSizeIndex>9</PaperSizeIndex>';
        $xml .= '<HorizontalResolution>600</HorizontalResolution>';
        $xml .= '<VerticalResolution>600</VerticalResolution>';
        $xml .= '</Print>';
        $xml .= '</PageSetup>';
        $xml .= '<Selected/>';
        // Freeze baris 1-3 (baris kosong + 2 baris header bertingkat) supaya
        // header tabel tetap terlihat saat scroll.
        $xml .= '<Panes><Pane><Number>3</Number><ActiveRow>' . ($dataStartRow - 1) . '</ActiveRow>'
            . '<RangeSelection>R1C1</RangeSelection></Pane></Panes>';
        $xml .= '<ProtectObjects>False</ProtectObjects>';
        $xml .= '<ProtectScenarios>False</ProtectScenarios>';
        $xml .= '</WorksheetOptions>';

        // AutoFilter pada baris pertama header bertingkat (Nama s.d. Total Absen)
        $xml .= '<AutoFilter x:Range="R' . $headerStartRow . 'C1:R' . $lastRow . 'C' . $totalCols . '"'
            . ' xmlns="urn:schemas-microsoft-com:office:excel"/>';

        $xml .= '</Worksheet>';
        $xml .= '</Workbook>';

        return $this->response
            ->setContentType('application/vnd.ms-excel')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Transfer-Encoding', 'binary')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($xml);
    }
}
