<?php if($bottommenu ?? null): ?>
<div class="appBottomMenu no-border shadow-lg" x-show="Alpine.store('member').showBottomMenu">
    <?php foreach($bottommenu as $menu): ?>
    <a href="<?= $menu['url'] ?>" id="bottommenu-member" class="item">
        <div class="col">
        <img src="<?= $menu['icon'] ?>" style="width:28px">
            <strong><?= $menu['label'] ?></strong>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>