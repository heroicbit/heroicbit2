// Page member/checkin
document.addEventListener('alpine:init', () => {
    Alpine.data('member_checkin', () => ({
        title: "Presensi Karyawan",
        APP_CONFIG: {
            minAccuracyMeter: 50,
            geoTimeout: 15000,
            geoMaximumAge: 5000,
            historyLimit: 30
        },

        tab: 'home',
        employee: { name: '', position: '', unit: '', role: '' },
        employeeNotFound: false,
        officeLocations: [],
        nearestLocation: null,
        unitSchedules: [],
        usedUnitScheduleIds: [],
        todayCheckins: [],

        clockHM: '', clockS: '', dateLong: '',

        geoLoading: true,
        geoErrorMsg: null,
        geoWatchId: null,
        currentLat: null,
        currentLng: null,
        accuracy: null,
        distance: null,
        inRadius: false,
        map: null,
        mapInitialized: false,
        userMarker: null,
        officeMarkers: [],
        radiusCircle: null,

        loadingToday: true,
        todayStatus: {
            checked_in: false, checked_out: false,
            check_in_time: null, check_out_time: null,
            check_in_distance_meter: null, check_out_distance_meter: null,
            status: null,
            is_holiday: false, is_day_off: false,
            holiday_name: null,
            schedule: null
        },
        submitting: false,

        loadingHistory: true,
        historyLoaded: false,
        history: [],

        toast: { show: false, type: 'success', title: '', desc: '' },

        // Popup sukses (lebih prominent daripada toast)
        popup: { show: false, type: 'success', title: '', desc: '', sub: '' },

        // Titik lokasi presensi terdekat dari posisi pengguna.
        // Dipakai untuk tampilan jarak, radius, nama di topbar, dan sebagai
        // titik check-in. Ditentukan ulang setiap posisi berubah.
        get officeLocation() {
            return this.nearestLocation;
        },

        // ------------------------------------------------------------
        // INIT
        // ------------------------------------------------------------
        async init() {
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'checkin';
            Alpine.store('tarbiyya').showBottomMenu = false;

            this.startClock();

            // Muat data dari cache atau fetch dari server
            if (cachePageData['member/checkin']) {
                const cached = cachePageData['member/checkin'];
                this.employee = cached.data.employee;
                this.officeLocations = cached.data.office_locations
                    || (cached.data.office_location ? [cached.data.office_location] : []);
                this.todayStatus = cached.data.today_status;
                this.unitSchedules = cached.data.unit_schedules || [];
                this.usedUnitScheduleIds = cached.data.used_unit_schedule_ids || [];
                this.todayCheckins = cached.data.today_checkins || [];
                this.loadingToday = false;

                this.updateNearestLocation();
                this.$nextTick(() => this.initMap());
            } else {
                await this.loadSupplyData();
            }

            this.startWatchingLocation();
        },

        // ------------------------------------------------------------
        // DATA FETCHING
        // ------------------------------------------------------------
        async loadSupplyData() {
            try {
                const data = await fetchPageData('member/checkin/supply', {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    }
                });

                if (data && data.response_code === 200 && data.data) {
                    cachePageData['member/checkin'] = data;
                    this.employee = data.data.employee;
                    this.officeLocations = data.data.office_locations || [];
                    this.todayStatus = data.data.today_status;
                    this.unitSchedules = data.data.unit_schedules || [];
                    this.usedUnitScheduleIds = data.data.used_unit_schedule_ids || [];
                    this.todayCheckins = data.data.today_checkins || [];
                    this.loadingToday = false;

                    this.updateNearestLocation();
                    this.$nextTick(() => this.initMap());
                } else if (data && data.response_code === 404) {
                    this.employeeNotFound = true;
                    this.employee = { name: 'Karyawan tidak ditemukan', role: '', unit: '' };
                    this.loadingToday = false;
                }
            } catch (e) {
                this.employee = { name: 'Gagal memuat profil', role: '', unit: '' };
                this.showToast('error', 'Gagal memuat data presensi', e.message || 'Silakan coba lagi.');
                this.loadingToday = false;
            }
        },

        switchToHistory() {
            this.tab = 'history';
            if (!this.historyLoaded) this.loadHistory();
        },

        async loadHistory() {
            this.loadingHistory = true;
            try {
                const data = await fetchPageData(
                    'member/checkin/history?limit=' + this.APP_CONFIG.historyLimit,
                    {
                        headers: {
                            'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                            'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                        }
                    }
                );
                if (data && data.response_code === 200) {
                    this.history = data.data || [];
                } else {
                    this.history = [];
                }
                this.historyLoaded = true;
            } catch (e) {
                this.showToast('error', 'Gagal memuat riwayat presensi', e.message);
                this.history = [];
            } finally {
                this.loadingHistory = false;
            }
        },

        // ------------------------------------------------------------
        // CLOCK
        // ------------------------------------------------------------
        startClock() {
            const update = () => {
                const d = new Date();
                const hh = String(d.getHours()).padStart(2, '0');
                const mm = String(d.getMinutes()).padStart(2, '0');
                const ss = String(d.getSeconds()).padStart(2, '0');
                this.clockHM = hh + ':' + mm;
                this.clockS = ss;
                const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][d.getDay()];
                const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][d.getMonth()];
                this.dateLong = hari + ', ' + d.getDate() + ' ' + bulan + ' ' + d.getFullYear();
            };
            update();
            setInterval(update, 1000);
        },

        // ------------------------------------------------------------
        // GEOLOCATION
        // ------------------------------------------------------------
        startWatchingLocation() {
            if (!('geolocation' in navigator)) {
                this.geoLoading = false;
                this.geoErrorMsg = 'Perangkat atau browser ini tidak mendukung layanan lokasi.';
                return;
            }
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                this.geoLoading = false;
                this.geoErrorMsg = 'Halaman ini perlu diakses melalui HTTPS agar lokasi dapat dideteksi.';
                return;
            }
            this.geoLoading = true;
            this.geoErrorMsg = null;
            this.geoWatchId = navigator.geolocation.watchPosition(
                pos => this.handlePosition(pos),
                err => this.handleGeoError(err),
                { enableHighAccuracy: true, timeout: this.APP_CONFIG.geoTimeout, maximumAge: this.APP_CONFIG.geoMaximumAge }
            );
        },

        handlePosition(pos) {
            this.geoLoading = false;
            this.geoErrorMsg = null;
            this.currentLat = pos.coords.latitude;
            this.currentLng = pos.coords.longitude;
            this.accuracy = pos.coords.accuracy;
            this.updateNearestLocation();
            this.updateMap();
        },

        // Tentukan titik presensi terdekat dari posisi pengguna.
        updateNearestLocation() {
            if (!this.officeLocations || !this.officeLocations.length) {
                this.nearestLocation = null;
                this.distance = null;
                this.inRadius = false;
                return;
            }
            // Sebelum posisi diketahui, tampilkan titik pertama sebagai default
            // (mis. nama lokasi di topbar).
            if (this.currentLat === null || this.currentLng === null) {
                this.nearestLocation = this.officeLocations[0];
                this.distance = null;
                this.inRadius = false;
                return;
            }
            let nearest = null;
            let minDist = Infinity;
            for (const loc of this.officeLocations) {
                const d = this.distanceMeters(this.currentLat, this.currentLng, loc.latitude, loc.longitude);
                if (d < minDist) {
                    minDist = d;
                    nearest = loc;
                }
            }
            this.nearestLocation = nearest;
            this.distance = minDist;
            this.inRadius = minDist <= nearest.radius_meter;
        },

        handleGeoError(err) {
            this.geoLoading = false;
            if (err.code === err.PERMISSION_DENIED) {
                this.geoErrorMsg = 'Izin lokasi ditolak. Aktifkan izin lokasi untuk situs ini lewat pengaturan browser Anda, lalu coba lagi.';
            } else if (err.code === err.POSITION_UNAVAILABLE) {
                this.geoErrorMsg = 'Lokasi tidak dapat dideteksi saat ini. Pastikan GPS aktif dan coba lagi.';
            } else if (err.code === err.TIMEOUT) {
                this.geoErrorMsg = 'Deteksi lokasi memakan waktu terlalu lama. Coba lagi.';
            } else {
                this.geoErrorMsg = 'Terjadi kesalahan saat mendeteksi lokasi.';
            }
        },

        retryLocation() {
            if (this.geoWatchId !== null) navigator.geolocation.clearWatch(this.geoWatchId);
            this.startWatchingLocation();
        },

        distanceMeters(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const toRad = d => (d * Math.PI) / 180;
            const dLat = toRad(lat2 - lat1);
            const dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat / 2) ** 2 +
                      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
            return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
        },

        // ------------------------------------------------------------
        // MAP (Leaflet)
        // ------------------------------------------------------------
        initMap() {
            if (this.mapInitialized || !this.officeLocations || !this.officeLocations.length || !this.$refs.mapContainer) return;

            // Tunggu Leaflet siap (CDN script mungkin belum selesai load
            // karena view di-render async oleh PineconeRouter)
            if (typeof L === 'undefined') {
                setTimeout(() => this.initMap(), 200);
                return;
            }

            this.mapInitialized = true;

            try {
                const first = this.officeLocations[0];
                this.map = L.map(this.$refs.mapContainer, {
                    center: [first.latitude, first.longitude],
                    zoom: 16,
                    zoomControl: true,
                    attributionControl: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);

                // Marker untuk setiap titik lokasi presensi
                this.officeMarkers = this.officeLocations.map(loc => {
                    const marker = L.marker([loc.latitude, loc.longitude], {
                        icon: this.makeOfficeIcon(loc, false)
                    })
                        .addTo(this.map)
                        .bindPopup('<b>' + loc.name + '</b><br>Titik lokasi presensi');
                    return { loc, marker };
                });

                // Lingkaran radius & gaya marker titik terdekat
                this.updateMap();

                setTimeout(() => this.map && this.map.invalidateSize(), 300);
            } catch (e) {
                console.error('Gagal menginisialisasi peta:', e);
                this.mapInitialized = false;
            }
        },

        // Ikon titik presensi. Titik terdekat dari pengguna diberi warna berbeda.
        makeOfficeIcon(loc, isNearest) {
            const color = isNearest ? '#157CA1' : '#3BC0CF';
            return L.divIcon({
                html: '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><circle cx="14" cy="14" r="12" fill="' + color + '" stroke="#fff" stroke-width="2.5"/><path d="M14 8c-2.2 0-4 1.8-4 4 0 3 4 7 4 7s4-4 4-7c0-2.2-1.8-4-4-4zm0 5.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" fill="#fff"/></svg>',
                className: '',
                iconSize: [28, 28],
                iconAnchor: [14, 28],
                popupAnchor: [0, -32]
            });
        },

        updateMap() {
            if (!this.map || !this.mapInitialized) return;

            // Sorot titik terdekat & gambar ulang lingkaran radius di titik tersebut
            const nearest = this.officeLocation;
            if (this.officeMarkers) {
                for (const { loc, marker } of this.officeMarkers) {
                    marker.setIcon(this.makeOfficeIcon(loc, nearest && loc.id === nearest.id));
                }
            }
            if (this.radiusCircle) {
                this.map.removeLayer(this.radiusCircle);
                this.radiusCircle = null;
            }
            if (nearest) {
                this.radiusCircle = L.circle([nearest.latitude, nearest.longitude], {
                    radius: nearest.radius_meter,
                    color: '#157CA1',
                    fillColor: '#CEE6F0',
                    fillOpacity: 0.35,
                    weight: 2,
                    dashArray: '5, 8'
                }).addTo(this.map);
            }

            if (this.currentLat === null || this.currentLng === null) return;

            // Marker posisi pengguna
            if (this.userMarker) {
                this.userMarker.setLatLng([this.currentLat, this.currentLng]);
            } else {
                const userIcon = L.divIcon({
                    html: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" fill="#2563EB" stroke="#fff" stroke-width="2.5"/><circle cx="12" cy="12" r="3" fill="#fff"/></svg>',
                    className: '',
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                this.userMarker = L.marker([this.currentLat, this.currentLng], { icon: userIcon })
                    .addTo(this.map)
                    .bindPopup('Lokasi Anda saat ini');
            }

            if (!this.map._userBoundsAdjusted && this.officeMarkers && this.officeMarkers.length) {
                const group = L.featureGroup([
                    ...this.officeMarkers.map(m => m.marker),
                    this.userMarker
                ]);
                this.map.fitBounds(group.getBounds(), { padding: [30, 30], maxZoom: 17 });
                this.map._userBoundsAdjusted = true;
            }

            this.map.invalidateSize();
        },

        // ------------------------------------------------------------
        // CTA (Check-in / Check-out)
        // ------------------------------------------------------------
        get ctaEnabled() {
            if (this.geoLoading || this.submitting || !this.inRadius) return false;
            const ts = this.todayStatus || {};
            if (ts.is_holiday || ts.is_day_off) return false;
            if (ts.checked_in) return false; // sudah checkin hari ini
            // Checkin aktif mulai expected_time_in (jika diatur)
            if (ts.schedule && ts.schedule.expected_time_in) {
                if (this.clockHM < this.timeToHM(ts.schedule.expected_time_in)) return false;
            }
            return true;
        },

        // Belum waktunya check-in (legacy: sebelum expected_time_in)
        get beforeExpectedTime() {
            const ts = this.todayStatus || {};
            if (ts.checked_in) return false;
            const ei = ts.schedule && ts.schedule.expected_time_in;
            if (!ei) return false;
            return this.clockHM < this.timeToHM(ei);
        },

        get expectedTimeLabel() {
            const ei = this.todayStatus.schedule && this.todayStatus.schedule.expected_time_in;
            return ei ? this.timeToHM(ei) : '';
        },

        get ctaLabel() {
            if (this.submitting) return 'Memproses...';
            const ts = this.todayStatus || {};
            if (ts.is_holiday) return 'Hari Libur';
            if (ts.is_day_off) return 'Bukan Hari Kerja';
            return 'Checkin Masuk';
        },

        async handleCta() {
            if (!this.ctaEnabled) return;
            await this.checkIn();
        },

        // ------------------------------------------------------------
        // JADWAL UNIT (pres_unit_schedules)
        // ------------------------------------------------------------
        // Normalisasi jam "HH:MM:SS" -> "HH:MM" (null jika kosong)
        timeToHM(t) {
            if (t === null || t === undefined || t === '') return null;
            return String(t).slice(0, 5);
        },

        // Label rentang waktu aktif sebuah jadwal
        scheduleWindowLabel(s) {
            const tIn = this.timeToHM(s.time_in);
            const tOut = this.timeToHM(s.time_out);
            if (tIn === null) return 'setiap waktu';
            if (tOut !== null) return tIn + ' – ' + tOut;
            return 'mulai ' + tIn;
        },

        // Apakah waktu sekarang berada dalam rentang time_in..time_out jadwal.
        // Jika time_in NULL -> selalu aktif (tidak dibatasi waktu).
        isScheduleWindowActive(s) {
            const tIn = this.timeToHM(s.time_in);
            if (tIn === null) return true;
            const now = this.clockHM; // diperbarui tiap detik oleh startClock
            const tOut = this.timeToHM(s.time_out);
            // Rentang lintas tengah malam (mis. 22:00 -> 02:00)
            if (tOut !== null && tOut < tIn) {
                return now >= tIn || now <= tOut;
            }
            if (now < tIn) return false;
            if (tOut !== null && now > tOut) return false;
            return true;
        },

        isScheduleMissed(s) {
            if (this.isScheduleDone(s)) return false;
            const tIn = this.timeToHM(s.time_in);
            if (tIn === null) return false;
            const now = this.clockHM;
            const tOut = this.timeToHM(s.time_out);
            if (tOut === null) {
                return false;
            }
            if (tOut < tIn) {
                return now > tOut && now < tIn;
            }
            return now > tOut;
        },

        getNextUpcomingSchedule() {
            const now = this.clockHM;
            for (const s of this.unitSchedules) {
                if (this.isScheduleDone(s) || this.isScheduleWindowActive(s) || this.isScheduleMissed(s)) {
                    continue;
                }
                const tIn = this.timeToHM(s.time_in);
                if (tIn === null) {
                    continue;
                }
                if (tIn > now) {
                    return s;
                }
            }
            return null;
        },

        hasPendingActiveSchedule() {
            return this.unitSchedules.some(s => !this.isScheduleDone(s) && this.isScheduleWindowActive(s));
        },

        isScheduleNextUpcoming(s) {
            const next = this.getNextUpcomingSchedule();
            if (!next) return false;
            if (this.hasPendingActiveSchedule()) return false;
            return next.id === s.id;
        },

        // Tombol jadwal sudah dipakai hari ini (hijau & disabled)
        isScheduleDone(s) {
            return this.usedUnitScheduleIds.indexOf(s.id) !== -1;
        },

        // Tombol checkin sebuah jadwal aktif atau tidak (lokasi + waktu + status)
        isScheduleButtonEnabled(s) {
            if (this.geoLoading || this.submitting || !this.inRadius) return false;
            const ts = this.todayStatus || {};
            if (ts.is_holiday || ts.is_day_off) return false;
            if (this.isScheduleDone(s)) return false; // sudah dipakai hari ini
            return this.isScheduleWindowActive(s);
        },

        // Klik tombol checkin sesuai jadwal unit
        async handleScheduleCta(s) {
            if (!this.isScheduleButtonEnabled(s)) return;
            await this.checkIn(s);
        },

        async checkIn(schedule) {
            this.submitting = true;
            try {
                const response = await axios.post(
                    base_url + 'member/checkin/checkIn',
                    {
                        latitude: this.currentLat,
                        longitude: this.currentLng,
                        accuracy: this.accuracy,
                        schedule_id: schedule ? schedule.id : null,
                        office_location_id: this.officeLocation ? this.officeLocation.id : null
                    },
                    {
                        headers: {
                            'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                            'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
                            'Content-Type': 'application/json'
                        }
                    }
                );

                const res = response.data;
                if (res.response_code === 200 && res.data) {
                    this.todayStatus.checked_in = true;
                    this.todayStatus.check_in_time = res.data.check_in_time;
                    this.todayStatus.check_in_distance_meter = res.data.distance_meter;
                    this.todayStatus.status = res.data.status;
                    if (schedule && this.usedUnitScheduleIds.indexOf(schedule.id) === -1) {
                        this.usedUnitScheduleIds.push(schedule.id);
                    }
                    this.todayCheckins.push({
                        unit_schedule_id: schedule ? schedule.id : null,
                        title: schedule ? schedule.title : null,
                        check_in_time: res.data.check_in_time,
                        check_in_distance_meter: res.data.distance_meter,
                        status: res.data.status,
                        office_location_name: res.data.office_location_name || null
                    });
                    const popupTitle = schedule && schedule.title
                        ? 'Absen Masuk: ' + schedule.title
                        : 'Absen Masuk Berhasil 🎉';
                    const locName = res.data.office_location_name
                        || (this.officeLocation ? this.officeLocation.name : '');
                    this.showPopup('success',
                        popupTitle,
                        'Pukul ' + res.data.check_in_time + ' · ' + res.data.distance_meter + ' m dari lokasi' + (locName ? ' (' + locName + ')' : ''),
                        res.data.status === 'terlambat' ? 'Anda tercatat terlambat hari ini.' : '');
                } else {
                    this.showToast('error', 'Absen masuk gagal', res.response_message || 'Silakan coba lagi.');
                }
            } catch (e) {
                const msg = e.response && e.response.data && e.response.data.response_message
                    ? e.response.data.response_message
                    : 'Terjadi kesalahan. Silakan coba lagi.';
                this.showToast('error', 'Absen masuk gagal', msg);
            } finally {
                this.submitting = false;
            }
        },

        // ------------------------------------------------------------
        // COMPUTED & HELPERS
        // ------------------------------------------------------------
        get historySummary() {
            const total = this.history.length;
            const terlambat = this.history.filter(h => h.status === 'terlambat').length;
            return { total, terlambat, hadir: total - terlambat };
        },

        formatDateLabel(isoDate) {
            const d = new Date(isoDate + 'T00:00:00');
            const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][d.getMonth()];
            return d.getDate() + ' ' + bulan;
        },

        statusLabel(status) {
            const labels = {
                'hadir': 'Hadir',
                'terlambat': 'Terlambat',
                'tidak_hadir': 'Tidak Hadir',
                'libur': 'Libur',
                'bukan_hari_kerja': 'Bukan Hari Kerja',
                'izin': 'Izin',
                'sakit': 'Sakit',
                'cuti': 'Cuti'
            };
            return labels[status] || status;
        },

        getScheduleCheckinById(scheduleId) {
            if (scheduleId === null || scheduleId === undefined) return null;
            return this.todayCheckins.find(c => c.unit_schedule_id === scheduleId) || null;
        },

        getScheduleCheckinTime(schedule) {
            const checkin = this.getScheduleCheckinById(schedule.id);
            return checkin ? checkin.check_in_time : '—';
        },

        getScheduleCheckinDistance(schedule) {
            const checkin = this.getScheduleCheckinById(schedule.id);
            return checkin ? checkin.check_in_distance_meter : null;
        },

        getScheduleCheckinStatus(schedule) {
            const checkin = this.getScheduleCheckinById(schedule.id);
            return checkin ? checkin.status : null;
        },

        get showBottomNav() {
            const slug = (this.employee.role || '').toLowerCase();
            return slug === 'super' || slug === 'admin';
        },

        showToast(type, title, desc) {
            this.toast = { show: true, type, title, desc: desc || '' };
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => { this.toast.show = false; }, 3800);
        },

        showPopup(type, title, desc, sub) {
            this.popup = { show: true, type, title, desc: desc || '', sub: sub || '' };
        },

        closePopup() {
            this.popup.show = false;
        }
    }));
});
