<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
            <img src="<?= $themeURL ?>assets/img/logo.png" alt="" style="height: 70%">
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
                <div><img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" class="rounded-circle"
                        alt="Toni Haryanto" style="width:56px"></div>
                <div class="use text-white">
                    <div class="h5 m-0">Toni Haryanto</div>
                    <div>Wali santri</div>
                </div>
            </div>
        </div>

        <div class="bg-success-2 rounded-bottom-4 pb-5">

            <div class="container">
                <div class="card bg-white rounded-4 py-3 px-4">
                    <div class="d-flex justify-content-center text-center">
                        <div style="width: 85px">
                            <a href="/page/profil-pesantren">
                                <img src="<?= $themeURL ?>assets/img/icon/profil-pesantren-min.png" style="max-width:48px">
                                <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Profil
                                    Pesantren</small>
                            </a>
                        </div>
                        <div style="width: 85px">
                            <a href="/santri">
                                <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                                <small class="smallthin mt-1 text-primary d-block"
                                    style="line-height: 18px">Agenda</small>
                            </a>
                        </div>
                        <div style="width: 85px">
                            <a href="/pengumuman">
                                <img src="<?= $themeURL ?>assets/img/icon/pengumuman-min.png" style="max-width:48px">
                                <small class="smallthin mt-1 text-primary d-block"
                                    style="line-height: 18px">Pengumuman</small>
                            </a>
                        </div>
                        <div style="width: 85px">
                            <a href="/presensi">
                                <img src="<?= $themeURL ?>assets/img/icon/presensi-min.png" style="max-width:48px">
                                <small class="smallthin mt-1 text-primary d-block"
                                    style="line-height: 18px">Presensi</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->include('Pages/member/home/_pengumuman') ?>

        <?= $this->include('Pages/member/home/_videos') ?>

        <?= $this->include('Pages/member/home/_articles') ?>

    </div>
    <!-- * App Capsule -->
</div>