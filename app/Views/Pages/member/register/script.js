// Page member/component
window.member_register = function(){
    return {
        title: "Login",
        data: [],
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'login'
            Alpine.store('member').showBottomMenu = false
        },
        register(){
            localStorage.setItem('token', 'yllumi')
            window.PineconeRouter.context.navigate('/')
        }
    }
}