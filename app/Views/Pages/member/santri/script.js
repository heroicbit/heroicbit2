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
        detailSantri: {
            nama_santri: null,
            nis: null,
            nik_santri: null,
            nisn: null,
            tempat_lahir_santri: null,
            jenis_kelamin: null,
            status_keluarga: null,
            anak_ke: null,
            jumlah_saudara_kandung: null,
            jumlah_saudara_tiri: null,
            jumlah_saudara_angkat: null,
            hobi: null,
            cita: null,
            asal_sekolah: null,
            npsn_sekolah: null,
            alamat_sekolah: null,
            nama_ayah: null,
            nik_ayah: null,
            tempat_lahir_ayah: null,
            kontak_ayah: null,
            pekerjaan_ayah: null,
            pendidikan_ayah: null,
            nama_ibu: null,
            nik_ibu: null,
            tempat_lahir_ibu: null,
            kontak_ibu: null,
            pekerjaan_ibu: null,
            pendidikan_ibu: null,
            penghasilan: null,
            alamat: null,
            rtrw: null,
            desa: null,
            kecamatan: null,
            kota: null,
            provinsi: null,
            kodepos: null,
            tahun_masuk: null,
            iuran_bulanan: null,
        },

        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'santri'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/santri']){
                this.data = cachePageData['member/santri']
            } else {   
                fetchPageData('member/santri', 
                    { headers: {
                        'Authorization': `Bearer ` + Alpine.store('member').sessionToken,
                        'Pesantrenku-ID': Alpine.store('member').kodePesantren,
                    } })
                .then(data => {
                    cachePageData['member/santri'] = data
                    this.data = data
                })
            }
        },

        showDetail(index){
            this.detailSantri = this.data.santri[index]
        },

        formatDate(dateString){
            if(dateString && dateString != '0000-00-00'){
                const date = new Date(dateString);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Intl.DateTimeFormat('id-ID', options).format(date);
            }
            return '';
        },

        checkNIS(){
            if(this.searchNIS) {
                fetchPageData('member/santri/?m=checkNIS&nis=' + this.searchNIS, 
                    { headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')} 
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
            postPageData('member/santri', 
                { token: this.NISFound.token },
                { headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')} 
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
