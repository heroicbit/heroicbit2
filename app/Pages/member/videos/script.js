window.member_videos = function(){
    return {
        title: "Video Pesantren",
        videos: [],
        nextPage: 1,
        empty: false,
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'videos'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData[`member/videos`]?.videos.length > 0){
                cachePageData[`member/videos`].videos.forEach(item => {
                    this.videos.push(item)
                })
                this.nextPage = cachePageData[`member/videos`].nextPage ?? 1;
                this.empty = cachePageData[`member/videos`].empty ?? false;
            } else {
                cachePageData[`member/videos`] = {}
                this.loadVideos()
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
            this.loadVideos()
        },
        loadVideos() {
            console.log('Fetching data...');
            fetchPageData(`api/member/videos?page=${this.nextPage}`, {
                headers: {
                    'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                    'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                }
            }).then(data => {
                if(data.data.videos.length == 0){
                    this.empty = true
                    cachePageData[`member/videos`].empty = true
                } else {
                    data.data.videos.forEach(item => {
                        this.videos.push(item)
                    })
                    this.nextPage++

                    cachePageData[`member/videos`].videos = this.videos
                    cachePageData[`member/videos`].nextPage = this.nextPage
                }
            })
        },
        showDetail(index){
            window.PineconeRouter.context.navigate(`/videos/${this.videos[index].id}`);
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
