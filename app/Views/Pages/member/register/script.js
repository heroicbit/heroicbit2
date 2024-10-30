// Page member/component
window.member_register = function(){
    return {
        title: "Register",
        showPwd: false,
        data: {
            name: '',
            email: '',
            whatsapp: '',
            password: '',
            repeat_password: '',
            otp: '',
        },
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'register'
            Alpine.store('member').showBottomMenu = false

            if(cachePageData['member/login']){
                this.data = cachePageData['member/login']
              } else {   
                fetchPageData('pages/member/login', {
                  headers: {
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                  }
                }).then(data => {
                  cachePageData['member/login'] = data
                  this.data = data
                })
              }
        },
        register(){
            // Gain javascript form validation
            // if(this.data.name == '' || this.data.email == '' || this.data.whatsapp == '' || this.data.password == '' || this.data.repeat_password == ''){
            //     toastr.warning('Form harus diisi semua')
            //     return;
            // }

            // Check login using axios post
            const formData = new FormData();
            formData.append('fullname', this.data.name);
            formData.append('email', this.data.email);
            formData.append('whatsapp', this.data.whatsapp);
            formData.append('password', this.data.password);
            formData.append('repeat_password', this.data.repeat_password);
            axios.post('/member/register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(response => {
                if(response.data.success == 1){
                    localStorage.setItem('token', response.data.jwt)
                    window.PineconeRouter.context.navigate('/')
                } else {
                    toastr.warning(response.data.message)
                }
            })
        }
    }
}