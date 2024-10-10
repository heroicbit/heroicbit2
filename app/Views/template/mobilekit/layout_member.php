<!doctype html>
<html lang="en">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#05b2c5">
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 5, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="<?= $themeURL ?>assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $themeURL ?>assets/img/icon/192x192.png">
    <link rel="manifest" href="__manifest.json">
    <script>let base_url = `<?= site_url() ?>`;</script>
    <link rel="stylesheet" href="<?= $themeURL ?>assets/css/style.css?v<?= $version ?>">
    <style>
    #appCapsule{ min-height: 100vh}
    .appBottomMenu, .appHeader, #appCapsule{ max-width: 768px; margin: 0 auto}
    .appHeader.bg-brand { box-shadow: none !important}
    .bg-brand{ background-color: #3BC0CF !important}
    .backlayer { width: 100%; height: 150px; position: absolute; z-index: -10; border-radius: 0 0 25px 25px}
    .glassmorph { backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.3)}
    .smallthin {  font-size: .74em; line-height: 14px !important; font-weight: 500;}
    .text-primary { color: #157CA1 !important}
    dl { margin-bottom: .5rem; }
    .page-content p, .page-content li { font-size: 1.1rem; line-height: 1.6rem; }
    </style>
</head>

<body>
    <!-- Content Section -->
    <?= $this->renderSection('content') ?>
    
    <!-- Script Packages -->
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
</body>
</html>