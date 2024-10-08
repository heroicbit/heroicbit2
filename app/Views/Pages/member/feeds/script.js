window.member_feeds = function(){
    return {
        title: "Info Pesantren",
        feeds: [],
        nextPage: 1,
        empty: false,
        detailFeed: {
            author_id: null,
            author_name: null,
            avatar: null,
            content: null,
            created_at: null,
            id: null,
            medias: null,
            published_at: null,
            status: null,
            title: null,
            total_comment: null,
            total_like: null,
        },
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'feeds'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData[`member/feeds`]?.feeds.length > 0){
                cachePageData[`member/feeds`].feeds.forEach(item => {
                    this.feeds.push(item)
                })
                this.nextPage = cachePageData[`member/feeds`].lastPage;
                this.empty = cachePageData[`member/feeds`].empty;
            } else {
                cachePageData[`member/feeds`] = {}
                this.loadFeeds()
            }
        },
        formatDate(dateString){
            if(dateString && dateString != '0000-00-00'){
                const date = new Date(dateString);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Intl.DateTimeFormat('id-ID', options).format(date);
            }
            return '';
        },
        loadMore() {
            this.loadFeeds()
        },
        loadFeeds() {
            console.log('Fetching data...');
            fetchPageData(`member/feeds?page=${this.nextPage}`, {
                headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(data => {
                if(data.data.posts.length == 0){
                    this.empty = true
                    cachePageData[`member/feeds`].empty = true
                } else {
                    data.data.posts.forEach(item => {
                        this.feeds.push(item)
                    })
                    this.nextPage++

                    cachePageData[`member/feeds`].feeds = this.feeds
                    cachePageData[`member/feeds`].nextPage = this.nextPage
                }
            })
        },
        showDetail(index){
            this.detailFeed = this.feeds[index]
        }
    }
}
