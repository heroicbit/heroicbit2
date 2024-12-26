<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="member()">
        <div class="page-content">
            <template x-route="/" x-template.preload="/ajax/member/home" x-handler="isLoggedIn"></template>
            <template x-route="/intro" x-template.preload="/ajax/member/intro"></template>
            <template x-route="/kodepesantren" x-template.preload="/ajax/member/kodepesantren"></template>
            <template x-route="/login" x-template.preload="/ajax/member/login" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register" x-template="/ajax/member/register" x-handler="isKodePesantrenSet"></template>
            <template x-route="/delete" x-template="/ajax/member/profile_delete" x-handler="[isKodePesantrenSet,isLoggedIn]"></template>
            <template x-route="/register/confirm" x-template="/ajax/member/register/confirm" x-handler="isKodePesantrenSet"></template>
            <template x-route="/reset_password" x-template="/ajax/member/reset_password" x-handler="isKodePesantrenSet"></template>
            <template x-route="/page/:slug" x-template="/ajax/member/page"></template>
            <template x-route="/feeds" x-template.preload="/ajax/member/feeds" x-handler="isLoggedIn"></template>
            <template x-route="/feeds/:id" x-template.preload="/ajax/member/feeds/detail" x-handler="isLoggedIn"></template>
            <template x-route="/videos" x-template.preload="/ajax/member/videos" x-handler="isLoggedIn"></template>
            <template x-route="/videos/:id" x-template.preload="/ajax/member/videos/detail" x-handler="isLoggedIn"></template>
            <template x-route="/santri" x-template.preload="/ajax/member/santri" x-handler="isLoggedIn"></template>
            <template x-route="/profile" x-template.preload="/ajax/member/profile" x-handler="isLoggedIn"></template>
            <template x-route="/profile_edit" x-template.preload="/ajax/member/profile_edit" x-handler="isLoggedIn"></template>
            <template x-route="/tagihan" x-template.preload="/ajax/member/tagihan" x-handler="isLoggedIn"></template>
            <template x-route="/pengumuman" x-template.preload="/ajax/member/pengumuman" x-handler="isLoggedIn"></template>
            <template x-route="/program_pesantren" x-template.preload="/ajax/member/program_pesantren" x-handler="isLoggedIn"></template>
        </div>

        <?= $this->include('member/bottommenu') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->