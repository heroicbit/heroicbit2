<?php 
$themeURL = service('settings')->get('Theme.frontendThemeURL'); 
$themePath = service('settings')->get('Theme.frontendThemePath'); ?>
<!doctype html>
<html lang="en">
<head>
    <?= $this->include($themePath . 'partials/head') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@8.5.0/themes/reset-min.css" integrity="sha256-KvFgFCzgqSErAPu6y9gz/AhZAvzK48VJASu3DpNLCEQ=" crossorigin="anonymous">
</head>
<body>
    <?= $this->renderSection('content') ?>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="<?= $themeURL ?>assets/js/vendor.bundle.js"></script>
    <script src="<?= $themeURL ?>assets/js/base.js"></script>
    <script src="<?= $themeURL ?>assets/js/pagescript.js"></script>    
    <script>
		document.addEventListener('alpine:init', () => {
			window.PineconeRouter.settings.basePath = '/'
			window.PineconeRouter.settings.hash = true; // use hash routing
		});
        // Configure NProgress
        NProgress.configure({ showSpinner: false });
		// add progress bar
        document.addEventListener('pinecone-start', () => {
            NProgress.start();
            Alpine.store('member').pageLoaded = false
        });
		document.addEventListener('pinecone-end', () => {
            NProgress.done();
            Alpine.store('member').pageLoaded = true
        });
		document.addEventListener('fetch-error', (err) => console.error(err));
        
        // Always run this code in the end of html to start AlpineJS
        Alpine.start();
	</script>
</body>

</html>