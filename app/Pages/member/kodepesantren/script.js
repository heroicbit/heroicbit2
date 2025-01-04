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
            setTimeout(() => {
                localStorage.setItem('forcekodepesantren', 1)
                localStorage.setItem('pesantrenID', pesantrenID)
            }, 500);

            // Set pesantrenID to session
            window.location.replace('/member/kodepesantren/setPesantrenID/' + pesantrenID)
        },

        enableButton(){
            if(this.kode.trim() != '')
                this.buttonDisabled = false
            else
                this.buttonDisabled = true
        }
    }
}