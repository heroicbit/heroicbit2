window.member_feed = function(id){
    return {
        title: "Detail Info",
        id: id,
        notFound: false,
        feed: {},
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'feed'
            Alpine.store('member').showBottomMenu = true

            // Get cache if exists
            this.feed = cachePageData[`member/feeds`]?.feeds.filter(item => item.id == this.id) ?? {};
            if(Object.keys(this.feed).length == 0) {
                fetchPageData(`pages/member/feed/${this.id}`, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                    }
                })
                .then(data => {
                    if(data.data.post.length == 0){
                        this.notFound = true
                    } else {
                        this.feed = data.data.post
                    }
                })
            }
        },
        formatDate(dateString){
            if(dateString && dateString != '0000-00-00') {
                const date = new Date(dateString);
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Intl.DateTimeFormat('id-ID', options).format(date);
            }
            return '';
        },
    }
}
