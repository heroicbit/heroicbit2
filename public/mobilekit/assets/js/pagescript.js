// Page member/component
function member_component(){
    return {
        title: "Components",
        data: [],
        init(){
            document.title = this.title
            // Fetch data from API using Axios
            axios
                .get(base_url + 'member/component?dataonly=1')
                .then(response => {
                    console.log(response.data);
                    this.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}

// Page member/home
function member_home(){
    return {
        title: "Discover",
        data: [],
        init(){
            document.title = this.title;
            // Fetch data from API using Axios
            axios
                .get(base_url + 'member/home?dataonly=1')
                .then(response => {
                    console.log(response.data);
                    this.data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}

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