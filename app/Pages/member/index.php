<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="member()">
        <div class="page-content">
            <template x-route="/" x-template.preload="/member/home/content" x-handler="isLoggedIn"></template>
            <template x-route="/intro" x-template.preload="/member/intro/content"></template>
            <template x-route="/kodepesantren" x-template.preload="/member/kodepesantren/content"></template>
            <template x-route="/login" x-template.preload="/member/login/content" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register" x-template="/member/register/content" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register/confirm" x-template="/member/register/confirm/content" x-handler="isKodePesantrenSet"></template>
            <template x-route="/reset_password" x-template="/member/reset_password/content" x-handler="isKodePesantrenSet"></template>
            <template x-route="/page/:slug" x-template="/member/page/content"></template>
            <template x-route="/feeds" x-template.preload="/member/feeds/content" x-handler="isLoggedIn"></template>
            <template x-route="/feeds/:id" x-template.preload="/member/feeds/detail/content" x-handler="isLoggedIn"></template>
            <template x-route="/videos" x-template.preload="/member/videos/content" x-handler="isLoggedIn"></template>
            <template x-route="/videos/:id" x-template.preload="/member/videos/detail/content" x-handler="isLoggedIn"></template>
            <template x-route="/santri" x-template.preload="/member/santri/content" x-handler="isLoggedIn"></template>
            <template x-route="/profile" x-template.preload="/member/profile/content" x-handler="isLoggedIn"></template>
            <template x-route="/profile/delete" x-template="/member/profile/delete/content" x-handler="[isKodePesantrenSet,isLoggedIn]"></template>
            <template x-route="/profile_edit" x-template.preload="/member/profile_edit/content" x-handler="isLoggedIn"></template>
            <template x-route="/tagihan" x-template.preload="/member/tagihan/content" x-handler="isLoggedIn"></template>
            <template x-route="/pengumuman" x-template.preload="/member/pengumuman/content" x-handler="isLoggedIn"></template>
            <template x-route="/program_pesantren" x-template.preload="/member/program_pesantren/content" x-handler="isLoggedIn"></template>
        </div>

        <?= $this->include('member/bottommenu') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->