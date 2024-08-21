// Page member/home
window.member_home = function(){
    return {
        title: "Discover",
        data: [],
        init(){
            document.title = this.title;

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