// Page member/component
window.member_kodepesantren = function(){
    return {
        title: "Kode Pesantren",
        buttonDisabled: true,
        allcodes: [],
        kode: null,
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title
            Alpine.store('member').currentPage = 'kodepesantren'
            Alpine.store('member').showBottomMenu = false

            axios.get('/api/member/kodepesantren')
            .then(response => {
                if(response.data.length > 1){
                    this.allcodes = response.data
                } else {
                    // Kode pesantren tidak tersedia
                    toastr.warning('Belum ada data pesantren')
                }
            })
        },
        checkKodePesantren(){
            // CHeck Kode Pesantren via ajax using axios
            const formData = new FormData();
            formData.append('kodepesantren', this.kode);
            axios.post('/api/member/kodepesantren', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if(response.data.found == 1){
                    // Save kodepesantren to localstorage
                    localStorage.setItem('kodepesantren', response.data.pesantrenID)
                    Alpine.store('member').kodePesantren = getCookie("kodepesantren")

                    // Save kodepesantren to cookie without cookie expire
                    setCookie('kodepesantren', response.data.pesantrenID, 1000);

                    window.PineconeRouter.context.redirect('/login')
                } else {
                    // Kode pesantren tidak tersedia
                    toastr.warning('Kode pesantren tidak tersedia')
                }
            })
        },

        forceKodePesantren(pesantrenID){
            localStorage.setItem('forcekodepesantren', 1)
            this.registerKodePesantren(pesantrenID)
        },
        
        registerKodePesantren(pesantrenID){
            Alpine.store('member').kodePesantren = getCookie("kodepesantren")
            window.PineconeRouter.context.navigate('/login')
        },

        enableButton(){
            if(this.kode.trim() != '')
                this.buttonDisabled = false
            else
                this.buttonDisabled = true
        }
    }
}