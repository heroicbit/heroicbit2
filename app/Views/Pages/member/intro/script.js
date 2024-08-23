// Page member/intro
window.member_intro = function(){
    return {
        title: "Intro",
        swiper: null,
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'intro'
            Alpine.store('member').showBottomMenu = false
            this.swiper = new Swiper(".swiper-intro", {
                slidesPerView: 1,
                spaceBetween: 20,
                autoplay: {
                    delay: 5000,
                    pauseOnMouseEnter: true
                },
                pagination: {
                    el: ".swiper-pagination"
                }
            });
        },
        gotoLogin(){
            localStorage.setItem('intro', 1)
            window.PineconeRouter.context.navigate('/login')
        }
    }
}