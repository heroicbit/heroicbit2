<!DOCTYPE html><!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.2
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2023 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
--><!-- Breadcrumb-->
<html lang="en">

<head>
	<?= $this->include('template/admin/partials/head') ?>
</head>

<body>
	<?= $this->include('template/admin/partials/sidebar') ?>

	<div class="wrapper d-flex flex-column min-vh-100 bg-light">
		<?= $this->include('template/admin/partials/header') ?>

		<?= $this->renderSection('content') ?>

		<footer class="footer">
			<div><a href="https://coreui.io">CoreUI </a><a href="https://coreui.io">Bootstrap Admin Template</a> © 2023 creativeLabs.</div>
			<div class="ms-auto">Powered by&nbsp;<a href="https://coreui.io/docs/">CoreUI UI Components</a></div>
		</footer>
	</div>

	<!-- CoreUI and necessary plugins-->
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/simplebar/js/simplebar.min.js"></script>
	<!-- Plugins and scripts required by this view-->
	<script src="<?= setting('Theme.themeUrl') ?>vendors/chart.js/js/chart.min.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/utils/js/coreui-utils.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>js/main.js"></script>
	<script>
	</script>

</body>

</html>