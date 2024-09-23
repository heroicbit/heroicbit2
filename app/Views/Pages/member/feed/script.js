// Page member/feed
window.member_feed = function(){
    return {
        title: "Info Pesantren",
        data: [],
        detailFeed: {
            title: '',
            content: '',
            created_at: '',
            updated_at: ''
        },
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'feed'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/feed']){
                this.data = cachePageData['member/feed']
            } else {
                fetchPageData('member/feed').then(data => {
                    cachePageData['member/feed'] = data
                    this.data = data
                })
            }
        },
        showDetail(index){
            this.detailFeed = this.data.articles[index]
        }
    }
}
