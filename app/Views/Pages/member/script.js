// Script untuk halaman utama home
// Mendapatkan hash dari URL
document.addEventListener('pinecone-end', () => {
    updateActiveMenu();
});

function updateActiveMenu() {
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