// Page member/checkin/rekap
document.addEventListener('alpine:init', () => {
    Alpine.data('member_checkin_rekap', () => ({
        title: "Rekap Kehadiran",
        screen: 'daily',
        date: new Date(),
        daily: null,
        loadingDaily: true,
        dailyError: null,
        filter: 'semua',
        search: '',

        periods: [],
        periodId: null,
        selectedEmployeeId: null,
        detail: null,
        loadingDetail: true,
        detailError: null,
        calYear: null,
        calMonth: null,

        // Export rekap bulanan
        exportModal: false,
        exportMonth: null,
        exportYear: null,
        exporting: false,

        toast: { show: false, type: 'success', title: '', desc: '' },

        async init() {
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'rekap';
            Alpine.store('tarbiyya').showBottomMenu = false;

            await this.loadPeriods();
            this.loadDaily();
        },

        goToCheckin() {
            window.PineconeRouter.context.navigate('/checkin');
        },

        // ---------- Daily recap ----------
        get isoDate() {
            const y = this.date.getFullYear();
            const m = String(this.date.getMonth() + 1).padStart(2, '0');
            const d = String(this.date.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + d;
        },
        get dateLabel() {
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][this.date.getDay()];
            const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][this.date.getMonth()];
            return hari + ', ' + this.date.getDate() + ' ' + bulan + ' ' + this.date.getFullYear();
        },
        get isToday() { return this.date.toDateString() === new Date().toDateString(); },

        changeDay(n) { const d = new Date(this.date); d.setDate(d.getDate() + n); this.date = d; this.loadDaily(); },
        setDate(v) { if (!v) return; this.date = new Date(v + 'T00:00:00'); this.loadDaily(); },
        goToday() { this.date = new Date(); this.loadDaily(); },

        async loadDaily() {
            this.loadingDaily = true;
            this.dailyError = null;
            try {
                const data = await fetchPageData('member/checkin/rekap/supply?date=' + this.isoDate, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    }
                });
                if (data && data.response_code === 200 && data.data) {
                    this.daily = data.data;
                } else if (data && data.response_code === 403) {
                    this.dailyError = data.response_message || 'Akses ditolak.';
                } else {
                    this.dailyError = 'Gagal memuat data rekap.';
                }
            } catch (e) {
                console.error('Gagal memuat data rekap harian:', e);
                this.dailyError = e.message || 'Terjadi kesalahan jaringan.';
            } finally {
                this.loadingDaily = false;
            }
        },

        statusLabel(e) {
            if (e.status === 'hadir') {
                // Satu jadwal (atau tanpa jadwal unit) = cukup "Hadir" tanpa persentase.
                return (e.total_schedules || 0) <= 1 ? 'Hadir' : 'Hadir 100%';
            }
            if (e.status === 'parsial') return 'Hadir ' + (e.percent ?? 0) + '%';
            if (e.status === 'tidak_hadir') return 'Tidak Hadir';
            if (e.status === 'libur') return 'Libur';
            if (e.status === 'bukan_hari_kerja') return 'Bukan Hari Kerja';
            return e.status || '';
        },

        // Label cakupan check-in: "1/2 jadwal · 08:00" (untuk jadwal ganda)
        coverageLabel(e) {
            if (e.status === 'libur' || e.status === 'bukan_hari_kerja') return '';
            if ((e.total_schedules || 0) > 1) {
                const c = e.check_in_count + '/' + e.total_schedules + ' jadwal';
                return e.check_in_time ? c + ' · ' + e.check_in_time : c;
            }
            return e.check_in_time || '';
        },

        initials(name) {
            return (name || '').split(' ').filter(w => w.length > 1).slice(0, 2).map(w => w[0]).join('').toUpperCase();
        },

        filteredEmployees() {
            if (!this.daily) return [];
            let list = this.daily.employees;
            if (this.filter === 'libur') {
                list = list.filter(e => e.status === 'libur' || e.status === 'bukan_hari_kerja');
            } else if (this.filter !== 'semua') {
                list = list.filter(e => e.status === this.filter);
            }
            if (this.search.trim()) {
                const q = this.search.trim().toLowerCase();
                list = list.filter(e => e.name.toLowerCase().includes(q));
            }
            return list;
        },

        // ---------- Export rekap bulanan ----------
        get monthNames() {
            return ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        },
        get exportYears() {
            const y = new Date().getFullYear();
            return [y, y - 1];
        },
        get exportMonthLabel() {
            return this.monthNames[(this.exportMonth || 1) - 1].toLowerCase();
        },

        openExportModal() {
            const now = new Date();
            this.exportMonth = now.getMonth() + 1;
            this.exportYear = now.getFullYear();
            this.exportModal = true;
        },

        async downloadExport() {
            if (!this.exportMonth || !this.exportYear || this.exporting) return;
            this.exporting = true;
            try {
                const month = String(this.exportMonth).padStart(2, '0');
                const url = base_url + 'member/checkin/rekap/export?month=' + month + '&year=' + this.exportYear + '&dataonly=1';
                const resp = await axios.get(url, {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    },
                    responseType: 'blob'
                });

                // Nama file dari header Content-Disposition, fallback ke format default
                let filename = 'rekap-checkin-' + this.exportMonthLabel + '-' + this.exportYear + '.xls';
                const cd = resp.headers['content-disposition'];
                if (cd) {
                    const match = cd.match(/filename="?([^";]+)"?/i);
                    if (match && match[1]) filename = match[1].trim();
                }

                const blobUrl = window.URL.createObjectURL(resp.data);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(blobUrl);

                this.exportModal = false;
                this.showToast('success', 'Rekap berhasil diunduh', filename);
            } catch (e) {
                console.error('Gagal mengunduh rekap bulanan:', e);
                this.showToast('error', 'Gagal mengunduh', 'Terjadi kesalahan saat mengunduh data. Coba lagi.');
            } finally {
                this.exporting = false;
            }
        },

        // ---------- Detail employee ----------
        openDetail(id) {
            this.screen = 'detail';
            this.selectedEmployeeId = id;
            const now = new Date();
            this.calYear = now.getFullYear();
            this.calMonth = now.getMonth();
            this.loadDetail();
        },
        goBackToList() { this.screen = 'daily'; },

        async loadPeriods() {
            try {
                const data = await fetchPageData('member/checkin/rekap/periods', {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    }
                });
                if (data && data.response_code === 200 && data.data) {
                    this.periods = data.data;
                    const active = this.periods.find(p => p.is_active == 1);
                    this.periodId = active ? active.id : (this.periods[0] ? this.periods[0].id : null);
                }
            } catch (e) {
                console.error('Gagal memuat daftar periode:', e);
                this.showToast('error', 'Gagal memuat daftar periode', e.message);
            }
        },

        async loadDetail() {
            if (!this.selectedEmployeeId || !this.periodId) return;
            this.loadingDetail = true;
            this.detailError = null;
            try {
                const qs = '?period_id=' + this.periodId + '&year=' + this.calYear + '&month=' + (this.calMonth + 1);
                const data = await fetchPageData('member/checkin/rekap/detail/' + this.selectedEmployeeId + qs, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    }
                });
                if (data && data.response_code === 200 && data.data) {
                    this.detail = data.data;
                } else {
                    this.detailError = data.response_message || 'Gagal memuat detail.';
                }
            } catch (e) {
                console.error('Gagal memuat detail presensi:', e);
                this.detailError = e.message || 'Terjadi kesalahan jaringan.';
            } finally {
                this.loadingDetail = false;
            }
        },

        get monthLabel() {
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            return bulan[this.calMonth] + ' ' + this.calYear;
        },
        changeMonth(n) {
            let m = this.calMonth + n, y = this.calYear;
            if (m < 0) { m = 11; y--; } else if (m > 11) { m = 0; y++; }
            this.calMonth = m; this.calYear = y;
            this.loadDetail();
        },

        calendarCells() {
            if (!this.detail) return [];
            const y = this.calYear, m = this.calMonth;
            const firstDow = new Date(y, m, 1).getDay();
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const byDate = {};
            this.detail.calendar.forEach(c => { byDate[c.date] = c.status; });
            const cells = [];
            for (let i = 0; i < firstDow; i++) cells.push({ key: 'e' + i, day: '', status: 'empty' });
            for (let d = 1; d <= daysInMonth; d++) {
                const mm = String(m + 1).padStart(2, '0');
                const dd = String(d).padStart(2, '0');
                const iso = y + '-' + mm + '-' + dd;
                cells.push({ key: 'd' + d, day: d, status: byDate[iso] || 'upcoming' });
            }
            return cells;
        },

        absenceNotes() {
            if (!this.detail) return [];
            return this.detail.calendar.filter(c => c.status === 'tidak_hadir' || c.status === 'parsial');
        },

        absenceNoteTitle(status) {
            if (status === 'tidak_hadir') return 'Tidak hadir';
            if (status === 'parsial') return 'Hadir sebagian';
            return 'Terlambat';
        },

        absenceNoteDesc(n) {
            if (n.status === 'parsial' && n.total_schedules > 0) {
                return 'Hanya check-in ' + n.check_in_count + ' dari ' + n.total_schedules + ' jadwal (' + (n.percent ?? 0) + '%) · ' + this.formatDateLong(n.date);
            }
            return this.formatDateLong(n.date);
        },

        formatDateLong(iso) {
            const d = new Date(iso + 'T00:00:00');
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
        },

        showToast(type, title, desc) {
            this.toast = { show: true, type, title, desc: desc || '' };
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => { this.toast.show = false; }, 3800);
        }
    }));
});
