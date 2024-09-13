<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#157CA1">
<title></title>
<meta name="description" content="Mobilekit HTML Mobile UI Kit">
<meta name="keywords" content="bootstrap 5, mobile template, cordova, phonegap, mobile, html" />
<link rel="icon" type="image/png" href="<?= $themeURL ?>assets/img/favicon.png" sizes="32x32">
<link rel="apple-touch-icon" sizes="180x180" href="<?= $themeURL ?>assets/img/icon/192x192.png">
<link rel="manifest" href="__manifest.json">

<script>let base_url = `<?= site_url() ?>`;</script>
<link rel="stylesheet" href="<?= $themeURL ?>assets/css/style.css">
<style>
#appCapsule {
    min-height: 100vh;
}
.appBottomMenu, .appHeader, #appCapsule {
    max-width: 768px;
    margin: 0 auto;
}
.appHeader.bg-brand {
    box-shadow: none !important;
}
.bg-brand {
    background-color: #3BC0CF !important;
}
.backlayer {
    width: 100%;
    height: 150px;
    position: absolute;
    z-index: -10;
    border-radius: 0 0 25px 25px;
}
.glassmorph {
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  border: 1px solid rgba(255, 255, 255, 0.3);
}
.smallthin { 
    font-size: .74em;
    line-height: 14px !important;
    font-weight: 500; 
}
.text-primary {
    color: #157CA1 !important;
}
</style>