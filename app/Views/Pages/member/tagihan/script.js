// Page member/profile
window.member_tagihan = function(){
    return {
        title: "Profile",
        data: [],
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'info'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/info']){
                this.data = cachePageData['member/info']
            } else {   
                fetchPageData('member/info').then(data => {
                    cachePageData['member/info'] = data
                    this.data = data
                })
            }
        }
    }
}
