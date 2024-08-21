// Page member/component
window.member_component = function(){
    return {
        title: "Components",
        data: [],
        init(){
            document.title = this.title
            fetchPageData('member/component').then(data => {
                this.data = data
            })
        }
    }
}