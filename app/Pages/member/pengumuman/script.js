// Page member/home
window.member_pengumuman = function(){
    return {
        title: "Pengumuman Pesantren",
        data: [],
        detailPengumuman: null,
        init(){
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'pengumuman'
            Alpine.store('tarbiyya').showBottomMenu = true

            if(cachePageData['member/pengumuman']){
                this.data = cachePageData['member/pengumuman']
            } else {
                fetchPageData('member/pengumuman/supply', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('tarbiyya').sessionToken,
                        'Pesantrenku-ID': Alpine.store('tarbiyya').kodePesantren,
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
