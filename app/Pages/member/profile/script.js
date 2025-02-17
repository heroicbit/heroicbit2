// Page member/profile
window.member_profile = function(){
    return {
        title: "Profile",
        data: [],
        init(){
            window.scrollTo({top:0, behavior:'auto'});
            
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'profile'

            if(cachePageData['member/profile']){
                this.data = cachePageData['member/profile']
            } else {   
                fetchPageData('member/profile/supply', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID,
                    }
                }).then(data => {
                    cachePageData['member/profile'] = data
                    this.data = data
                })
            }
        },
        async logout(){
            // Confirm
            const confirmed = await Prompts.confirm("Anda yakin akan keluar dari aplikasi?");
            if (confirmed) {
                window.location.href = '/logout'
            }
        }
    }
}
