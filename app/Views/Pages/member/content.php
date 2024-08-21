<?= $this->extend('template/mobilekit/layouts/basic') ?>

<?php $this->section('content') ?>

<!-- Define alpinejs router -->
<div id="app" x-data>
    <template x-route="/" x-template="/member/home"></template>
    <template x-route="/components" x-template="/member/component"></template>
</div>

<?php $this->endSection() ?>