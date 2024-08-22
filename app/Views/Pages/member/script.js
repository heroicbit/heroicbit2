// Script untuk halaman utama member

// Alpine Store
Alpine.store('member', {
    currentPage: 'home',
    showBottomMenu: false,
})

// Alpine data function
window.member = function(){
    return {
        title: "Member Dashboard",
        sessionToken: null,
        init(){
            document.title = this.title;
        },
        // Check login session, dipanggil oleh x-handler template yang meemerlukan session
        isLoggedIn(context){
            this.sessionToken = localStorage.getItem('token')
            if(this.sessionToken == null) return context.redirect('/login')
        }
    }
}

// Fungsi untuk set aktif current bottommenu 
window.updateActiveBottomMenu = function() {
    let hash = window.location.hash;
    let segment = hash.replace(/^#\/?/, '');

    // Menghapus class 'active' dari semua menu
    let menus = document.querySelectorAll('[id^="bottommenu-"]');
    menus.forEach(menu => menu.classList.remove('active'));

    // Menambahkan class 'active' ke menu yang sesuai dengan segmen
    if(segment == '') segment = 'member';
    let activeMenu = document.getElementById('bottommenu-' + segment);
    if (activeMenu) {
        activeMenu.classList.add('active');
    }
}

//****************************************************************** */
// Animated header style on scroll
//****************************************************************** */
window.animatedScroll = function() {
    var appHeader = document.querySelector(".appHeader.scrolled");
    var scrolled = window.scrollY;
    if (scrolled > 20) {
        appHeader.classList.add("is-active")
    }
    else {
        appHeader.classList.remove("is-active")
    }
}

document.addEventListener('pinecone-end', () => {
    updateActiveBottomMenu();
    var appHeader = document.querySelector(".appHeader.scrolled");
    if (document.body.contains(appHeader)) {
        animatedScroll();
        document.addEventListener("scroll", function () {
            animatedScroll();
        })
    }
});