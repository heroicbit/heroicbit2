// Page member/home
window.member_pengumuman = function(){
    return {
        title: "Pengumuman Pesantren",
        pengumuman: [],
        icons: [],
        nextPage: 1,
        detailPengumuman: null,
        empty: false,
        newestPublished: null,

        init(){
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'pengumuman'
            Alpine.store('tarbiyya').showBottomMenu = true

            if(cachePageData[`member/pengumuman`]?.pengumuman.length > 0){
                cachePageData[`member/pengumuman`].pengumuman.forEach(item => {
                    this.pengumuman.push(item)
                })
                this.icons = cachePageData[`member/pengumuman`].icons ?? {};
                this.nextPage = cachePageData[`member/pengumuman`].nextPage ?? 1;
                this.empty = cachePageData[`member/pengumuman`].empty ?? false;
            } else {
                cachePageData[`member/pengumuman`] = {}
                this.loadPengumuman()
            }
        },
        loadMore() {
            this.loadPengumuman()
        },
        loadPengumuman() {
            console.log('Fetching data...');
            fetchPageData(`member/pengumuman/supply?page=${this.nextPage}`, {
                headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                }
            }).then(response => {
                this.icons = response.icons
                if(response.pengumuman.length == 0){
                    this.empty = true
                    cachePageData[`member/pengumuman`].empty = true
                } else {
                    response.pengumuman.forEach(item => {
                        this.pengumuman.push(item)
                    })
                    this.nextPage++

                    cachePageData[`member/pengumuman`].icons = this.icons
                    cachePageData[`member/pengumuman`].pengumuman = this.pengumuman
                    cachePageData[`member/pengumuman`].nextPage = this.nextPage
                }
            })
        },
        showDetail(index){
            this.detailPengumuman = this.pengumuman[index]
        },
    }
}
