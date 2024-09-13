// Page member/component
window.member_login = function(){
    return {
        title: "Login",
        showPwd: false,
        data: [],
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'login'
            Alpine.store('member').showBottomMenu = false
        },
        login(){
            // Check login using axios post
            const formData = new FormData();
            formData.append('username', this.data.username);
            formData.append('password', this.data.password);
            axios.post('/member/login', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(response => {
                if(response.data.found == 1){
                    localStorage.setItem('token', response.data.jwt)
                    window.PineconeRouter.context.navigate('/')
                } else {
                    toastr.warning('Username atau password salah')
                }
            })
        }
    }
}