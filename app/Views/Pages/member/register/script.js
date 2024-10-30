// Page member/component
window.member_register = function(){
    return {
        title: "Register",
        showPwd: false,
        data: {
            fullname: '',
            email: '',
            whatsapp: '',
            password: '',
            repeat_password: '',
        },
        errors: {
            fullname: '',
            email: '',
            whatsapp: '',
            password: '',
            repeat_password: '',
        },
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'register'
            Alpine.store('member').showBottomMenu = false

            if(cachePageData['member/register']){
                this.data = cachePageData['member/register']
              } else {   
                fetchPageData('pages/member/register', {
                  headers: {
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                  }
                }).then(data => {
                  cachePageData['member/register'] = data
                  this.data = data
                })
              }
        },
        register() {
            this.errors = {
                fullname: '',
                email: '',
                whatsapp: '',
                password: '',
                repeat_password: '',
            };

            // Check login using axios post
            const formData = new FormData();
            formData.append('fullname', this.data.fullname ?? '');
            formData.append('email', this.data.email ?? '');
            formData.append('whatsapp', this.data.whatsapp ?? '');
            formData.append('password', this.data.password ?? '');
            formData.append('repeat_password', this.data.repeat_password ?? '');
            axios.post('/pages/member/register', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(response => {
                if(response.data.success == 1){
                    let token = response.data.token + '_' + response.data.id + 'X' + Math.random().toString(36).substring(7)
                    window.PineconeRouter.context.navigate('/member/register_confirm/' + token)
                } else {
                    this.errors = response.data.errors
                }
            })
        }
    }
}