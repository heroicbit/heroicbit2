<div class="bg-success-2 rounded-bottom-4 pb-2">
    <style>
        .shrink { height: 195px !important }
    </style>

    <div class="container">    
        <div class="card bg-white rounded-4 py-3 px-0 mb-4 overflow-hidden" :class="showAllIcons ? '' : 'shrink'" style="height:450px;transition:.5s ease">
            <div class="text-center mb-2 mx-auto" style="width:340px">
                <?php if(($settings['fitur_profil_pesantren'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/page/profil-pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/profil-pesantren-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Profil Pesantren</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_program_pesantren'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/member/program_pesantren">
                        <img src="<?= $themeURL ?>assets/img/icon/program.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Program Pesantren</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_psb'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a :href="data.psb_url" target="_blank">
                        <img src="<?= $themeURL ?>assets/img/icon/psb-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">PSB</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_pengumuman'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/pengumuman">
                        <img src="<?= $themeURL ?>assets/img/icon/pengumuman-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Pengumuman</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_kabar'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/feeds">
                        <img src="<?= $themeURL ?>assets/img/icon/info-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Kabar</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_video'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/videos">
                        <img src="<?= $themeURL ?>assets/img/icon/video.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Video</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_santri'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/santri">
                        <img src="<?= $themeURL ?>assets/img/icon/santri-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Santri</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['show_button_lainnya'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px" x-on:click="showAllIcons = true">
                    <a x-show="!showAllIcons" x-transition.duration.300ms>
                        <img src="<?= $themeURL ?>assets/img/icon/lainnya-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Lainnya</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_tagihan'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/tagihan">
                        <img src="<?= $themeURL ?>assets/img/icon/tagihan-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Tagihan</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_agenda'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/agenda-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Agenda</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_rapor'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/rapor-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Rapor Digital</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_kajian'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/kajian-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Kajian</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_jadwal_sholat'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/jadwal-sholat-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Jadwal Sholat</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_hadits_arbain'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/hadits-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Hadits Arba'in</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_doa_zikir'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a class="opacity-50" x-on:click="comingsoon = true">
                        <img src="<?= $themeURL ?>assets/img/icon/doa-min.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Doa & Zikir</small>
                    </a>
                </div>
                <?php endif; ?>

                <?php if(($settings['fitur_profile'] ?? null) == 1): ?>
                <div class="float-start" style="width:85px;height:90px">
                    <a href="/profile">
                        <img src="<?= $themeURL ?>assets/img/icon/user.png" style="max-width:48px">
                        <small class="smallthin mt-1 text-primary d-block" style="line-height: 18px">Profil</small>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-2">
                <button class="btn btn-sm btn-link text-primary py-1" x-on:click="showAllIcons = false">Sembunyikan <span class="bi bi-chevron-double-up ms-1"></span></button>
            </div>
        </div>

    </div>
</div>

<div id="toast-comingsoon" 
    class="toast-box toast-bottom" 
    :class="comingsoon ? 'show' : ''"
    x-init="$watch('comingsoon', v => v ? setTimeout(() => comingsoon = false, 3000) : null)">
    <div class="in">
        <div class="text">Fitur ini masih dalam proses pengembangan.</div>
    </div>
    <button type="button" class="btn btn-sm btn-text-light" x-on:click="comingsoon = false">OK</button>
</div>