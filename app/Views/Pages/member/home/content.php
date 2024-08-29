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
    <div id="appCapsule">

        <div class="header-large-title mb-3">
            <h1 class="title" x-text="title"></h1>
            <h4 class="subtitle"></h4>
        </div>

        <div class="bg-success-2 pt-5 rounded-bottom-4" style="height: 150px; margin-bottom: 100px">

            <div class="container">
                <div class="card bg-light rounded-4 py-3 px-4 glassmorph">
                    <div class="d-flex justify-content-center text-center">
                        <div style="max-width: 90px">
                        <a href="https://pesantrenku.test/pengumuman">
                            <img src="https://pesantrenku.test/views/pesantrenku/assets/wali/img/icons/pengumuman.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Pengumuman</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="https://pesantrenku.test/santri">
                            <img src="https://pesantrenku.test/views/pesantrenku/assets/wali/img/icons/data-santri.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Santri</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="https://pesantrenku.test/presensi">
                            <img src="https://pesantrenku.test/views/pesantrenku/assets/wali/img/icons/presensi.png" style="width: 50%">
                            <small class="smallthin mt-2 text-primary d-block" style="line-height: 18px">Presensi</small>
                        </a>
                        </div>
                        <div style="max-width: 90px">
                        <a href="https://pesantrenku.test/tagihan">
                            <img src="https://pesantrenku.test/views/pesantrenku/assets/wali/img/icons/billing.png" style="width: 50%">
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