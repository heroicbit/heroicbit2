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
    <div id="appCapsule" class="shadow">

        <div class="bg-brand backlayer"></div>
        <div class="header-large-title my-3 ms-1">
            <div class="d-flex justify-content-start">
                <img src="<?= $themeURL; ?>assets/img/walisantri/avatar.png" alt="image" class="imaged w48 rounded">
                <div class="ms-1 d-flex flex-column justify-content-center">
                    <h5 class="fs-6 mb-0 text-white">Toni Haryanto</h5>
                    <smal class="mb-0 text-white">Wali Santri</smal>
                </div>
            </div>
        </div>

        <div class="bg-success-2 rounded-bottom-4" style="height: 150px; margin-bottom: 100px">

            <div class="container">
                <div class="card bg-light rounded-4 py-3 px-4 glassmorph">
                    <div class="d-flex justify-content-center text-center">
                        <div style="max-width: 90px">
                        <a href="/pengumuman">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/profil-pesantren.png" style="width: 50%">
                            <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Profil Pesantren</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/santri">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/pengumuman.png" style="width: 50%">
                            <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Pengumuman</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/presensi">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/presensi.png" style="width: 50%">
                            <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Presensi</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/tagihan">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/billing.png" style="width: 50%">
                            <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Tagihan</small>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
  
        </div>
        
    </div>
    <!-- * App Capsule -->
</div>