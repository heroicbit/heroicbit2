<!doctype html>
<html class="no-js" lang="en">
<head>
	<?= $this->include('template/admin/partials/header') ?>
</head>
<body>

<!-- Bridge to Js -->
<input type="hidden" id="base_url" value="<?php echo base_url()?>">

	<div id="pjax-container" class="main-wrapper sidebar-fixed">
		<div class="app" id="app">
			<div class="bgtop"></div>

			<!-- <?php//= $this->include('template/admin/partials/navbar') ?> -->
			
			<!-- <?php//= $this->include('template/admin/partials/sidebar') ?> -->

			<article class="content dashboard-page">

				<?= $this->renderSection('content') ?>

			</article>
		</div>

		<!-- Footer script MUST placed inside #pjax-container -->
		<!-- <?php//= $this->include('template/admin/partials/footer') ?> -->
	</div>

</body>
</html>
