<?php 
$ThemeConfig = config('Config\\Theme'); 
$themeURL = $ThemeConfig->adminThemeURL; 
$themePath = $ThemeConfig->adminThemePath; 
?>
<!DOCTYPE html><!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.2
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2023 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
--><!-- Breadcrumb-->
<html lang="en">

<head>
	<?= $this->include($themePath . 'partials/head') ?>
</head>

<body>
	<?= $this->include($themePath . 'partials/sidebar') ?>

	<div class="wrapper d-flex flex-column min-vh-100 bg-light">
		<?= $this->include($themePath . 'partials/header') ?>

		<div class="body flex-grow-1 px-3">
			<?= $this->renderSection('content') ?>
		</div>

		<footer class="footer">
			<div><a href="https://coreui.io">CoreUI </a><a href="https://coreui.io">Bootstrap Admin Template</a> © 2023 creativeLabs.</div>
			<div class="ms-auto">Powered by&nbsp;<a href="https://coreui.io/docs/">CoreUI UI Components</a></div>
		</footer>
	</div>

	<!-- CoreUI and necessary plugins-->
	<script src="<?= $themeURL ?>vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
	<script src="<?= $themePath ?>vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
	<script src="<?= $themeURL ?>vendors/simplebar/js/simplebar.min.js"></script>
	<script src="<?= $themePath ?>vendors/simplebar/js/simplebar.min.js"></script>
	<!-- Plugins and scripts required by this view-->
	<script src="<?= $themeURL ?>vendors/chart.js/js/chart.min.js"></script>
	<script src="<?= $themePath ?>vendors/chart.js/js/chart.min.js"></script>
	<script src="<?= $themeURL ?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
	<script src="<?= $themePath ?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
	<script src="<?= $themeURL ?>vendors/@coreui/utils/js/coreui-utils.js"></script>
	<script src="<?= $themePath ?>vendors/@coreui/utils/js/coreui-utils.js"></script>
	<script src="<?= $themeURL ?>js/main.js"></script>
	<script src="<?= $themePath ?>js/main.js"></script>
	<script>
	</script>

</body>

</html>