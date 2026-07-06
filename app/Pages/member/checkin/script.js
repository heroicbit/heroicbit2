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
        officeLocation: null,

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
        officeMarker: null,
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
        checkInTimestamp: null,  // epoch ms kapan check-in dilakukan
        submitting: false,

        loadingHistory: true,
        historyLoaded: false,
        history: [],

        toast: { show: false, type: 'success', title: '', desc: '' },

        // Popup sukses (lebih prominent daripada toast)
        popup: { show: false, type: 'success', title: '', desc: '', sub: '' },

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
                this.officeLocation = cached.data.office_location;
                this.todayStatus = cached.data.today_status;
                this.loadingToday = false;
                this.restoreCheckInTimestamp();

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
                    this.officeLocation = data.data.office_location;
                    this.todayStatus = data.data.today_status;
                    this.loadingToday = false;
                    this.restoreCheckInTimestamp();

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
            if (this.officeLocation) {
                this.distance = this.distanceMeters(
                    this.currentLat, this.currentLng,
                    this.officeLocation.latitude, this.officeLocation.longitude
                );
                this.inRadius = this.distance <= this.officeLocation.radius_meter;
            }
            this.updateMap();
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
            if (this.mapInitialized || !this.officeLocation || !this.$refs.mapContainer) return;

            // Tunggu Leaflet siap (CDN script mungkin belum selesai load
            // karena view di-render async oleh PineconeRouter)
            if (typeof L === 'undefined') {
                setTimeout(() => this.initMap(), 200);
                return;
            }

            this.mapInitialized = true;

            try {
                const ol = this.officeLocation;
                this.map = L.map(this.$refs.mapContainer, {
                    center: [ol.latitude, ol.longitude],
                    zoom: 16,
                    zoomControl: true,
                    attributionControl: true
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(this.map);

                // Ikon pesantren
                const officeIcon = L.divIcon({
                    html: '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><circle cx="14" cy="14" r="12" fill="#3BC0CF" stroke="#fff" stroke-width="2.5"/><path d="M14 8c-2.2 0-4 1.8-4 4 0 3 4 7 4 7s4-4 4-7c0-2.2-1.8-4-4-4zm0 5.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" fill="#fff"/></svg>',
                    className: '',
                    iconSize: [28, 28],
                    iconAnchor: [14, 28],
                    popupAnchor: [0, -32]
                });

                this.officeMarker = L.marker([ol.latitude, ol.longitude], { icon: officeIcon })
                    .addTo(this.map)
                    .bindPopup('<b>' + ol.name + '</b><br>Lokasi pesantren');

                // Lingkaran radius
                this.radiusCircle = L.circle([ol.latitude, ol.longitude], {
                    radius: ol.radius_meter,
                    color: '#3BC0CF',
                    fillColor: '#D4F0F3',
                    fillOpacity: 0.35,
                    weight: 2,
                    dashArray: '5, 8'
                }).addTo(this.map);

                setTimeout(() => this.map && this.map.invalidateSize(), 300);
            } catch (e) {
                console.error('Gagal menginisialisasi peta:', e);
                this.mapInitialized = false;
            }
        },

        updateMap() {
            if (!this.map || !this.mapInitialized) return;
            if (this.currentLat === null || this.currentLng === null) return;

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

            if (!this.map._userBoundsAdjusted) {
                const group = L.featureGroup([
                    this.officeMarker,
                    L.circle([this.officeLocation.latitude, this.officeLocation.longitude], { radius: this.officeLocation.radius_meter }),
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
            if (ts.checked_in && ts.checked_out) return false;
            // Check-out hanya bisa setelah 1 jam sejak check-in
            if (ts.checked_in && !ts.checked_out && this.checkInTimestamp) {
                const elapsed = (Date.now() - this.checkInTimestamp) / 1000;
                if (elapsed < 3600) return false;
            }
            return true;
        },

        get ctaLabel() {
            if (this.submitting) return 'Memproses...';
            const ts = this.todayStatus || {};
            if (ts.is_holiday) return 'Hari Libur';
            if (ts.is_day_off) return 'Bukan Hari Kerja';
            if (ts.checked_in && ts.checked_out) return 'Presensi Selesai';
            if (ts.checked_in && !ts.checked_out && this.checkInTimestamp) {
                const elapsed = (Date.now() - this.checkInTimestamp) / 1000;
                if (elapsed < 3600) {
                    const remaining = Math.ceil((3600 - elapsed) / 60);
                    return 'Tunggu ' + remaining + ' mnt';
                }
            }
            if (ts.checked_in) return 'Absen Pulang';
            return 'Absen Masuk';
        },

        async handleCta() {
            if (!this.ctaEnabled) return;
            if (this.todayStatus.checked_in) {
                await this.checkOut();
            } else {
                await this.checkIn();
            }
        },

        async checkIn() {
            this.submitting = true;
            try {
                const response = await axios.post(
                    base_url + 'member/checkin/checkIn',
                    {
                        latitude: this.currentLat,
                        longitude: this.currentLng,
                        accuracy: this.accuracy
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
                    this.checkInTimestamp = Date.now();
                    this.persistCheckInTimestamp();
                    this.showPopup('success',
                        'Absen Masuk Berhasil 🎉',
                        'Pukul ' + res.data.check_in_time + ' · ' + res.data.distance_meter + ' m dari lokasi',
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

        async checkOut() {
            this.submitting = true;
            try {
                const response = await axios.post(
                    base_url + 'member/checkin/checkOut',
                    {
                        latitude: this.currentLat,
                        longitude: this.currentLng,
                        accuracy: this.accuracy
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
                    this.todayStatus.checked_out = true;
                    this.todayStatus.check_out_time = res.data.check_out_time;
                    this.showPopup('success',
                        'Absen Pulang Berhasil 👋',
                        'Pukul ' + res.data.check_out_time + ' · ' + res.data.distance_meter + ' m dari lokasi',
                        'Sampai jumpa besok!');
                } else {
                    this.showToast('error', 'Absen pulang gagal', res.response_message || 'Silakan coba lagi.');
                }
            } catch (e) {
                const msg = e.response && e.response.data && e.response.data.response_message
                    ? e.response.data.response_message
                    : 'Terjadi kesalahan. Silakan coba lagi.';
                this.showToast('error', 'Absen pulang gagal', msg);
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
        },

        // Kembalikan timestamp check-in dari check_in_time (tahan refresh)
        restoreCheckInTimestamp() {
            if (!this.todayStatus.checked_in || this.todayStatus.checked_out) return;
            if (this.checkInTimestamp) return; // sudah ada

            // Coba ambil dari localStorage dulu
            const stored = localStorage.getItem('checkin_ts_' + this.todayStatus.check_in_time);
            if (stored) {
                this.checkInTimestamp = parseInt(stored, 10);
                return;
            }

            // Fallback: rekonstruksi dari check_in_time (HH:MM)
            if (this.todayStatus.check_in_time) {
                const parts = this.todayStatus.check_in_time.split(':');
                if (parts.length === 2) {
                    const now = new Date();
                    const d = new Date(now.getFullYear(), now.getMonth(), now.getDate(),
                        parseInt(parts[0], 10), parseInt(parts[1], 10), 0);
                    this.checkInTimestamp = d.getTime();
                }
            }
        },

        persistCheckInTimestamp() {
            if (this.checkInTimestamp && this.todayStatus.check_in_time) {
                try {
                    localStorage.setItem('checkin_ts_' + this.todayStatus.check_in_time,
                        String(this.checkInTimestamp));
                } catch (e) { /* ignore */ }
            }
        }
    }));
});
