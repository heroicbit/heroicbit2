<?php
	$modules = $this->config->config['modules'];
	$entries = $this->config->config['entries'];
	$modules += $entries;
	
	ci()->event->trigger('AdminSidebar.modules', $modules);

	ci()->shared['parent_module'] = $modules[ci()->shared['current_module'] ?? '']['parent_module'] ?? ci()->shared['parent_module'] ?? '';
	
	$menuStructure = [];

	// Make menu from modules list
	foreach ($modules as $moduleName => $moduleDetail)
	{
		// eliminate module if user has no access to it
		$menu_permission = $moduleDetail['privileges'][0] ?? false;
		if($menu_permission && !isPermitted($menu_permission, $moduleName) || ($moduleDetail['enable'] ?? true) == false) continue;
		
		// Process only if module has module.yml
		if($moduleDetail['show_admin_menu'] ?? '')
		{
			$menu_url = $moduleDetail['custom_url'] 
									? $moduleDetail['custom_url'] 
									: 'admin/'.$moduleName;
			$menu_position = $moduleDetail['menu_position'] ?? 100;

			// If parent menu is not set, put directly right inside root
			if(empty($moduleDetail['parent_menu'])){
				$menuStructure[$menu_position] = [
					'url' => $menu_url,
					'module' => $moduleName,
					'icon' => $moduleDetail['icon'] ?? 'file',
					'caption' => $moduleDetail['name']
				];
			}

			// put inside parent menu first
			else {
				$parentMenuArr = explode(":", $moduleDetail['parent_menu']);
				$parent_position = $parentMenuArr[0];
				$parent_caption = $parentMenuArr[1];
				$parent_icon = isset($parentMenuArr[2]) ? $parentMenuArr[2] : 'list';

				// If parent menu is never set before, initiate
				if(!isset($menuStructure[$parent_position]))
					$menuStructure[$parent_position] = [
						'url' => '#',
						'module' => $moduleDetail['parent_menu'],
						'icon' => $parent_icon,
						'caption' => $parent_caption,
						'children' => []
					];

				// If custom_menu is set, use this as child menu
				if($moduleDetail['custom_menu'] ?? '')
				{
					foreach ($moduleDetail['custom_menu'] as $mkey => $custom_menu)
					{
						if(isset($custom_menu['menu_permission'])
						   && ! isPermitted($custom_menu['menu_permission'], $moduleName))
							unset($moduleDetail['custom_menu'][$mkey]);
					}

					if(! empty($moduleDetail['custom_menu']))
						$menuStructure[$parent_position]['children'] = $menuStructure[$parent_position]['children'] + $moduleDetail['custom_menu'];

					unset($moduleDetail['custom_menu']);
				}

				// Else, show this only menu
				else {
					$menuStructure[$parent_position]['children'] = ($menuStructure[$parent_position]['children'] ?? []) + [
						$menu_position => [
							'url' => $menu_url,
							'module' => $moduleName,
							'icon' => $moduleDetail['icon'] ?? 'list',
							'caption' => $moduleDetail['name'],
						]
					];
				}
			}
		}
	}

?>

<aside class="sidebar shadow" style="padding:0">
	<div class="sidebar-container">
		<div class="sidebar-header">
			<div class="brand p-0">
				<a href="<?= site_url(); ?>" target="_blank" style="color:white">
					<img src="<?= !empty(setting_item('site.site_logo')) ? setting_item('site.site_logo') : base_url('uploads/logo.png'); ?>" alt="" style="width:<?= setting_item('theme.admin_logo_width'); ?>; padding: 5px 20px;">
				</a>
				<?php if(trim($_ENV['CI_ENV']) == 'development'): ?>
				<div class="text-center bg-warning text-danger"><?=t('Development mode')?></div>
				<?php endif; ?>
			</div>
		</div>


		<nav class="menu mb-5">
			<ul class="sidebar-menu metismenu" id="sidebar-menu" data-parent-active="<?= '';?>">
				
				<?php
					$viewdata = [
						'structure' => $menuStructure,
						'theme' => $this->shared['theme']
					];

					// Run recursive rendering menu
					$this->load->view_partial('sidebar_menuitem', $viewdata, false); 
				?>

			</ul>
		</nav>
	</div>

</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
<div class="mobile-menu-handle"></div>

<?php unset($menuStructure); ?>
