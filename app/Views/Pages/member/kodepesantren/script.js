// Page member/component
window.member_kodepesantren = function(){
    return {
        title: "Kode Pesantren",
        buttonDisabled: true,
        kode: null,
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'kodepesantren'
            Alpine.store('member').showBottomMenu = false
        },
        checkKodePesantren(){
            // CHeck Kode Pesantren via ajax using axios
            const formData = new FormData();
            formData.append('kodepesantren', this.kode);
            axios.post('/pages/member/kodepesantren', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if(response.data.found == 1){
                    // Save kodepesantren to localstorage
                    localStorage.setItem('kodepesantren', response.data.pesantrenID)
                    Alpine.store('member').kodePesantren = localStorage.getItem('kodepesantren')

                    // Save kodepesantren to cookie without cookie expire
                    setCookie('kodepesantren', response.data.pesantrenID, 1000);

                    window.PineconeRouter.context.navigate('/login')
                } else {
                    // Kode pesantren tidak tersedia
                    toastr.warning('Kode pesantren tidak tersedia')
                }
            })
        },

        forceKodePesantren(pesantrenID){
            localStorage.setItem('forcekodepesantren', 1)
            localStorage.setItem('kodepesantren', pesantrenID)
            Alpine.store('member').kodePesantren = localStorage.getItem('kodepesantren')
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