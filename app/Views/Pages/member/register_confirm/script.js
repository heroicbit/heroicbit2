// Page member/component
window.member_register_confirm = function(tokens){
    return {
        title: "Konfirmasi Registrasi",
        showPwd: false,
        data: {
            logo: '',
            id: '',
            token: '',
            otp: '',
        },
        error: '',
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'register_confirm'
            Alpine.store('member').showBottomMenu = false

            const [part1, rest] = tokens.split('_');  // Bagian pertama sebelum _ adalah token
            const [part2, part3] = rest.split('X'); // Bagian kedua antara _ dan X, dan bagian ketiga
            this.data.token = part1
            this.data.id = part2

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
                    window.PineconeRouter.context.navigate('/')
                } else {
                    this.error = response.data.message
                }
            })
        }
    }
}