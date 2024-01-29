<?php $theme_url = config('Config\\Theme')->adminThemeURL; ?>
<?php $menu = config('Config\\SidebarMenu')->menu; ?>

<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
	<div class="sidebar-brand d-none d-md-flex">
		<svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
			<use xlink:href="<?= $theme_url ?>assets/brand/coreui.svg#full"></use>
		</svg>
		<svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
			<use xlink:href="<?= $theme_url ?>assets/brand/coreui.svg#signet"></use>
		</svg>
	</div>
	<ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
		<?php foreach ($menu ?? [] as $key => $item): ?>
			<?php if (! empty($item['children'])): ?>
				<li class="nav-group">
					<a class="nav-link nav-group-toggle" href="<?= $item['url'] ?>">
						<i class="nav-icon <?= $item['icon'] ?>"></i>
						<?= $item['caption'] ?>
					</a>
					<ul class="nav-group-items">
						<?php foreach ($item['children'] as $key => $child): ?>
							<li class="nav-item">
								<a class="nav-link" href="<?= site_url($child['url']) ?>">
									<i class="nav-icon <?= $child['icon'] ?>"></i> 
									<?= $child['caption'] ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php else: ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= site_url($item['url']) ?>">
						<i class="nav-icon <?= $item['icon'] ?>"></i> 
						<?= $item['caption'] ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>