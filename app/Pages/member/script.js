// Script untuk halaman utama member

// Alpine data function
document.addEventListener('alpine:init', () => {

    NProgress.configure({ showSpinner: false });

    // Setup Pinecone Router
    window.PineconeRouter.settings.basePath = '/member';
    
    document.addEventListener('pinecone-start', () => {
        NProgress.start();
        Alpine.store('member').pageLoaded = false
    });
    document.addEventListener('pinecone-end', () => {
        NProgress.done();
        Alpine.store('member').pageLoaded = true;
    });
    document.addEventListener('fetch-error', (err) => console.error(err));

    // Global store
    Alpine.store('member', {
        currentPage: 'home',
        pageLoaded: false,
        showBottomMenu: true,
        sessionToken: null,
        kodePesantren: null,
        tarbiyyaSetting: { }
    })
    
    window.member = function(){
        return {
            init(){
                document.title = this.title;
                Alpine.store('member').kodePesantren = getCookie("kodepesantren")
                Alpine.store('member').sessionToken = localStorage.getItem('heroic_token')

                if(Alpine.store('member').kodePesantren) {
                    if(Object.keys(Alpine.store('member').tarbiyyaSetting).length < 1){
                        fetchPageData('member/supply', {
                            headers: {
                                'Authorization': `Bearer ` + localStorage.getItem('heroic_token'),
                                'Pesantrenku-ID': getCookie("kodepesantren")
                            }
                        }).then(data => {
                            Alpine.store('member').tarbiyyaSetting = data.tarbiyyaSetting
                        })
                    }
                }
            },
            
            // Check kode pesantren
            isKodePesantrenSet(context){
                if(Alpine.store('member').kodePesantren == null) return context.redirect('/kodepesantren')
            },

            // Check login session, dipanggil oleh x-handler template yang meemerlukan session
            isLoggedIn(context){
                if(Alpine.store('member').sessionToken == null) return context.redirect('/login')
            }
        }
    }
})

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

// Variabel untuk melacak offcanvas yang sedang terbuka
window.openOffcanvas = null;
window.openModal = null;
window.historyStateAdded = false;

document.addEventListener('pinecone-end', () => {
    var appHeader = document.querySelector(".appHeader.scrolled");
    if (document.body.contains(appHeader)) {
        animatedScroll();
        document.addEventListener("scroll", function () {
            animatedScroll();
        })
    }

    function handlePopState(event) {
        if (window.openOffcanvas) {
            // Menutup offcanvas yang terbuka dengan animasi
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(window.openOffcanvas);
            offcanvasInstance.hide();

            // Tidak perlu manipulasi history di sini karena tombol kembali sudah mengubah state history
            // Hanya perlu mereset flag
            window.historyStateAdded = false;

            // Menghapus referensi ke offcanvas yang terbuka
            window.openOffcanvas = null;
        } else if (window.openModal) {
            // Menutup modal yang terbuka dengan animasi
            const modalInstance = bootstrap.Modal.getInstance(window.openModal);
            modalInstance.hide();

            // Tidak perlu manipulasi history di sini karena tombol kembali sudah mengubah state history
            // Hanya perlu mereset flag
            historyStateAdded = false;

            // Menghapus referensi ke modal yang terbuka
            window.openModal = null;
        } else {
            // Tidak ada modal yang terbuka, biarkan perilaku default
            // Ini akan berpindah ke halaman sebelumnya
        }
    }

    // Mendapatkan semua elemen offcanvas dan modal
    window.offcanvasElements = document.querySelectorAll('.offcanvas');
    window.modalElements = document.querySelectorAll('.modal');

    offcanvasElements.forEach((offcanvasElement) => {
        // Mendapatkan instance Bootstrap Offcanvas
        const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);

        offcanvasElement.addEventListener('shown.bs.offcanvas', () => {
            if (!window.historyStateAdded) {
                // Menambahkan state baru ke history
                history.pushState({ offcanvasOpen: true }, '');
                window.historyStateAdded = true;
            }
            // Menetapkan offcanvas yang sedang terbuka
            window.openOffcanvas = offcanvasElement;

            // Menambahkan event listener untuk popstate
            window.addEventListener('popstate', handlePopState);
        });

        offcanvasElement.addEventListener('hidden.bs.offcanvas', () => {
            // Menghapus event listener popstate
            window.removeEventListener('popstate', handlePopState);

            if (window.historyStateAdded) {
                // Menghapus state yang ditambahkan ke history
                history.back();
                window.historyStateAdded = false;
            }

            // Menghapus referensi ke offcanvas yang terbuka
            window.openOffcanvas = null;
        });
    });

    modalElements.forEach((modalElement) => {
        // Mendapatkan instance Bootstrap Modal
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);

        modalElement.addEventListener('shown.bs.modal', () => {
            if (!window.historyStateAdded) {
                // Menambahkan state baru ke history
                history.pushState({ modalOpen: true }, '');
                window.historyStateAdded = true;
            }
            // Menetapkan modal yang sedang terbuka
            window.openModal = modalElement;

            // Menambahkan event listener untuk popstate
            window.addEventListener('popstate', handlePopState);
        });

        modalElement.addEventListener('hidden.bs.modal', () => {
            // Menghapus event listener popstate
            window.removeEventListener('popstate', handlePopState);

            if (window.historyStateAdded) {
                // Menghapus state yang ditambahkan ke history
                history.back();
                window.historyStateAdded = false;
            }

            // Menghapus referensi ke modal yang terbuka
            window.openModal = null;
        });
    });
});