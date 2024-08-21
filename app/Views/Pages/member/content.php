<?= $this->extend('template/mobilekit/layouts/member') ?>

<?php $this->section('content') ?>

<!-- Define alpinejs router -->
<div id="app" x-data>
    <template x-route="/" x-template="/member/home"></template>
    <template x-route="/components" x-template="/member/component"></template>

    <!-- App Bottom Menu -->
    <?= $this->include('Pages/member/bottommenu') ?>
    <!-- * App Bottom Menu -->
</div>

<?php $this->endSection() ?>