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
    
    <!-- ============== Js Files ==============  -->
    <!-- Bootstrap -->
    <script src="<?= $themeURL ?>assets/js/lib/bootstrap.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- ProgressBar js -->
    <script src="<?= $themeURL ?>assets/js/plugins/progressbar-js/progressbar.min.js"></script>

    <script>
		document.addEventListener('alpine:initialized', () => {
			window.PineconeRouter.settings.basePath = '/'
			window.PineconeRouter.settings.hash = true; // use hash routing
		});
		// add progress bar
        NProgress.configure({ showSpinner: false });
		document.addEventListener('pinecone-start', () => NProgress.start());
		document.addEventListener('pinecone-end', () => NProgress.done());
		document.addEventListener('fetch-error', (err) => console.error(err));
	</script>

</body>

</html>