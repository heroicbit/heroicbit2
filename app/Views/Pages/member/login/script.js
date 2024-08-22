// Page member/component
window.member_login = function(){
    return {
        title: "Login",
        data: [],
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'login'
            Alpine.store('member').showBottomMenu = false
        },
        login(){
            localStorage.setItem('token', 'yllumi')
            window.PineconeRouter.context.navigate('/')
        }
    }
}