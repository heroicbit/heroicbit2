// Page member/home
window.member_program_pesantren = function(){
    return {
        title: "Program Pesantren",
        data: [],
        detail: null,
        init(){
            document.title = this.title;
            Alpine.store('member').currentPage = 'program_pesantren'
            Alpine.store('member').showBottomMenu = true

            if(cachePageData['member/program_pesantren']){
                this.data = cachePageData['member/program_pesantren']
            } else {
                fetchPageData('member/program_pesantren/supply', {
                    headers: {
                        'Authorization': `Bearer ` + Alpine.store('member').sessionToken,
                        'Pesantrenku-ID': Alpine.store('member').kodePesantren,
                    }
                }).then(data => {
                    cachePageData['member/program_pesantren'] = data
                    this.data = data
                })
            }
        },
        showDetail(index){
            this.detail = this.data.pages[index]
        }
    }
}
