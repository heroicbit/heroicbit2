<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php $this->load->view('admin/partials/header'); ?>
</head>
<body>

<!-- Bridge to Js -->
<input type="hidden" id="base_url" value="<?php echo base_url()?>">

	<div id="<?= setting_item('theme.enable_pjax_admin') == '1' ? 'pjax-container' : ''; ?>" class="main-wrapper sidebar-fixed">
		<div class="app" id="app">
			<div class="bgtop"></div>

			<?php $this->load->view('admin/partials/navbar'); ?>

			<?php $this->load->view('admin/partials/sidebar'); ?>

			<article class="content dashboard-page">
				
				<?php echo $this->session->flashdata('message');?>

				<?php echo $content; ?>

			</article>
		</div>

		<!-- Footer script MUST placed inside #pjax-container -->
		<?php $this->load->view('admin/partials/footer'); ?>
	</div>

</body>
</html>

<?php endif;?>