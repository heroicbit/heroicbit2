<?php $theme_url = config('Config\\Theme')->adminThemeURL; ?>

<base href="./">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
<meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
<meta name="author" content="Łukasz Holeczek">
<meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
<title><?= $page_title ?? $this->renderSection('title'); ?></title>
<link rel="apple-touch-icon" sizes="57x57" href="<?= $theme_url ?>assets/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?= $theme_url ?>assets/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?= $theme_url ?>assets/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?= $theme_url ?>assets/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?= $theme_url ?>assets/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?= $theme_url ?>assets/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?= $theme_url ?>assets/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?= $theme_url ?>assets/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $theme_url ?>assets/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192" href="<?= $theme_url ?>assets/favicon/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?= $theme_url ?>assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?= $theme_url ?>assets/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= $theme_url ?>assets/favicon/favicon-16x16.png">
<link rel="manifest" href="<?= $theme_url ?>assets/favicon/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<!-- Vendors styles-->
<link rel="stylesheet" href="<?= $theme_url ?>vendors/simplebar/css/simplebar.css">
<link rel="stylesheet" href="<?= $theme_url ?>css/vendors/simplebar.css">
<!-- Main styles for this application-->
<link href="<?= $theme_url ?>css/style.css" rel="stylesheet">
<!-- We use those styles to show code examples, you should remove them in your application.-->
<link href="<?= $theme_url ?>css/examples.css" rel="stylesheet">
<link href="<?= $theme_url ?>vendors/@coreui/chartjs/css/coreui-chartjs.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
