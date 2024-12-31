window.member_feeds_detail = function(id){
    return {
        title: "Detail Info",
        id: id,
        notFound: false,
        feed: {},
        swiper: null,
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'feed'
            Alpine.store('member').showBottomMenu = true

            // Get cache if exists
            this.feed = cachePageData[`member/feeds`]?.feeds.filter(item => item.id == this.id) ?? {};
            if(Object.keys(this.feed).length == 0) {
                fetchPageData(`api/member/feeds/detail/${this.id}`, {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': getCookie("kodepesantren")
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
        
        initFeedSwiper() {
            this.swiper = new Swiper(".feed-carousel", {
                pagination: {
                  el: ".swiper-pagination",
                  type: "fraction",
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
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
