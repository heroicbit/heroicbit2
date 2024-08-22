<?= $this->extend('template/mobilekit/layouts/member') ?>

<?php $this->section('content') ?>

<!-- Define alpinejs router -->
<div id="app" x-data="member()">
    <div class="page-content">
        <template x-route="/login" x-template="/member/login"></template>
        <template x-route="/" x-template="/member/home"></template>
        <template x-route="/components" x-template="/member/component" x-handler="isLoggedIn"></template>
    </div>

    <!-- App Bottom Menu -->
    <?= $this->include('Pages/member/bottommenu') ?>
    <!-- * App Bottom Menu -->
</div>

<?php $this->endSection() ?>