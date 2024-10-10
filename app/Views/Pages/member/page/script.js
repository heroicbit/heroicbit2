window.member_page = function(slug) {
    return {
        title: "Detail Halaman",
        slug: slug,
        notFound: false,
        page: {},
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'page'
            Alpine.store('member').showBottomMenu = true

            // Get cache if exists
            let url = `pages/member/page/${this.slug}`;
            this.page = cachePageData[url] ?? {};
            if(Object.keys(this.page).length === 0) {
                fetchPageData(url, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
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
