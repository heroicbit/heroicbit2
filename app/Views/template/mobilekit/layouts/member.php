<?php 
$themeURL = service('settings')->get('Theme.frontendThemeURL'); 
$themePath = service('settings')->get('Theme.frontendThemePath'); ?>
<!doctype html>
<html lang="en">
<head>
    <?= $this->include($themePath . 'partials/member/head') ?>
</head>
<body>
    <?= $this->renderSection('content') ?>

    <?= $this->include($themePath . 'partials/member/footer_script') ?>
</body>

</html>