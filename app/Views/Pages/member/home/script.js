// Page member/home
window.member_home = function(){
  return {
    title: "Beranda",
    data: [],
    showAllIcons: false,
    swiperArticle: null,
    swiperVideo: null,
    unreadPengumuman: 3,
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
