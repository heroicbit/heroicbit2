<div id="member_santri" x-data="member_santri()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Daftar Santri</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-success-2 rounded-bottom-4 pb-5" style="height: 150px; margin-bottom: 100px">

            <div class="section mt-0 p-0">
                <ul class="listview image-listview media mb-2">
                    <template x-for="(santri,santriIndex) in data.santri">
                    <li>
                        <a href="javascript:void()" class="item" x-on:click="showDetail(santriIndex)" data-bs-toggle="offcanvas" data-bs-target="#detailCanvas" aria-controls="detailCanvas">
                            <div class="imageWrapper">
                                <img :src="santri.photo ? santri.photo : `<?= $themeURL ?>assets/img/walisantri/avatar/${santri.jenis_kelamin}.svg`" alt="image" class="imaged w64">
                            </div>
                            <div class="in">
                                <div>
                                    <span x-text="santri.nama_santri"></span>
                                    <div class="text-secondary" x-text="`Kelas ` + santri.class_name"></div>
                                    <small class="text-end" x-html="getTodayPresensi(santriIndex)"></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    </template>
                    
                    <li>
                        <a href="javascript:void()" class="item bg-light" data-bs-toggle="modal" data-bs-target="#addSantriModal">
                            <div class="imageWrapper">
                                <img src="<?= $themeURL ?>assets/img/icon/icon-plus.png" alt="image" class="imaged w48 bg-secondary-subtle opacity-50">
                            </div>
                            <div class="in">
                                <div class="label fs-6 text-primary">
                                    Tambah Santri yang Diwalikan
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Offcanvas detail -->
        <?= $this->include('Pages/member/santri/_detailSantri') ?>
        <!-- End OffCanvas -->

        <!-- Dialog Form -->
        <?= $this->include('Pages/member/santri/_formAdd') ?>
        <!-- * Dialog Form -->
        
    </div>
    <!-- * App Capsule -->
</div>