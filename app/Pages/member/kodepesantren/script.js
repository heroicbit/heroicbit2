// Page member/component
window.member_kodepesantren = function(){
    return {
        title: "Kode Pesantren",
        buttonDisabled: true,
        buttonSubmitting: false,
        allcodes: [],
        kode: null,
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            // Reset site settings
            Alpine.store('tarbiyya').tarbiyyaSetting = {} 

            document.title = this.title
            Alpine.store('tarbiyya').currentPage = 'kodepesantren'
            Alpine.store('tarbiyya').showBottomMenu = false

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

        // Called when domain bring kodepesantren itself
        async forceKodePesantren(pesantrenID){
            localStorage.setItem('forcekodepesantren', 1)
            localStorage.setItem('pesantrenID', pesantrenID)
            Alpine.store('tarbiyya').pesantrenID = pesantrenID;
            await Alpine.store('tarbiyya').getSiteSettings(Alpine.store('tarbiyya').pesantrenID)
            window.PineconeRouter.context.redirect('/login')
        },

        async choosePesantren() {
            this.buttonSubmitting = true
            this.buttonDisabled = true
            
            // Check login using axios post
            const formData = new FormData();
            formData.append("kodepesantren", this.kode);
            axios
              .post("/member/kodepesantren", formData, {
                headers: {
                  "Content-Type": "multipart/form-data",
                },
              })
              .then(async (response) => {
                if (response.data.found == 1) {
                  localStorage.setItem("pesantrenID", response.data.pesantrenID);
                  Alpine.store('tarbiyya').pesantrenID = response.data.pesantrenID;
                  await Alpine.store('tarbiyya').getSiteSettings(localStorage.getItem("pesantrenID"))
                  window.PineconeRouter.context.redirect('/login')
                } else {
                    window.console.log('Kode pesantren tidak ditemukan')
                }
              });
          },

        enableButton(){
            if(this.kode.trim() != '')
                this.buttonDisabled = false
            else
                this.buttonDisabled = true
        }
    }
}