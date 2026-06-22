// Page member/santri
window.member_santri = function(){
    return {
        title: "Daftar Santri",
        data: [],
        searchNIS: null,
        NISFound: {
            found: null,
            token: null,
            nama_santri: null,
            class_name: null
        },

        init(){
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'santri'
            Alpine.store('tarbiyya').showBottomMenu = true

            if(cachePageData['member/santri']){
                this.data = cachePageData['member/santri']
            } else {   
                fetchPageData('member/santri/supply', 
                    { headers: {
                        'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
                    } })
                .then(data => {
                    cachePageData['member/santri'] = data
                    this.data = data
                })
            }
        },

        getTodayPresensi(santriIndex) {
            let santri = this.data.santri[santriIndex]
            let presensi = '<span class="text-secondary">Presensi belum dicek</span>';
            if(this.data.isLibur) 
                presensi =`<span class="text-secondary">${this.data.isLibur} libur</span>`;
            else {
                if(santri.presensi_hadir == '1') presensi = '<span class="text-secondary">Presensi hari ini: </span><span class="text-success">Hadir</span>'
                else if(santri.presensi_sakit == '1') presensi = '<span class="text-secondary">Presensi hari ini: </span><span class="text-warning">Sakit</span>'
                else if(santri.presensi_izin == '1') presensi = '<span class="text-secondary">Presensi hari ini: </span><span class="text-info">Izin</span>'
                else if(santri.presensi_alpa == '1') presensi = '<span class="text-secondary">Presensi hari ini: </span><span class="text-danger">Alpa</span>'
            }
            return presensi
        },

        checkNIS(){
            if(this.searchNIS) {
                fetchPageData('member/santri/checkNIS/' + this.searchNIS, 
                    { headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID} 
                    })
                .then(data => {
                    if(data.found != 1){
                        this.NISFound.found = 0
                    } else {
                        this.NISFound = data
                        this.NISFound.found = 1
                    }
                })
            }
        },

        addSantri(){
            postPageData('/member/santri', 
                { token: this.NISFound.token },
                { headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID} 
                })
            .then(data => {
                if(data.status == 'success'){
                    this.data.santri.push(data.santri)
                    this.searchNIS = null
                    this.NISFound = {
                        found: null,
                        token: null,
                        nama_santri: null,
                        class_name: null
                    }
                    const addSantriModal = bootstrap.Modal.getInstance(Array.from(window.modalElements).find(modal => modal.id === 'addSantriModal'));
                    addSantriModal.hide();
                }
            })
        }
    }
}
