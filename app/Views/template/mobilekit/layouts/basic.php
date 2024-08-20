<?php 
$themeURL = service('settings')->get('Theme.frontendThemeURL'); 
$themePath = service('settings')->get('Theme.frontendThemePath'); ?>
<!DOCTYPE html>
<html lang="id" dir="ltr">
<head>
    <?= $this->include($themePath . 'partials/head') ?>
    <?= $this->include($themePath . 'partials/custom_css') ?>
</head>
<body>
    <div>
        <header class="shadow-sm">
            <?= $this->include($themePath . 'partials/navbar') ?>
        </header>

        <div id="main" style="max-width:1024px; margin:0 auto;">

            <!-- App Capsule -->
            <div id="appCapsule">
                <div class="appContent">
                    <?= $this->renderSection('content') ?>
                </div>

                <?= $this->renderSection('footer') ?>
            </div>
            <!-- * appCapsule -->

            <?= $this->include($themePath . 'partials/bottommenu') ?>

        </div>

        <?= $this->include($themePath . 'partials/sidebar') ?>

        <!-- Bootstrap-->
        <script src="<?=$themeURL?>assets/js/lib/bootstrap.min.js"></script>
        <!-- Ionicons -->
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <!-- Splide -->
        <script src="<?=$themeURL?>assets/js/plugins/splide/splide.min.js"></script>
        <!-- PJAX -->
        <script src="https://cdn.jsdelivr.net/npm/jquery-pjax@2.0.1/jquery.pjax.min.js"></script>
        <!-- Base Js File -->
        <script src="<?=$themeURL?>assets/js/base.js?v1.1"></script>  
    </div>
</body>
</html>