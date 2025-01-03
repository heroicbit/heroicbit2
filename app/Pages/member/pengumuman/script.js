// Page member/home
window.member_pengumuman = function(){
    return {
        title: "Pengumuman Pesantren",
        data: [],
        detailPengumuman: null,
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'pengumuman'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/pengumuman']){
                this.data = cachePageData['member/pengumuman']
            } else {
                fetchPageData('member/pengumuman/supply', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('member').sessionToken,
                        'Pesantrenku-ID': Alpine.store('member').kodePesantren,
                    }
                }).then(data => {
                    cachePageData['member/pengumuman'] = data
                    this.data = data
                })
            }
        },
        showDetail(index){
            this.detailPengumuman = this.data.pengumuman[index]
        }
    }
}
