// Page member/component
window.member_kodepesantren = function(){
    return {
        title: "Kode Pesantren",
        kode: null,
        init(){
            document.title = this.title
            Alpine.store('member').currentPage = 'kodepesantren'
            Alpine.store('member').showBottomMenu = false
        },
        checkKodePesantren(){
            localStorage.setItem('kodepesantren', this.kodepesantren)
            window.PineconeRouter.context.navigate('/login')
        }
    }
}