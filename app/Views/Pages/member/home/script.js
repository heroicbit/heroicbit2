// Page member/home
window.member_home = function(){
    return {
        title: "Beranda",
        data: [],
        init(){
            if(localStorage.getItem('intro') != 1){
                window.PineconeRouter.context.navigate('/intro');
            }

            document.title = this.title;
            Alpine.store('member').currentPage = 'home'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/home']){
                this.data = cachePageData['member/home']
            } else {   
                fetchPageData('pages/member/home', {
                    headers: {
                        'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                        'Pesantrenku-ID': localStorage.getItem('kodepesantren')
                    }
                }).then(data => {
                    cachePageData['member/home'] = data
                    this.data = data
                })
            }
        },

        initSwiperArticles () {
            let swiperArticle = new Swiper(".swiper-article", {
              slidesPerView: 1.6,
              spaceBetween: 10,
              slidesOffsetBefore: 15,
              slidesOffsetAfter: 20,
              autoplay: {
                delay: 5000,
                pauseOnMouseEnter: true,
              },
              breakpoints: {
                // when window width is >= 640px
                640: {
                  slidesPerView: 2.8,
                  spaceBetween: 20
                }
              }
            });
          },
    }
}
