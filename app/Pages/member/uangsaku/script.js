// Page member/uangsaku
window.member_uangsaku = function() {
    return {
        title: "Smartcard",
        data: [],
        saldoList: {},
        loadingSaldoList: false,
        cardGradients: [
            'linear-gradient(135deg, #3BC0CF 0%, #1565C0 100%)',
            'linear-gradient(135deg, #1A8C9B 0%, #1e74fd 100%)',
            'linear-gradient(135deg, #157CA1 0%, #0D4B8A 100%)',
            'linear-gradient(135deg, #0d7a8a 0%, #1a4fd4 100%)',
        ],

        init() {
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'uangsaku';
            Alpine.store('tarbiyya').showBottomMenu = true;

            if (cachePageData['member/uangsaku']) {
                this.data = cachePageData['member/uangsaku'];
                this.loadAllSaldo();
            } else {
                fetchPageData('member/uangsaku/supply', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
                    }
                })
                .then(data => {
                    cachePageData['member/uangsaku'] = data;
                    this.data = data;
                    this.loadAllSaldo();
                });
            }
        },

        async loadAllSaldo() {
            if (!this.data.santri || this.data.santri.length === 0) return;
            this.loadingSaldoList = true;

            const promises = this.data.santri.map(santri => {
                const cacheKey = 'uangsaku/saldo/' + santri.nis;

                if (cachePageData[cacheKey]) {
                    this.saldoList[santri.nis] = cachePageData[cacheKey];
                    return Promise.resolve();
                }

                return axios.get('https://api.persisbenda.my.id/simbenda/uangsaku/saldo', {
                    params: { nis: santri.nis },
                    headers: { 'x-pb-api-key': 'Alh4mDuLiLLaH!' }
                })
                .then(res => {
                    if (res.data.success) {
                        const data = res.data.data;
                        cachePageData[cacheKey] = data;
                        this.saldoList[santri.nis] = data;
                    }
                })
                .catch(() => {
                    this.saldoList[santri.nis] = null;
                });
            });

            await Promise.all(promises);
            this.loadingSaldoList = false;
        },

        bukaDetail(index) {
            const santri = this.data.santri[index];
            window.PineconeRouter.context.navigate(`/uangsaku/${santri.nis}`);
        },

        formatRupiah(value) {
            if (value === null || value === undefined) return 'Rp0';
            return 'Rp' + Number(value).toLocaleString('id-ID');
        }
    }
}
