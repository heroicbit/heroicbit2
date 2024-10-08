<?= $this->extend('template/mobilekit/layouts/member') ?>

<?php $this->section('content') ?>

<div id="app" x-data="member()">
    
    <!-- Alpinejs Routers -->
    <div class="page-content">
        <template x-route="/intro" x-template.preload="/member/intro"></template>
        <template x-route="/kodepesantren" x-template.preload="/member/kodepesantren"></template>
        <template x-route="/login" x-template.preload="/member/login" x-handler="isKodePesantrenSet"></template>
        <template x-route="/register" x-template="/member/register" x-handler="isKodePesantrenSet"></template>
        <template x-route="/reset_password" x-template="/member/reset_password" x-handler="isKodePesantrenSet"></template>
        <template x-route="/" x-template.preload="/member/home" x-handler="isLoggedIn"></template>
        <template x-route="/feeds" x-template.preload="/member/feeds" x-handler="isLoggedIn"></template>
        <template x-route="/feed/:id" x-template.preload="/member/feed" x-handler="isLoggedIn"></template>
        <template x-route="/santri" x-template.preload="/member/santri" x-handler="isLoggedIn"></template>
        <template x-route="/profile" x-template.preload="/member/profile" x-handler="isLoggedIn"></template>
        <template x-route="/tagihan" x-template.preload="/member/tagihan" x-handler="isLoggedIn"></template>
        <template x-route="/components" x-template.preload="/member/component" x-handler="isLoggedIn"></template>
        <template x-route="/pengumuman" x-template.preload="/member/pengumuman" x-handler="isLoggedIn"></template>
    </div>

    <!-- AppBottomMenu -->
    <?= $this->include('Pages/member/bottommenu') ?>

</div>

<?php $this->endSection() ?>