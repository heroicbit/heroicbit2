<?= $this->extend('template/layout_member') ?>

<!-- START Content Section -->
<?php $this->section('content') ?>

    <!-- Alpinejs Routers -->
    <div id="app" x-data="member()">
        <div class="page-content">
            <div id="member_offline" x-data="member_offline()">
                <div class="appHeader">
                    <div class="left">
                    </div>
                    <div class="pageTitle" x-text="title"></div>
                    <div class="right">
                    </div>
                </div>

                <!-- App Capsule -->
                <div id="appCapsule">

                    <div class="section mt-2">
                        <div class="page-content p-0 pb-5">
                            <img src="<?= $themeURL ?>assets/img/icon/offline.png" alt="offline-robot" class="w-100">
                        </div>
                    </div>
                </div>
                <!-- * App Capsule -->
            </div>
        </div>

        <?= $this->include('member/bottommenu') ?>
    </div>

<?php $this->endSection() ?>
<!-- END Content Section -->
