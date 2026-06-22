// Page member/uangsaku/detail
window.member_uangsaku_detail = function(nis) {
    return {
        title: "Uang Saku",
        nis: nis,
        santri: null,
        saldoData: null,
        historyData: [],
        loadingSaldo: false,
        loadingHistory: false,
        errorSaldo: null,
        errorHistory: null,

        init() {
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'uangsaku';
            Alpine.store('tarbiyya').showBottomMenu = true;

            // Load student info from supply
            fetchPageData('member/uangsaku/detail/supply/' + this.nis, {
                headers: {
                    'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                    'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
                }
            })
            .then(data => {
                if (data.found == 1) {
                    this.santri = data.santri;
                    document.title = 'Uang Saku - ' + this.santri.nama_santri;
                    this.loadSaldo();
                    this.loadHistory();
                }
            });
        },

        async loadSaldo() {
            const cacheKey = 'uangsaku/saldo/' + this.nis;

            if (cachePageData[cacheKey]) {
                this.saldoData = cachePageData[cacheKey];
                return;
            }

            this.loadingSaldo = true;
            this.errorSaldo = null;

            try {
                const response = await axios.get('https://api.persisbenda.my.id/simbenda/uangsaku/saldo', {
                    params: { nis: this.nis },
                    headers: { 'x-pb-api-key': 'Alh4mDuLiLLaH!' }
                });

                if (response.data.success) {
                    this.saldoData = response.data.data;
                    cachePageData[cacheKey] = response.data.data;
                } else {
                    this.errorSaldo = response.data.message || 'Gagal memuat saldo';
                }
            } catch (err) {
                this.errorSaldo = err.response?.data?.message || err.message || 'Gagal terhubung ke server';
            } finally {
                this.loadingSaldo = false;
            }
        },

        async loadHistory() {
            const cacheKey = 'uangsaku/history/' + this.nis;

            if (cachePageData[cacheKey]) {
                this.historyData = cachePageData[cacheKey];
                return;
            }

            this.loadingHistory = true;
            this.errorHistory = null;

            try {
                const response = await axios.get('https://api.persisbenda.my.id/simbenda/uangsaku/history', {
                    params: { nis: this.nis },
                    headers: { 'x-pb-api-key': 'Alh4mDuLiLLaH!' }
                });

                if (response.data.success) {
                    this.historyData = response.data.data.transaksi || [];
                    cachePageData[cacheKey] = this.historyData;
                } else {
                    this.errorHistory = response.data.message || 'Gagal memuat riwayat';
                }
            } catch (err) {
                this.errorHistory = err.response?.data?.message || err.message || 'Gagal terhubung ke server';
            } finally {
                this.loadingHistory = false;
            }
        },

        formatRupiah(value) {
            if (value === null || value === undefined) return 'Rp0';
            return 'Rp' + Number(value).toLocaleString('id-ID');
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return d.toLocaleDateString('id-ID', options);
        },

        formatTime(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        },

        getUniqueDates() {
            const dates = [...new Set(this.historyData.map(t => t.tgl?.split(' ')[0]))];
            dates.sort((a, b) => new Date(b) - new Date(a));
            return dates;
        },

        getTransactionsByDate(date) {
            return this.historyData.filter(t => t.tgl?.startsWith(date));
        },

        formatDateGroup(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr + 'T00:00:00');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return d.toLocaleDateString('id-ID', options);
        }
    }
}