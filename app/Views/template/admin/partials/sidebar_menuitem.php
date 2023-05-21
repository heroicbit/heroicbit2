<?php ksort($structure); ?>
<?php foreach($structure as $sort_id => $detail): ?>
	
	<?php // If menu has children, do recursive rendering
		if(isset($detail['children']))
			if(empty($detail['children']))
				continue;
			else
				$viewdata = [
					'structure' => $detail['children'],
					'theme' => $theme
				];
	?>
	<li data-order="menu-<?= $sort_id; ?>" class="<?= $detail['module'] == ((!empty($this->shared['parent_module']) ? $this->shared['parent_module'] : null) ?? $this->shared['current_module'] ?? $this->_module) ? 'active mm-active' : ''; ?>" rel="<?= $this->uri->uri_string().' - '.$detail['url']; ?>" data-module="<?= $detail['module']; ?>">
		<a <?php echo $detail['url'] != '#' ? 'href="'.site_url($detail['url']).'"' : ''; ?>>
			<i class="fa fa-fw fa-<?php echo $detail['icon'];?>"></i> 
			<?php echo t($detail['caption']); ?>
			<?php if(isset($detail['children'])) echo '<i class="fa arrow"></i>'; ?>
		</a>

		<?php if(isset($viewdata)): ?>
		<ul class="sidebar-nav">
			<?php $this->load->view_partial('sidebar_menuitem', $viewdata, false); ?>
		</ul>
		<?php endif; ?>
	</li>

<?php endforeach; ?>