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

            axios.get('/member/kodepesantren/supply')
            .then(response => {
                if(response.data.length > 1){
                    this.allcodes = response.data
                } else {
                    // Kode pesantren tidak tersedia
                    toastr.warning('Belum ada data pesantren')
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