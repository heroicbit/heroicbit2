<?= $this->extend('template/mobilekit/layouts/basic') ?>

<?php $this->section('content') ?>

<!-- Start App -->
<div id="app" x-data="example">

    <template x-route="/" x-template="/home/_index"></template>
    <template x-route="/components" x-template="/home/_components"></template>
    <template x-route="/chats" x-template="/home/_chats"></template>

</div>
<!-- End App -->

<script>
    let example = ({
        id: '',
        loading: false,
        hello(context) {
            if (context.params.name.toLowerCase() == 'home') {
                return context.redirect('/')
            }
        },

        // async functions will be automatically awaited by Pinecone Router
        // meaning until it finished subsequent handlers wont be executed
        // and templates wont be displayed
        async awaitedHandler(context) {
            await new Promise(resolve => setTimeout(resolve, 1500));
            this.id = context.params.id
        },
    })

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
        if(segment == '') segment = 'home';
        let activeMenu = document.getElementById('bottommenu-' + segment);
        if (activeMenu) {
            activeMenu.classList.add('active');
        }
    }
</script>

<?php $this->endSection() ?>