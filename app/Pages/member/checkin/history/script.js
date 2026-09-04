// Page member/checkin/history
document.addEventListener('alpine:init', () => {
    Alpine.data('member_checkin_history', () => ({
        title: 'Riwayat Presensi',

        employee: { name: '', unit: '', role: '' },
        employeeNotFound: false,

        // Filter rentang riwayat
        range: 'this_month',     // 'this_month' | 'last_month' | 'custom'
        customStart: '',
        customEnd: '',
        rangeInfo: { start_date: '', end_date: '' },

        loading: true,
        errorMsg: null,
        days: [],
        summary: {
            total_days: 0,
            work_days: 0,
            hadir: 0,
            parsial: 0,
            tidak_hadir: 0,
            libur: 0,
            bukan_hari_kerja: 0,
            percent: 0
        },

        toast: { show: false, type: 'success', title: '', desc: '' },

        // Modal detail hari (dibuka saat baris hari diklik)
        modal: { show: false, day: null },

        get todayISO() {
            return this.toISO(new Date());
        },

        // ------------------------------------------------------------
        // INIT
        // ------------------------------------------------------------
        async init() {
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'history';
            Alpine.store('tarbiyya').showBottomMenu = false;

            // Default custom range = bulan berjalan (1 s.d. hari ini)
            const end = new Date();
            const start = new Date(end.getFullYear(), end.getMonth(), 1);
            this.customEnd = this.toISO(end);
            this.customStart = this.toISO(start);

            await this.loadHistory();
        },

        goCheckin() {
            window.PineconeRouter.context.navigate('/checkin');
        },

        openDay(day) {
            this.modal.day = day;
            this.modal.show = true;
        },

        closeModal() {
            this.modal.show = false;
            this.modal.day = null;
        },

        // Label rentang waktu sebuah jadwal unit, mis. "06:00 – 09:00"
        schedWindow(s) {
            const tIn = s.time_in ? String(s.time_in).slice(0, 5) : null;
            const tOut = s.time_out ? String(s.time_out).slice(0, 5) : null;
            if (tIn === null) return 'setiap waktu';
            if (tOut !== null) return tIn + ' – ' + tOut;
            return 'mulai ' + tIn;
        },

        toISO(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + day;
        },

        // ------------------------------------------------------------
        // FILTER RENTANG
        // ------------------------------------------------------------
        setRange(r) {
            this.range = r;
            if (r !== 'custom') this.loadHistory();
        },

        applyCustom() {
            if (!this.customStart || !this.customEnd) {
                this.showToast('error', 'Rentang belum lengkap', 'Pilih tanggal mulai dan tanggal akhir terlebih dahulu.');
                return;
            }
            if (this.customStart > this.customEnd) {
                this.showToast('error', 'Rentang tidak valid', 'Tanggal mulai tidak boleh melewati tanggal akhir.');
                return;
            }
            if (this.customEnd > this.todayISO) {
                this.customEnd = this.todayISO;
            }
            this.loadHistory();
        },

        async loadHistory() {
            // Urutan request: hanya respons terbaru yang dipakai agar tidak
            // tertimpa respons lama saat pengguna cepat mengganti rentang.
            const seq = ++this._loadSeq || (this._loadSeq = 1);
            this.loading = true;
            this.errorMsg = null;
            try {
                let qs = 'range=' + this.range;
                if (this.range === 'custom') {
                    qs += '&start_date=' + this.customStart + '&end_date=' + this.customEnd;
                }

                const data = await fetchPageData('member/checkin/history/supply?' + qs);
                if (seq !== this._loadSeq) return; // respons lama diabaikan

                if (data && data.response_code === 200 && data.data) {
                    this.days = data.data.days || [];
                    this.summary = data.data.summary || this.summary;
                    this.rangeInfo = data.data.range || this.rangeInfo;
                    this.employee = data.data.employee || this.employee;
                } else if (data && data.response_code === 404) {
                    this.employeeNotFound = true;
                    this.days = [];
                } else {
                    this.errorMsg = (data && data.response_message) || 'Gagal memuat riwayat presensi.';
                }
            } catch (e) {
                if (seq !== this._loadSeq) return;
                console.error('Gagal memuat riwayat presensi:', e);
                this.errorMsg = e.message || 'Terjadi kesalahan jaringan.';
            } finally {
                if (seq === this._loadSeq) this.loading = false;
            }
        },

        // ------------------------------------------------------------
        // COMPUTED & HELPERS
        // ------------------------------------------------------------
        get rangeLabel() {
            const s = this.formatFullDate(this.rangeInfo.start_date);
            const e = this.formatFullDate(this.rangeInfo.end_date);
            return (s && e) ? s + ' – ' + e : '';
        },

        formatFullDate(iso) {
            if (!iso) return '';
            const d = new Date(iso + 'T00:00:00');
            if (isNaN(d.getTime())) return '';
            const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][d.getMonth()];
            return d.getDate() + ' ' + bulan + ' ' + d.getFullYear();
        },

        // Label tanggal di baris riwayat, mis. "Kamis, 4 Sep"
        dateTitle(isoDate) {
            const d = new Date(isoDate + 'T00:00:00');
            const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][d.getDay()];
            const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][d.getMonth()];
            return hari + ', ' + d.getDate() + ' ' + bulan;
        },

        // Label status hari (hadir / hadir sebagian / tidak hadir)
        statusLabel(status) {
            const labels = {
                'hadir': 'Hadir',
                'parsial': 'Sebagian',
                'tidak_hadir': 'Tidak Hadir',
                'libur': 'Libur',
                'bukan_hari_kerja': 'Bukan Hari Kerja'
            };
            return labels[status] || status;
        },

        showToast(type, title, desc) {
            this.toast = { show: true, type, title, desc: desc || '' };
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => { this.toast.show = false; }, 3800);
        }
    }));
});
