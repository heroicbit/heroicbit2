<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-primary scrolled">
        <div class="left">
        </div>
        <div class="pageTitle" x-text="title"></div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow">

        <div class="header-large-title mb-3">
            <h1 class="title" x-text="title"></h1>
            <h4 class="subtitle"></h4>
        </div>

        <div class="bg-success-2 pt-5 rounded-bottom-4" style="height: 150px; margin-bottom: 100px">

            <div class="container">
                <div class="card bg-light rounded-4 py-3 px-4 glassmorph">
                    <div class="d-flex justify-content-center text-center">
                        <div style="max-width: 90px">
                        <a href="/pengumuman">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/pengumuman.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Pengumuman</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/santri">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/data-santri.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Santri</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/presensi">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/presensi.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Presensi</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="/tagihan">
                            <img src="<?= $themeURL ?>assets/img/walisantri/icons/billing.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Tagihan</small>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="ais-InstantSearch">
            <div id="searchbox" class="px-3"></div>
            <div id="hits"></div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>