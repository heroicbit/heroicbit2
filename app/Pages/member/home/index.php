<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle" x-show="data.logo">
            <img :src="data.logo ? data.logo : `<?= $themeURL ?>assets/img/logo.png`" alt="" style="height: 36px">
        </div>
        <div class="right">
            <!-- <a href="#" class="headerButton toggle-searchbox text-white">
                <ion-icon name="notifications"></ion-icon>
            </a> -->
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-brand backlayer"></div>
        <div class="header-large-title my-3 ms-1">
            <div class="d-flex align-items-center justify-content-start gap-3">
                <div class="use text-white">
                    <div>Ahlan wa sahlan,</div>
                    <div class="h5 m-0" x-text="Alpine.store('tarbiyya').user.name"></div>
                </div>
            </div>
        </div>

        <?= $this->include('member/home/_icons') ?>

        <?= $this->include('member/home/_pengumuman') ?>

        <?= $this->include('member/home/_videos') ?>

        <?= $this->include('member/home/_articles') ?>

    </div>
    <!-- * App Capsule -->
</div>