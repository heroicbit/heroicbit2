<?php 
$themeURL = service('settings')->get('Theme.frontendThemeURL'); 
$themePath = service('settings')->get('Theme.frontendThemePath'); ?>
<!doctype html>
<html lang="en">
<head>
    <?= $this->include($themePath . 'partials/head') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@8.5.0/themes/reset-min.css" integrity="sha256-KvFgFCzgqSErAPu6y9gz/AhZAvzK48VJASu3DpNLCEQ=" crossorigin="anonymous">

    <style>
    .ais-SearchBox-input {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .ais-InstantSearch {
        max-width: 960px;
        width: 100%;
        display: block;
        overflow: hidden;
        margin: 0 auto;
        font-family: sans-serif;
    }
    .ais-SearchBox-submit {
        display: none;
    }

    .ais-Hits-item {
        border-bottom: 1px solid #E1E1E1;
    }
    .ais-Hits-item .item {
        padding: 10px 16px;
        width: 100%;
        min-height: 50px;
        display: flex;
    }
    .ais-Hits-item .item .imageWrapper {
        margin-right: 16px;
    }
    .ais-Hits-item .item .in {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .ais-Hits-item .item .in header,
    .ais-Hits-item .item .in footer {
        font-size: 12px;
        margin: 0;
        line-height: 1.2em;
    }
    .ais-Hits-item .item .in footer {
        color: #4F5050;
        margin-top: 3px;
    }
    </style>
</head>
<body>
    <?= $this->renderSection('content') ?>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="<?= $themeURL ?>assets/js/vendor.bundle.js"></script>
    <script src="<?= $themeURL ?>assets/js/base.js"></script>
    <script src="<?= $themeURL ?>assets/js/pagescript.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.24.0/dist/algoliasearch-lite.umd.js" integrity="sha256-b2n6oSgG4C1stMT/yc/ChGszs9EY/Mhs6oltEjQbFCQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.74.0/dist/instantsearch.production.min.js" integrity="sha256-1OlwSxFMcBXdQtWWvx95HkDw88ZSOde0gyii+lyOkB4=" crossorigin="anonymous"></script>

    <script>
		document.addEventListener('alpine:init', () => {
			window.PineconeRouter.settings.basePath = '/'
			window.PineconeRouter.settings.hash = true; // use hash routing
		});
        // Configure NProgress
        NProgress.configure({ showSpinner: false });
		// add progress bar
        document.addEventListener('pinecone-start', () => NProgress.start());
		document.addEventListener('pinecone-end', () => NProgress.done());
		document.addEventListener('fetch-error', (err) => console.error(err));

        // Always run this code in the end of html to start AlpineJS
        Alpine.start();
	</script>
</body>

</html>