<?php 
$themeURL = service('settings')->get('Theme.frontendThemeURL'); 
$themePath = service('settings')->get('Theme.frontendThemePath'); ?>
<!doctype html>
<html lang="en">
<head>
    <?= $this->include($themePath . 'partials/head') ?>
</head>
<body>
    
    <?= $this->renderSection('content') ?>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>let base_url = `<?= site_url() ?>`;</script>
    <script src="<?= $themeURL ?>assets/js/vendor.bundle.js"></script>
    <script src="<?= $themeURL ?>assets/js/base.js"></script>
    <script src="<?= $themeURL ?>assets/js/pagescript.js"></script>

    <script>
		document.addEventListener('alpine:init', () => {
			window.PineconeRouter.settings.basePath = '/'
			window.PineconeRouter.settings.hash = true; // use hash routing
		});
		// add progress bar
        document.addEventListener('pinecone-start', () => NProgress.start());
		document.addEventListener('pinecone-end', () => NProgress.done());
		document.addEventListener('fetch-error', (err) => console.error(err));

        // Always run this code in the end of html to start AlpineJS
        Alpine.start();
	</script>

</body>

</html>