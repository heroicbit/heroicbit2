<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

<script src="<?= $themeURL ?>assets/js/pagescript.js?v<?= $version ?>" defer></script>    
<script src="https://cdn.jsdelivr.net/npm/pinecone-router@4.x.x/dist/router.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

<script src="<?= $themeURL ?>assets/js/helpers.bundle.js?v<?= $version ?>"></script>
<script src="<?= $themeURL ?>assets/js/base.js?v<?= $version ?>"></script>

<script>
    // Setup Pinecone Router
    document.addEventListener('alpine:init', () => {
        window.PineconeRouter.settings.basePath = '/'
        window.PineconeRouter.settings.hash = true; // use hash routing
    })

    // Configure NProgress
    NProgress.configure({ showSpinner: false });
    
    document.addEventListener('pinecone-start', () => {
        NProgress.start();
        Alpine.store('member').pageLoaded = false
    });
    document.addEventListener('pinecone-end', () => {
        NProgress.done();
        Alpine.store('member').pageLoaded = true;
    });
    document.addEventListener('fetch-error', (err) => console.error(err));
</script>