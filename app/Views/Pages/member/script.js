// Script untuk halaman utama member

// Alpine data function
document.addEventListener('alpine:init', () => {
    Alpine.data('member', () => ({
        title: "Member Dashboard",
        sessionToken: null,
        kodePesantren: null,
        init(){
            document.title = this.title;
        },
        // Check kode pesantren
        isKodePesantrenSet(context){
            this.kodePesantren = localStorage.getItem('kodepesantren')
            if(this.kodePesantren == null) return context.redirect('/kodepesantren')
        },
        // Check login session, dipanggil oleh x-handler template yang meemerlukan session
        isLoggedIn(context){
            this.sessionToken = localStorage.getItem('token')
            if(this.sessionToken == null) return context.redirect('/login')
        }
    }))
})

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

    // Script to handle Android back button// Mendapatkan elemen offcanvas dan instance-nya
    // Variabel global untuk menyimpan offcanvas yang sedang terbuka
    let currentOffcanvasElement = null;
    let currentOffcanvasInstance = null;
    let previousState = null;
    let previousTitle = null;
    let previousUrl = null;

    function handleBackButton(event) {
        // Jika ada offcanvas yang sedang terbuka
        if (currentOffcanvasElement && currentOffcanvasElement.classList.contains('show')) {
            event.preventDefault(); // Mencegah navigasi kembali
            currentOffcanvasInstance.hide(); // Menutup offcanvas dengan animasi
            // Mengembalikan state history sebelumnya setelah animasi selesai
            currentOffcanvasElement.addEventListener('hidden.bs.offcanvas', restoreHistoryState, { once: true });
        }
    }

    function restoreHistoryState() {
        if (previousState !== null) {
            // Mengganti state history saat ini dengan state sebelumnya
            history.replaceState(previousState, previousTitle, previousUrl);
            previousState = null; // Reset state
        }
        // Reset currentOffcanvasElement dan instance
        currentOffcanvasElement = null;
        currentOffcanvasInstance = null;
    }

    // Mendapatkan semua elemen offcanvas di halaman
    const offcanvasElements = document.querySelectorAll('.offcanvas');

    offcanvasElements.forEach((offcanvasElement) => {
        const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);

        // Saat offcanvas dibuka
        offcanvasElement.addEventListener('shown.bs.offcanvas', () => {
            // Jika sudah ada offcanvas yang terbuka, tidak perlu melakukan apa-apa
            if (currentOffcanvasElement) {
                return;
            }
            // Menyimpan state history sebelumnya
            previousState = history.state;
            previousTitle = document.title;
            previousUrl = location.href;

            // Menyimpan offcanvas yang sedang terbuka
            currentOffcanvasElement = offcanvasElement;
            currentOffcanvasInstance = offcanvasInstance;

            // Menambahkan state baru ke history untuk offcanvas
            history.pushState({ offcanvasOpen: true }, '', location.href);
            window.addEventListener('popstate', handleBackButton);
        });

        // Saat offcanvas ditutup
        offcanvasElement.addEventListener('hidden.bs.offcanvas', () => {
            // Jika offcanvas yang ditutup adalah yang sedang aktif
            if (currentOffcanvasElement === offcanvasElement) {
                window.removeEventListener('popstate', handleBackButton);
                // Tidak perlu manipulasi history di sini karena sudah ditangani di restoreHistoryState
                // Reset currentOffcanvasElement dan instance akan dilakukan di restoreHistoryState
            }
        });
    });
});