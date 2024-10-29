<div class="bg-success-2 rounded-bottom-4 pb-5">
    <div class="container">
        
        <div class="card bg-white rounded-4 py-3 px-4 mb-4">
            <div class="d-flex justify-content-center text-center mb-2">
                <div style="width: 85px">
                    <a href="/page/profil-pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/profil-pesantren-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" 
                            style="line-height: 18px">Profil Pesantren</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/presensi">
                        <img src="<?= $themeURL ?>assets/img/icon/presensi-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Program</small>
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
            </div>

            <div class="d-flex justify-content-center text-center">
                <div style="width: 85px">
                    <a href="/page/profil-pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" 
                            style="line-height: 18px">PSB</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/santri">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Rapor Digital</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/pengumuman">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Video</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/presensi" x-show="showAllIcons">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Kabar</small>
                    </a>
                    <a href="javascript:void()" x-show="!showAllIcons" x-on:click="showAllIcons = true">
                        <img src="<?= $themeURL ?>assets/img/icon/lainnya-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Lainnya</small>
                    </a>
                </div>
            </div>
            
            <!-- Other Menus -->
            <div x-show="showAllIcons" x-transition>
            <div class="d-flex justify-content-center text-center mt-2">
                <div style="width: 85px">
                    <a href="/page/profil-pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" 
                            style="line-height: 18px">Kajian</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/santri">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Fatwa PERSIS</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/pengumuman">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Doa & Zikir</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/presensi">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Hadits Arba'in</small>
                    </a>
                </div>
            </div>
            
            <div class="d-flex justify-content-center text-center mt-2">
                <div style="width: 85px">
                    <a href="/page/profil-pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" 
                            style="line-height: 18px">Risalah Sholat</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/santri">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Jadwal Sholat</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/pengumuman">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Arah Qiblat</small>
                    </a>
                </div>
                <div style="width: 85px">
                    <a href="/presensi">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block"
                            style="line-height: 18px">Majalah Risalah</small>
                    </a>
                </div>
            </div>

            <div class="text-center mt-2">
                <button class="btn btn-sm btn-link text-primary py-1" x-on:click="showAllIcons = false">Sembunyikan <span class="bi bi-chevron-double-up ms-1"></span></button>
            </div>
        </div>

    </div>
</div>