// Page member/home
window.member_home = function(){
    return {
        title: "Discover",
        data: [],
        init(){
            document.title = this.title;

            fetchPageData('member/home').then(data => {
                this.data = data
            })
        }
    }
}