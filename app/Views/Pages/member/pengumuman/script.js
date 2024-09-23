// Page member/home
window.member_pengumuman = function(){
    return {
        title: "Pengumuman Pesantren",
        data: [],
        detailFeed: null,
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'pengumuman'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/pengumuman']){
                this.data = cachePageData['member/pengumuman']
            } else {
                fetchPageData('member/pengumuman').then(data => {
                    cachePageData['member/pengumuman'] = data
                    this.data = data
                })
            }
        },
        showDetail(index){
            this.detailFeed = this.data.posts[index]
        }
    }
}
