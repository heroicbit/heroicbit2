<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="member()">
        <div class="page-content">
            <template x-route="/" x-template.preload="/member/home?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/intro" x-template.preload="/member/intro?get=ajax"></template>
            <template x-route="/kodepesantren" x-template.preload="/member/kodepesantren?get=ajax"></template>
            <template x-route="/login" x-template.preload="/member/login?get=ajax" x-handler="isKodePesantrenSet"></template>
            <template x-route="/register" x-template="/member/register?get=ajax" x-handler="isKodePesantrenSet"></template>
            <template x-route="/delete" x-template="/member/profile_delete?get=ajax" x-handler="[isKodePesantrenSet,isLoggedIn]"></template>
            <template x-route="/register/confirm" x-template="/member/register/confirm?get=ajax" x-handler="isKodePesantrenSet"></template>
            <template x-route="/reset_password" x-template="/member/reset_password?get=ajax" x-handler="isKodePesantrenSet"></template>
            <template x-route="/page/:slug" x-template="/member/page?get=ajax"></template>
            <template x-route="/feeds" x-template.preload="/member/feeds?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/feeds/:id" x-template.preload="/member/feeds/detail?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/videos" x-template.preload="/member/videos?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/videos/:id" x-template.preload="/member/videos/detail?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/santri" x-template.preload="/member/santri?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/profile" x-template.preload="/member/profile?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/profile_edit" x-template.preload="/member/profile_edit?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/tagihan" x-template.preload="/member/tagihan?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/pengumuman" x-template.preload="/member/pengumuman?get=ajax" x-handler="isLoggedIn"></template>
            <template x-route="/program_pesantren" x-template.preload="/member/program_pesantren?get=ajax" x-handler="isLoggedIn"></template>
        </div>

        <?= $this->include('member/bottommenu') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->