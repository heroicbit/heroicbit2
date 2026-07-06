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

        // Ambil semua karyawan aktif
        $employees = $db->query("
            SELECT ve.id, ve.user_name as name, ve.unit_name as unit, ve.employee_code
            FROM view_employees ve
            WHERE ve.is_active = 1
            ORDER BY ve.unit_name, ve.user_name
        ")->getResultArray();

        // Ambil presensi untuk tanggal tersebut
        $attendances = $db->query("
            SELECT a.employee_id, a.status, 
                   DATE_FORMAT(a.check_in_time, '%H:%i') as check_in_time,
                   a.check_in_distance_meter
            FROM pres_attendances a
            WHERE a.date = :date:
        ", ['date' => $date])->getResultArray();

        // Index by employee_id
        $attMap = [];
        foreach ($attendances as $att) {
            $attMap[$att['employee_id']] = $att;
        }

        // Ambil jadwal untuk hari tersebut (untuk menentukan bukan_hari_kerja)
        $dayOfWeek = (int)date('N', strtotime($date));
        $schedules = $db->query("
            SELECT es.employee_id
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE es.day_of_week = :dow:
            AND sp.is_active = 1
            AND :date: BETWEEN sp.start_date AND sp.end_date
        ", ['dow' => $dayOfWeek, 'date' => $date])->getResultArray();

        $scheduledIds = array_column($schedules, 'employee_id');

        // Hitung status per karyawan
        $counts = ['hadir' => 0, 'terlambat' => 0, 'tidak_hadir' => 0, 'libur' => 0, 'bukan_hari_kerja' => 0];
        $result = [];

        foreach ($employees as $emp) {
            $eid = $emp['id'];
            $status = null;
            $checkInTime = null;
            $checkInDist = null;

            if (isset($attMap[$eid])) {
                $status = $attMap[$eid]['status'];
                $checkInTime = $attMap[$eid]['check_in_time'];
                $checkInDist = $attMap[$eid]['check_in_distance_meter'];
            } elseif ($holiday) {
                $status = 'libur';
            } elseif (!in_array($eid, $scheduledIds)) {
                $status = 'bukan_hari_kerja';
            } else {
                $status = 'tidak_hadir';
            }

            $counts[$status] = ($counts[$status] ?? 0) + 1;

            $result[] = [
                'id'                      => $eid,
                'name'                    => $emp['name'],
                'unit'                    => $emp['unit'],
                'employee_code'           => $emp['employee_code'],
                'status'                  => $status,
                'check_in_time'           => $checkInTime,
                'check_in_distance_meter' => $checkInDist,
            ];
        }

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => [
                'date'        => $date,
                'is_holiday'  => $holiday !== null,
                'holiday_name'=> $holiday ? $holiday['name'] : null,
                'counts'      => $counts,
                'employees'   => $result,
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

        // Data karyawan
        $employee = $db->query("
            SELECT id, user_name as name, unit_name as unit, position, employee_code
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

        // Statistik presensi dalam periode
        $stats = ['hadir' => 0, 'terlambat' => 0, 'tidak_hadir' => 0, 'libur' => 0, 'bukan_hari_kerja' => 0];
        $calendarData = [];

        if ($periodId && $period) {
            // Hitung statistik
            $statQuery = $db->query("
                SELECT a.status, COUNT(*) as total
                FROM pres_attendances a
                WHERE a.employee_id = :eid:
                AND a.date BETWEEN 
                    (SELECT start_date FROM pres_schedule_periods WHERE id = :pid:)
                    AND (SELECT end_date FROM pres_schedule_periods WHERE id = :pid:)
                GROUP BY a.status
            ", ['eid' => $employeeId, 'pid' => $periodId])->getResultArray();

            foreach ($statQuery as $row) {
                $stats[$row['status']] = (int)$row['total'];
            }

            // Data kalender bulan ini
            $startDate = "$year-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            $calData = $db->query("
                SELECT a.date, a.status
                FROM pres_attendances a
                WHERE a.employee_id = :eid:
                AND a.date BETWEEN :start: AND :end:
                ORDER BY a.date
            ", [
                'eid'   => $employeeId,
                'start' => $startDate,
                'end'   => $endDate
            ])->getResultArray();

            $calendarData = $calData;
        }

        $totalWorkDays = $stats['hadir'] + $stats['terlambat'] + $stats['tidak_hadir'];
        $percent = $totalWorkDays > 0
            ? round(($stats['hadir'] + $stats['terlambat']) / $totalWorkDays * 100, 1)
            : 0;

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
            $dayNames = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];
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
                    'telat'   => $stats['terlambat'],
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
