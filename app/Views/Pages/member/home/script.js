// Page member/home
window.member_home = function(){
    return {
        title: "Beranda",
        data: [],
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'home'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/home']){
                this.data = cachePageData['member/home']
            } else {   
                fetchPageData('member/home').then(data => {
                    cachePageData['member/home'] = data
                    this.data = data
                })
            }
        }
    }
}
