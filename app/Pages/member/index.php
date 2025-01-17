<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="router()">
        <div class="page-content">
            <!-- Beranda -->
            <template 
                x-route="/" 
                x-template.preload="['/member/home/content?pid=' + pesantrenID, '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Intro -->
            <template 
                x-route="/intro" 
                x-template.preload="['/member/intro/content']"
                ></template>
            <!-- Kode Pesantren -->
            <template 
                x-route="/kodepesantren" 
                x-template.preload="['/member/kodepesantren/content']"
                ></template>
            <!-- Login -->
            <template 
                x-route="/login" 
                x-template.preload="['/member/login/content']" 
                x-handler="isKodePesantrenSet"
                ></template>
            <!-- Register -->
            <template 
                x-route="/register" 
                x-template="['/member/register/content']" 
                x-handler="isKodePesantrenSet"
                ></template>
            <!-- Confirm Register -->
            <template 
                x-route="/register/confirm" 
                x-template="['/member/register/confirm/content']" 
                x-handler="isKodePesantrenSet"
                ></template>
            <!-- Reset Password -->
            <template 
                x-route="/reset_password" 
                x-template="['/member/reset_password/content']" 
                x-handler="isKodePesantrenSet"
                ></template>
            <!-- Change Password -->
            <template 
                x-route="/reset_password/change/:token" 
                x-template="['/member/reset_password/change/content']" 
                x-handler="isKodePesantrenSet"
                ></template>
            <!-- Page -->
            <template 
                x-route="/page/:slug" 
                x-template="['/member/page/content', '/_components/bottommenu?pid=' + pesantrenID]"
                ></template>
            <!-- Feeds -->
            <template 
                x-route="/feeds" 
                x-template.preload="['/member/feeds/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Detail feed -->
            <template 
                x-route="/feeds/:id" 
                x-template.preload="['/member/feeds/detail/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Videos -->
            <template 
                x-route="/videos" 
                x-template.preload="['/member/videos/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Detail video -->
            <template 
                x-route="/videos/:id" 
                x-template.preload="['/member/videos/detail/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Videos -->
            <template 
                x-route="/kajian" 
                x-template.preload="['/member/kajian/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Detail video -->
            <template 
                x-route="/kajian/:id" 
                x-template.preload="['/member/kajian/detail/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Santri -->
            <template 
                x-route="/santri" 
                x-template.preload="['/member/santri/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Profile -->
            <template 
                x-route="/profile" 
                x-template.preload="['/member/profile/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Profile Delete -->
            <template 
                x-route="/profile/delete" 
                x-template="['/member/profile/delete/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="[isKodePesantrenSet,isLoggedIn]"
                ></template>
            <!-- Profile Edit -->
            <template 
                x-route="/profile_edit" 
                x-template.preload="['/member/profile_edit/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Tagihan -->
            <template 
                x-route="/tagihan" 
                x-template.preload="['/member/tagihan/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Pengumuman -->
            <template 
                x-route="/pengumuman" 
                x-template.preload="['/member/pengumuman/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
            <!-- Program Pesantren -->
            <template 
                x-route="/program_pesantren" 
                x-template.preload="['/member/program_pesantren/content', '/_components/bottommenu?pid=' + pesantrenID]" 
                x-handler="isLoggedIn"
                ></template>
                <!-- 404 Page Not Found -->
                <template 
                x-route="notfound"
                x-template.preload="/member/notfound/content" 
                ></template>
        </div>

    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->