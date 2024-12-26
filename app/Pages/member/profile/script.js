// Page member/profile
window.member_profile = function(){
    return {
        title: "Profile",
        data: [],
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'profile'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/profile']){
                this.data = cachePageData['member/profile']
            } else {   
                fetchPageData('/pages/member/profile', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('member').sessionToken,
                        'Pesantrenku-ID': Alpine.store('member').kodePesantren,
                    }
                }).then(data => {
                    cachePageData['member/profile'] = data
                    this.data = data
                })
            }
        },
        logout(){
            localStorage.removeItem('heroic_token');
            window.PineconeRouter.context.navigate('/login');
        }
    }
}