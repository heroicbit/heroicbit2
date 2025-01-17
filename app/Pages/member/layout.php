<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="router()">
        <?= $this->include('member/router') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->