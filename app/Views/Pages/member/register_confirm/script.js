// Page member/component
window.member_register_confirm = function(tokens){
    return {
        title: "Konfirmasi Registrasi",
        data: {
            logo: '',
            id: '',
            token: '',
            otp: '',
        },
        resending: false,
        error: '',
        remainingTime: 30, // Set durasi awal (60 detik)
        interval: null, // Untuk menyimpan ID interval

        get formattedTime() {
            const minutes = Math.floor(this.remainingTime / 60);
            const seconds = this.remainingTime % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        },

        startCountdown() {
            this.interval = setInterval(() => {
                if (this.remainingTime > 0) {
                    this.remainingTime -= 1;
                } else {
                    clearInterval(this.interval); // Hentikan countdown
                }
            }, 1000);
        },
        
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'register_confirm'
            Alpine.store('member').showBottomMenu = false
            this.data.logo = Alpine.store('member').tarbiyyaSetting.auth_logo

            const tokenRegex = /^[a-f0-9]+_[0-9]+X.+$/;
            if (!tokenRegex.test(tokens)) {
                window.PineconeRouter.context.navigate('/member/register')
            }

            const [part1, rest] = tokens.split('_');  // Bagian pertama sebelum _ adalah token
            const [part2, part3] = rest.split('X'); // Bagian kedua antara _ dan X, dan bagian ketiga
            this.data.token = part1
            this.data.id = part2

            this.startCountdown();

            if(cachePageData['member/register_confirm']){
                this.data = cachePageData['member/register_confirm']
              } else {   
                fetchPageData('pages/member/register_confirm', {
                  headers: {
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                  }
                }).then(data => {
                  cachePageData['member/register_confirm'] = data
                  this.data.logo = data.logo
                })
              }
        },

        confirm(){
            // Gain javascript form validation
            if(this.data.otp == ''){
                this.error = 'Kode Registrasi tidak boleh kosong.'
                return;
            }

            // Check register_confirm using axios post
            const formData = new FormData();
            formData.append('id', this.data.id ?? '');
            formData.append('token', this.data.token ?? '');
            formData.append('otp', this.data.otp ?? '');
            axios.post('/pages/member/register_confirm', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(response => {
                if(response.data.success == 1){
                    localStorage.setItem('heroic_token', response.data.jwt)
                    setTimeout(() => {
                        Alpine.store('member').sessionToken = localStorage.getItem('heroic_token')
                        window.PineconeRouter.context.navigate('/')
                    })
                } else {
                    this.error = response.data.message
                }
            })
        },
    
        resendOTP() {
            this.resending = true

            // Check resend_otp using axios post
            const formData = new FormData();
            formData.append('id', this.data.id ?? '');
            formData.append('token', this.data.token ?? '');
            axios.post('/pages/member/register_confirm?m=resend', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(response => {
                if(response.data.success == 1){
                    let token = response.data.token + '_' + response.data.id + 'X' + Math.random().toString(36).substring(7)
                    window.PineconeRouter.context.navigate('/member/register/' + token)
                } else {
                    this.error = response.data.message
                    this.resending = false
                }
            })
        }
    }
}