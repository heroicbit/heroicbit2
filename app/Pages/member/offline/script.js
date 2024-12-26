window.member_offline = function(slug) {
    return {
        title: "Anda sedang offline",
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'offline'
            Alpine.store('member').showBottomMenu = true
        }
    }
}
