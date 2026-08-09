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
                if ($dow === 7) {
                    $workDow[0] = true; // Minggu: konvensi ISO (7) dan getDay (0)
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
}
