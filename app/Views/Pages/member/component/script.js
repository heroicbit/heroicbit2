// Page member/component
window.member_component = function(){
    return {
        title: "Components",
        data: [],
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'component'

            fetchPageData('member/component').then(data => {
                this.data = data
            })
        }
    }
}