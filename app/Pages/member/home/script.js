// Page member/home
window.member_home = function(){
  return {
    title: "Beranda",
    data: [],
    comingsoon: false,
    showAllIcons: false,
    swiperArticle: null,
    swiperVideo: null,
    pengumumanRead: [],

    init() {
      if(localStorage.getItem('intro') != 1){
        window.PineconeRouter.context.redirect('/intro');
      }
      
      document.title = this.title;
      Alpine.store('tarbiyya').currentPage = 'home'
      Alpine.store('tarbiyya').showBottomMenu = true

      this.pengumumanRead = JSON.parse(localStorage.getItem('pengumumanRead') ?? '[]')
      
      if(cachePageData['member/home']){
        this.data = cachePageData['member/home']
      } else {   
        fetchPageData('member/home/supply', {
          headers: {
            'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
            'Pesantrenku-ID': Alpine.store('tarbiyya').pesantrenID
          }
        }).then(data => {
          cachePageData['member/home'] = data
          this.data = data
        }).catch(err => {
          console.error(err)
        })
      }
    },
    
    initSwiperArticles () {
      let config = {
        slidesPerView: 1.6,
        spaceBetween: 10,
        slidesOffsetBefore: 15,
        slidesOffsetAfter: 20,
        autoplay: {
          delay: 60000,
          pauseOnMouseEnter: true,
        },
        breakpoints: {
          // when window width is >= 640px
          640: {
            slidesPerView: 2.8,
            spaceBetween: 20
          }
        }
      }

      if(this.data.posts.length > 2){
        config.autoplay.delay = 60000;
      }

      this.swiperArticle = new Swiper(".swiper-article", config);
    },

    initSwiperVideos () {
      let config = {
        slidesPerView: 1.6,
        spaceBetween: 10,
        slidesOffsetBefore: 15,
        slidesOffsetAfter: 20,
        autoplay: {
          delay: 120000,
          pauseOnMouseEnter: true,
        },
        breakpoints: {
          // when window width is >= 640px
          640: {
            slidesPerView: 2.8,
            spaceBetween: 20
          }
        }
      };
      
      if(this.data.videos.length > 2){
        config.autoplay.delay = 60000;
      }

      this.swiperVideo = new Swiper(".swiper-video", config);
    },

  }
}
