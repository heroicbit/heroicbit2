window.member_page = function(slug) {
    return {
        title: "Detail Halaman",
        slug: slug,
        notFound: false,
        page: {},
        init(){
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'page'
            Alpine.store('tarbiyya').showBottomMenu = true

            // Get cache if exists
            let url = `member/page/supply/${this.slug}`;
            this.page = cachePageData[url] ?? {};
            if(Object.keys(this.page).length === 0) {
                fetchPageData(url, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
                    }
                })
                .then(data => {
                    if(data.data.page.length == 0) {
                        this.notFound = true
                    } else {
                        this.page = data.data.page
                        cachePageData[url] = this.page
                        this.title = this.page.title
                        document.title = this.page.title
                    }
                })
            }
        },
    }
}
