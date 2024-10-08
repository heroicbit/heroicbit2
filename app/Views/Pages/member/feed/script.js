window.member_feed = function(){
    return {
        title: "Info Pesantren",
        data: {
            posts: []
        },
        page: 1,
        empty: false,
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

            this.loadPosts()
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
            this.loadPosts()
        },
        loadPosts() {
            if(cachePageData[`member/feed?page=${this.page}`]){
                cachePageData[`member/feed?page=${this.page}`].posts.forEach(item => {
                    this.data.posts.push(item)
                })
                this.page++
            } else {
                fetchPageData(`member/feed?page=${this.page}`, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                    }
                }).then(data => {
                    if(data.data.posts.length == 0){
                        this.empty = true
                    } else {
                        cachePageData[`member/feed?page=${this.page}`] = data.data
                        data.data.posts.forEach(item => {
                            this.data.posts.push(item)
                        })
                        this.page++
                    }
                })
            }
        },
        showDetail(index){
            this.detailFeed = this.data.articles[index]
        }
    }
}
