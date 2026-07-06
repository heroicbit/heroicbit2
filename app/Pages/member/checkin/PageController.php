<?php namespace App\Pages\member\checkin;

use App\Pages\member\PageController as MemberPageController;

class PageController extends MemberPageController {

    public function getContent()
    {
        return pageView('member/checkin/index', $this->data);
    }

    public function getSupply()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        // --- Ambil data karyawan yang terkait dengan user login ---
        $employee = $db->query("
            SELECT id, employee_code, user_name, position, 
                   employment_type, user_email, user_phone, is_active,
                   unit_id, unit_name, role, role_id
            FROM view_employees
            WHERE user_id = :user_id:
            AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code'    => 404,
                'response_message' => 'Data karyawan tidak ditemukan',
                'data'             => null
            ]);
        }

        // --- Ambil lokasi kantor aktif ---
        $officeLocation = $db->query("
            SELECT id, name, latitude, longitude, radius_meter
            FROM pres_office_locations
            WHERE is_active = 1
            LIMIT 1
        ")->getRowArray();

        // --- Ambil status presensi hari ini ---
        $today = date('Y-m-d');
        $attendance = $db->query("
            SELECT a.id, a.date, a.status, a.check_in_time, a.check_out_time,
                   a.check_in_distance_meter, a.check_out_distance_meter,
                   a.check_in_latitude, a.check_in_longitude,
                   a.check_out_latitude, a.check_out_longitude,
                   a.notes
            FROM pres_attendances a
            WHERE a.employee_id = :employee_id:
            AND a.date = :date:
        ", [
            'employee_id' => $employee['id'],
            'date'        => $today
        ])->getRowArray();

        // Tentukan jadwal hari ini (untuk menentukan expected time & toleransi)
        $dayOfWeek = (int)date('N'); // 1=Senin, 7=Minggu
        $employeeId = (int)$employee['id'];

        $schedule = $db->query("
            SELECT es.id, es.day_of_week, es.expected_time_in, es.expected_time_out,
                   es.late_tolerance_minutes, sp.id as period_id, sp.name as period_name
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE es.employee_id = :employee_id:
            AND es.day_of_week = :day_of_week:
            AND sp.is_active = 1
            AND CURDATE() BETWEEN sp.start_date AND sp.end_date
            LIMIT 1
        ", [
            'employee_id' => $employeeId,
            'day_of_week' => $dayOfWeek
        ])->getRowArray();

        // Cek apakah hari ini libur
        $holiday = $db->query("
            SELECT h.id, h.name, h.category
            FROM pres_holidays h
            LEFT JOIN pres_holiday_units hu ON hu.holiday_id = h.id
            WHERE :date: BETWEEN h.start_date AND h.end_date
            AND (h.applies_to_all = 1 OR hu.unit_id = :unit_id:)
            LIMIT 1
        ", [
            'date'    => $today,
            'unit_id' => $employee['unit_id']
        ])->getRowArray();

        $isHoliday = $holiday !== null;
        $isScheduled = $schedule !== null;
        $isDayOff = !$isScheduled && !$isHoliday;

        // Format response todayStatus
        $todayStatus = [
            'checked_in'              => false,
            'checked_out'             => false,
            'check_in_time'           => null,
            'check_out_time'          => null,
            'check_in_distance_meter' => null,
            'check_out_distance_meter'=> null,
            'status'                  => null,
            'is_holiday'              => $isHoliday,
            'is_day_off'              => $isDayOff,
            'holiday_name'            => $holiday ? $holiday['name'] : null,
            'schedule'                => $schedule ? [
                'expected_time_in'       => $schedule['expected_time_in'],
                'expected_time_out'      => $schedule['expected_time_out'],
                'late_tolerance_minutes' => (int)$schedule['late_tolerance_minutes'],
            ] : null,
        ];

        if ($attendance) {
            $todayStatus['checked_in']               = $attendance['check_in_time'] !== null;
            $todayStatus['checked_out']              = $attendance['check_out_time'] !== null;
            $todayStatus['check_in_time']            = $attendance['check_in_time'] 
                ? date('H:i', strtotime($attendance['check_in_time'])) : null;
            $todayStatus['check_out_time']           = $attendance['check_out_time'] 
                ? date('H:i', strtotime($attendance['check_out_time'])) : null;
            $todayStatus['check_in_distance_meter']  = $attendance['check_in_distance_meter'];
            $todayStatus['check_out_distance_meter'] = $attendance['check_out_distance_meter'];
            $todayStatus['status']                   = $attendance['status'];
        }

        $data = [
            'employee'       => [
                'id'              => $employee['id'],
                'name'            => $employee['user_name'],
                'role'            => $employee['role'],
                'position'        => $employee['position'],
                'unit'            => $employee['unit_name'],
                'employee_code'   => $employee['employee_code'],
                'employment_type' => $employee['employment_type'],
            ],
            'office_location' => $officeLocation ? [
                'id'           => $officeLocation['id'],
                'name'         => $officeLocation['name'],
                'latitude'     => (float)$officeLocation['latitude'],
                'longitude'    => (float)$officeLocation['longitude'],
                'radius_meter' => (int)$officeLocation['radius_meter'],
            ] : null,
            'today_status'    => $todayStatus,
        ];

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => $data
        ]);
    }

    /**
     * POST: Check-in presensi
     */
    public function postCheckIn()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $input = $this->request->getJSON();

        $latitude  = $input->latitude ?? null;
        $longitude = $input->longitude ?? null;
        $accuracy  = $input->accuracy ?? null;

        if ($latitude === null || $longitude === null) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Data lokasi (latitude, longitude) diperlukan.'
            ], 422);
        }

        // Ambil data karyawan dari view_employees
        $employee = $db->query("
            SELECT id, unit_id
            FROM view_employees
            WHERE user_id = :user_id:
            AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code'    => 200,
                'found'            => 0,
                'response_message' => 'Data karyawan tidak ditemukan.'
            ]);
        }

        $today = date('Y-m-d');

        // Cek apakah sudah check-in hari ini
        $existing = $db->query("
            SELECT id FROM pres_attendances 
            WHERE employee_id = :employee_id: AND date = :date:
        ", ['employee_id' => $employee['id'], 'date' => $today])->getRow();

        if ($existing) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Anda sudah melakukan absen masuk hari ini.'
            ], 422);
        }

        // Ambil lokasi kantor aktif
        $officeLocation = $db->query("
            SELECT id, latitude, longitude, radius_meter
            FROM pres_office_locations
            WHERE is_active = 1
            LIMIT 1
        ")->getRowArray();

        if (!$officeLocation) {
            return $this->respond([
                'response_code'    => 500,
                'response_message' => 'Lokasi presensi belum dikonfigurasi.'
            ], 500);
        }

        // Validasi jarak di server (Haversine)
        $distance = $this->haversineDistance(
            (float)$latitude, (float)$longitude,
            (float)$officeLocation['latitude'], (float)$officeLocation['longitude']
        );

        if ($distance > (int)$officeLocation['radius_meter']) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => "Anda berada di luar radius presensi ({$distance} m). Maksimal {$officeLocation['radius_meter']} m dari lokasi."
            ], 422);
        }

        // Cek hari libur
        $holiday = $db->query("
            SELECT h.id FROM pres_holidays h
            LEFT JOIN pres_holiday_units hu ON hu.holiday_id = h.id
            WHERE :date: BETWEEN h.start_date AND h.end_date
            AND (h.applies_to_all = 1 OR hu.unit_id = :unit_id:)
            LIMIT 1
        ", ['date' => $today, 'unit_id' => $employee['unit_id']])->getRow();

        if ($holiday) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Hari ini adalah hari libur.'
            ], 422);
        }

        // Tentukan status: hadir / terlambat
        $status = 'hadir';
        $dayOfWeek = (int)date('N');
        $schedule = $db->query("
            SELECT es.expected_time_in, es.late_tolerance_minutes
            FROM pres_employee_schedules es
            JOIN pres_schedule_periods sp ON sp.id = es.period_id
            WHERE es.employee_id = :employee_id:
            AND es.day_of_week = :day_of_week:
            AND sp.is_active = 1
            AND CURDATE() BETWEEN sp.start_date AND sp.end_date
            LIMIT 1
        ", [
            'employee_id' => (int)$employee['id'],
            'day_of_week' => $dayOfWeek
        ])->getRowArray();

        $now = new \DateTime();
        $checkInTime = $now->format('Y-m-d H:i:s');

        if ($schedule && $schedule['expected_time_in']) {
            $expectedTimeIn = \DateTime::createFromFormat('H:i:s', $schedule['expected_time_in']);
            $tolerance = (int)$schedule['late_tolerance_minutes'];
            
            if ($expectedTimeIn) {
                $deadline = (clone $expectedTimeIn)->modify("+{$tolerance} minutes");
                $currentTime = \DateTime::createFromFormat('H:i', $now->format('H:i'));
                
                if ($currentTime > $deadline) {
                    $status = 'terlambat';
                }
            }
        }

        // Cek apakah hari ini dijadwalkan kerja
        if (!$schedule) {
            // Jika tidak ada jadwal, cek apakah hari ini bukan hari kerja
            // (minggu atau memang tidak dijadwalkan)
            $status = 'bukan_hari_kerja';
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Hari ini bukan hari kerja Anda.'
            ], 422);
        }

        // Simpan data presensi
        $insertData = [
            'employee_id'            => $employee['id'],
            'office_location_id'     => $officeLocation['id'],
            'date'                   => $today,
            'status'                 => $status,
            'check_in_time'          => $checkInTime,
            'check_in_latitude'      => $latitude,
            'check_in_longitude'     => $longitude,
            'check_in_distance_meter'=> $distance,
        ];

        // Cari schedule_period_id
        $period = $db->query("
            SELECT sp.id FROM pres_schedule_periods sp
            WHERE sp.is_active = 1
            AND CURDATE() BETWEEN sp.start_date AND sp.end_date
            LIMIT 1
        ")->getRowArray();
        if ($period) {
            $insertData['schedule_period_id'] = $period['id'];
        }

        $db->table('pres_attendances')->insert($insertData);
        $attendanceId = $db->insertID();

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'Absen masuk berhasil.',
            'data'             => [
                'check_in_time'           => $now->format('H:i'),
                'distance_meter'          => $distance,
                'status'                  => $status,
            ]
        ]);
    }

    /**
     * POST: Check-out presensi
     */
    public function postCheckOut()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $input = $this->request->getJSON();

        $latitude  = $input->latitude ?? null;
        $longitude = $input->longitude ?? null;

        if ($latitude === null || $longitude === null) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Data lokasi (latitude, longitude) diperlukan.'
            ], 422);
        }

        // Ambil data karyawan dari view_employees
        $employee = $db->query("
            SELECT id FROM view_employees
            WHERE user_id = :user_id:
            AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code'    => 404,
                'response_message' => 'Data karyawan tidak ditemukan.'
            ], 404);
        }

        $today = date('Y-m-d');

        // Cek apakah sudah check-in hari ini
        $attendance = $db->query("
            SELECT id, check_out_time FROM pres_attendances 
            WHERE employee_id = :employee_id: AND date = :date:
        ", ['employee_id' => $employee['id'], 'date' => $today])->getRowArray();

        if (!$attendance) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Anda belum melakukan absen masuk hari ini.'
            ], 422);
        }

        if ($attendance['check_out_time']) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => 'Anda sudah melakukan absen pulang hari ini.'
            ], 422);
        }

        // Ambil lokasi kantor
        $officeLocation = $db->query("
            SELECT id, latitude, longitude, radius_meter
            FROM pres_office_locations
            WHERE is_active = 1
            LIMIT 1
        ")->getRowArray();

        if (!$officeLocation) {
            return $this->respond([
                'response_code'    => 500,
                'response_message' => 'Lokasi presensi belum dikonfigurasi.'
            ], 500);
        }

        // Validasi jarak
        $distance = $this->haversineDistance(
            (float)$latitude, (float)$longitude,
            (float)$officeLocation['latitude'], (float)$officeLocation['longitude']
        );

        if ($distance > (int)$officeLocation['radius_meter']) {
            return $this->respond([
                'response_code'    => 422,
                'response_message' => "Anda berada di luar radius presensi ({$distance} m). Maksimal {$officeLocation['radius_meter']} m dari lokasi."
            ], 422);
        }

        $now = new \DateTime();
        $checkOutTime = $now->format('Y-m-d H:i:s');

        $db->table('pres_attendances')
            ->where('id', $attendance['id'])
            ->update([
                'check_out_time'           => $checkOutTime,
                'check_out_latitude'       => $latitude,
                'check_out_longitude'      => $longitude,
                'check_out_distance_meter' => $distance,
            ]);

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'Absen pulang berhasil.',
            'data'             => [
                'check_out_time'  => $now->format('H:i'),
                'distance_meter'  => $distance,
            ]
        ]);
    }

    /**
     * GET: Riwayat presensi
     */
    public function getHistory()
    {
        date_default_timezone_set('Asia/Jakarta');

        $Tarbiyya = new \App\Libraries\Tarbiyya();
        $user = $Tarbiyya->checkToken();
        $db = $Tarbiyya->initDBPesantren();

        $limit = (int)($this->request->getGet('limit') ?? 30);

        $employee = $db->query("
            SELECT id FROM view_employees
            WHERE user_id = :user_id: AND is_active = 1
        ", ['user_id' => $user->user_id])->getRowArray();

        if (!$employee) {
            return $this->respond([
                'response_code'    => 404,
                'response_message' => 'Data karyawan tidak ditemukan.',
                'data'             => []
            ], 404);
        }

        $history = $db->query("
            SELECT a.date, 
                   DATE_FORMAT(a.check_in_time, '%H:%i') as check_in_time,
                   DATE_FORMAT(a.check_out_time, '%H:%i') as check_out_time,
                   a.status,
                   a.check_in_distance_meter,
                   a.check_out_distance_meter
            FROM pres_attendances a
            WHERE a.employee_id = :employee_id:
            ORDER BY a.date DESC
            LIMIT :limit:
        ", [
            'employee_id' => $employee['id'],
            'limit'       => $limit
        ])->getResultArray();

        return $this->respond([
            'response_code'    => 200,
            'response_message' => 'success',
            'data'             => $history
        ]);
    }

    /**
     * Hitung jarak menggunakan formula Haversine (dalam meter)
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // meter

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int)round($earthRadius * $c);
    }
}
