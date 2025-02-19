window.member_feeds = function(){
    return {
        title: "Info Pesantren",
        feeds: [],
        nextPage: 1,
        empty: false,
        init(){
            document.title = this.title;
            Alpine.store('tarbiyya').currentPage = 'feeds'
            Alpine.store('tarbiyya').showBottomMenu = true

            if(cachePageData[`member/feeds`]?.feeds.length > 0){
                cachePageData[`member/feeds`].feeds.forEach(item => {
                    this.feeds.push(item)
                })
                this.nextPage = cachePageData[`member/feeds`].nextPage ?? 1;
                this.empty = cachePageData[`member/feeds`].empty ?? false;
            } else {
                cachePageData[`member/feeds`] = {}
                this.loadFeeds()
            }
        },
        
        loadMore() {
            this.loadFeeds()
        },
        loadFeeds() {
            console.log('Fetching data...');
            fetchPageData(`member/feeds/supply?page=${this.nextPage}`, {
                headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
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
            window.PineconeRouter.context.navigate(`/feeds/${this.feeds[index].id}`);
        },
        stripIntro(wordNum, sentence, index){
            let words = sentence.split(` `);
            if(words.length > wordNum){
                let result = words.slice(0, wordNum).join(` `)
                return result + `... <a href="javascript:void()" x-on:click="showDetail(${index})">Selengkapnya</a>`
            } else {
                return sentence;
            }
        }
    }
}
