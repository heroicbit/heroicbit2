<?= $this->extend('template/mobilekit/layouts/member') ?>

<?php $this->section('content') ?>

<!-- Define alpinejs router -->
<div id="app" x-data="member()">
    <div class="page-content">
        <template x-route="/intro" x-template="/member/intro"></template>
        <template x-route="/kodepesantren" x-template="/member/kodepesantren"></template>
        <template x-route="/login" x-template="/member/login" x-handler="isKodePesantrenSet"></template>
        <template x-route="/" x-template="/member/home" x-handler="isLoggedIn"></template>
        <template x-route="/info" x-template="/member/info" x-handler="isLoggedIn"></template>
        <template x-route="/santri" x-template="/member/santri" x-handler="isLoggedIn"></template>
        <template x-route="/profile" x-template="/member/profile" x-handler="isLoggedIn"></template>
        <template x-route="/tagihan" x-template="/member/tagihan" x-handler="isLoggedIn"></template>
        <template x-route="/components" x-template="/member/component" x-handler="isLoggedIn"></template>
    </div>

    <!-- App Bottom Menu -->
    <?= $this->include('Pages/member/bottommenu') ?>
    <!-- * App Bottom Menu -->
</div>

<?php $this->endSection() ?>