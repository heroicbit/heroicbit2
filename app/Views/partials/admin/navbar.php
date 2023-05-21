<header class="header no-print">
	<div class="header-block header-block-collapse d-lg-none d-xl-none">
		<button class="collapse-btn" id="sidebar-collapse-btn">
			<i class="fa fa-bars"></i>
		</button>
	</div>
	<div class="header-block-submenu">
		<?php
			$modules = $this->config->config['modules'];
			$entries = $this->config->config['entries'];
			$modules += $entries;
			if($submenu = $modules[$this->uri->segment(3)]['sub_menu'] ?? []){
				$moduleName = $this->uri->segment(3);
			} else {
				$submenu = $modules[$this->uri->segment(2)]['sub_menu'] ?? [];
				$moduleName = $this->uri->segment(2);
			}
			$this->event->trigger('admin_navbar.submenu', $submenu);

			if(empty($submodule)) $submodule = $moduleName;
		?>
		<?php if(!empty($submenu)): ?>
		<ul class="nav-submenu d-none d-md-block">
			<?php foreach ($submenu as $menu): ?>
				<?php if($this->ci_auth->isPermitted($menu['menu_permission'] ?? false, $moduleName)): ?>
					<li>
						<a href="<?php echo site_url($menu['url']);?>" class="<?= $submodule == $menu['submodule'] ? 'active' : ''; ?>">
							<span class="fa fa-<?= $menu['icon'] ?? 'menu'; ?>"></span>
							<?=t($menu['caption']); ?>	
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<select name="submenu" id="submenu" class="form-control d-block d-md-none">
			<?php foreach ($submenu as $menu): ?>
				<?php if($this->ci_auth->isPermitted($menu['menu_permission'] ?? false, $moduleName)): ?>
					<option value="<?php echo site_url($menu['url']);?>" <?= $submodule == $menu['submodule'] ? 'selected' : ''; ?>>
						<?= $menu['caption']; ?>
					</option>
					<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php endif; ?>
	</div>

	<div class="header-block header-block-nav">
		<ul class="nav-profile">
			<!-- <li class="text-white pr-2"><?= date("Y-m-d H:i:s"); ?></li> -->
			<!-- <li><a href="<?= site_url('admin/kelas/tahun'); ?>"><?= $this->config->config['tahun_ajaran']['year_label'] ?? ''; ?></a></li> -->
			<li class="profile dropdown">
				<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
					<span class="name"> <?= isLoggedIn('name');?> </span>
				</a>
				<div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
					<a class="dropdown-item" href="<?php echo site_url('user/logout')?>"><span class="fa fa-lock"></span> Logout</a>
				</div>
			</li>
		</ul>
	</div>
</header>
<script>
	$(function(){
		$('select[name=submenu]').on('change', function(){
			window.location.href = $(this).val();
		})
	})
</script>
