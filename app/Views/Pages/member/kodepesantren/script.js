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
            axios.post('/member/kodepesantren', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if(response.data.found == 1){
                    // Kode pesantren tersedia
                    localStorage.setItem('kodepesantren', response.data.pesantrenID)
                    window.PineconeRouter.context.navigate('/login')
                } else {
                    // Kode pesantren tidak tersedia
                    toastr.warning('Kode pesantren tidak tersedia')
                }
            })
        },
        enableButton(){
            if(this.kode.trim() != '')
                this.buttonDisabled = false
            else
                this.buttonDisabled = true
        }
    }
}