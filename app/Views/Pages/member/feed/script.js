window.member_feed = function(){
    return {
        title: "Info Pesantren",
        id: null,
        post: {},
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'feed'
            Alpine.store('member').showBottomMenu = true

            this.loadPost()
        },
        formatDate(dateString){
            if(dateString && dateString != '0000-00-00'){
                const date = new Date(dateString);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Intl.DateTimeFormat('id-ID', options).format(date);
            }
            return '';
        },
        loadPost() {
            if(cachePageData[`member/feed`][this.id]){
                this.data.post = cachePageData[`member/feed`][this.id].post
            } else {
                fetchPageData(`member/feed/${this.id}`, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                    }
                }).then(data => {
                    if(data.data.post.length == 0){
                        this.empty = true
                    } else {
                        cachePageData[`member/feed`][this.id] = data.data
                    }
                })
            }
        }
    }
}
