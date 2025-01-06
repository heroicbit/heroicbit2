// Page member/component
window.member_reset_password = function(){
    return {
        title: "Reset Kata Sandi",
        logo: '',
        phone: '',
        recaptcha: '',
        recaptchaWidget: null,
        error: '',
        sending: false,

        init(){
            document.title = this.title
            Alpine.store('tarbiyya').currentPage = 'reset_password'
            Alpine.store('tarbiyya').showBottomMenu = false
            this.logo = Alpine.store('tarbiyya').tarbiyyaSetting.auth_logo

            // Call google recaptcha
            this.recaptchaWidget = grecaptcha.render('grecaptcha', {
                'sitekey' : Alpine.store('tarbiyya').tarbiyyaSetting.recaptcha_site_key,
            });
        },

        confirm(){
            this.sending = true

            // Gain javascript form validation
            if(this.phone == ''){
                this.error = 'Nomor WhatsApp tidak boleh kosong.'
                this.sending = false
                return;
            }
            
            this.recaptcha = grecaptcha.getResponse(this.recaptchaWidget);
            if(this.recaptcha == '') {
                this.error = 'Ceklis dulu Recaptcha.'
                this.sending = false
                return;
            }

            // Check register_confirm using axios post
            const formData = new FormData();
            formData.append('phone', this.phone);
            formData.append('recaptcha', this.recaptcha);
            axios.post('/member/reset_password', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                }
            }).then(response => {
                window.console.log(response)
                if(response.data.success == 1){
                    window.PineconeRouter.context.redirect('/reset_password/confirm/' + response.data.token)
                } else {
                    this.error = response.data.message
                    grecaptcha.reset(this.recaptchaWidget)
                    this.sending = false
                }
            })
        },
    }
}

