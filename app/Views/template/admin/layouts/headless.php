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
	<?= $this->renderSection('main') ?>

	<?= $this->renderSection('script') ?>
	<!-- CoreUI and necessary plugins-->
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/simplebar/js/simplebar.min.js"></script>
	<!-- Plugins and scripts required by this view-->
	<script src="<?= setting('Theme.themeUrl') ?>vendors/chart.js/js/chart.min.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>vendors/@coreui/utils/js/coreui-utils.js"></script>
	<script src="<?= setting('Theme.themeUrl') ?>js/main.js"></script>
	<?php $this->endSection() ?>

</body>

</html>