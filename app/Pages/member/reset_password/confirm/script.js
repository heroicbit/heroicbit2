// Page member/component
window.member_reset_password_confirm = function(){
    return {
        title: "Reset Password",
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
            Alpine.store('tarbiyya').currentPage = 'register_confirm'
            Alpine.store('tarbiyya').showBottomMenu = false
            this.data.logo = Alpine.store('tarbiyya').tarbiyyaSetting.auth_logo

            
        },

    }
}