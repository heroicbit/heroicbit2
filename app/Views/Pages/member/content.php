<?= $this->extend('template/mobilekit/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="member()">
        <div class="page-content">
            <template x-route="/" x-template.preload="/pages/member/home" x-handler="isLoggedIn"></template>
            <template x-route="/intro" x-template.preload="/pages/member/intro"></template>
            <template x-route="/kodepesantren" x-template.preload="/pages/member/kodepesantren"></template>
            <template x-route="/login" x-template.preload="/pages/member/login" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register" x-template="/pages/member/register" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register_confirm/:token" x-template="/pages/member/register_confirm" x-handler="isKodePesantrenSet"></template>
            <template x-route="/reset_password" x-template="/pages/member/reset_password" x-handler="isKodePesantrenSet"></template>
            <template x-route="/page/:slug" x-template="/pages/member/page"></template>
            <template x-route="/feeds" x-template.preload="/pages/member/feeds" x-handler="isLoggedIn"></template>
            <template x-route="/feed/:id" x-template.preload="/pages/member/feed" x-handler="isLoggedIn"></template>
            <template x-route="/videos" x-template.preload="/pages/member/videos" x-handler="isLoggedIn"></template>
            <template x-route="/video/:id" x-template.preload="/pages/member/video" x-handler="isLoggedIn"></template>
            <template x-route="/santri" x-template.preload="/pages/member/santri" x-handler="isLoggedIn"></template>
            <template x-route="/profile" x-template.preload="/pages/member/profile" x-handler="isLoggedIn"></template>
            <template x-route="/tagihan" x-template.preload="/pages/member/tagihan" x-handler="isLoggedIn"></template>
            <template x-route="/pengumuman" x-template.preload="/pages/member/pengumuman" x-handler="isLoggedIn"></template>
        </div>

        <?= $this->include('Pages/member/bottommenu') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->