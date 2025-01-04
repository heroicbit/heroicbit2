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
        forceKodePesantren(pesantrenID){
            localStorage.setItem('forcekodepesantren', 1)
            localStorage.setItem('pesantrenID', pesantrenID)

            // Set pesantrenID to session
            setTimeout(() => {
                window.location.replace('/member/kodepesantren/setPesantrenID/' + pesantrenID)
            }, 500);
        },

        choosePesantren() {
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
              .then((response) => {
                if (response.data.found == 1) {
                  localStorage.setItem("pesantrenID", response.data.pesantrenID);
                  Alpine.store('tarbiyya').pesantrenID = response.data.pesantrenID;
                  window.location.replace("/member/kodepesantren/setPesantrenID/" + response.data.pesantrenID);
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