<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
            <img src="<?= $themeURL ?>assets/img/logo.png" alt="" style="height: 70%">
        </div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox text-white">
                <ion-icon name="notifications"></ion-icon>
            </a>
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

        <div class="bg-success-2 rounded-bottom-4 pb-5" style="height: 150px; margin-bottom: 100px">

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

            <div class="section mt-3 mb-5" style="max-width:470px;margin:auto">
                <div class="card mb-3">
                    <div class="card-header p-1">
                        <img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" alt="image" class="imaged w32 rounded me-1">
                        <span>Edward Lindgren</span>
                    </div>
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="" alt="image">
                    <div class="card-body">
                        <small class="text-muted">19 September 2024</small>
                        <h5 class="card-title fs-5">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>

            </div>
            <hr class="pb-5">
        </div>

    </div>
    <!-- * App Capsule -->
</div>