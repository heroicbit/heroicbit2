<?php if($bottommenu ?? null): ?>
<div class="appBottomMenu no-border shadow-lg">
    <?php foreach($bottommenu as $menu): ?>
    <a href="<?= $menu['url'] ?>" 
        id="bottommenu-member" 
        class="item" 
        :class="Alpine.store('masagi').currentPage == '<?= trim($menu['url'], '/') ?>' ? 'active' : ''"
        >
        <div class="col">
            <?= $menu['icon'] ?>
            <strong><?= $menu['label'] ?></strong>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>