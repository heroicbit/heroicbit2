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
        <div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="detailCanvasLabel" x-text="detailSantri.nama_santri"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav nav-tabs capsuled" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#profil" role="tab" aria-selected="true">
                            Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#presensi" role="tab" aria-selected="false">
                            Presensi
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <div class="tab-pane fade active show" id="profil" role="tabpanel">
                    <dl><dt>NIS</dt><dd x-text="detailSantri.nis"></dd></dl>
                    <dl><dt>NIK</dt><dd x-text="detailSantri.nik_santri"></dd></dl>
                    <dl><dt>NISN</dt><dd x-text="detailSantri.nisn"></dd></dl>
                    <dl><dt>Tempat/Tanggal Lahir</dt><dd x-text="detailSantri.tempat_lahir_santri + `, ` + formatDate(detailSantri.tanggal_lahir_santri)"></dd></dl>
                    <dl><dt>Jenis Kelamin</dt><dd x-text="detailSantri.jenis_kelamin == `m` ? `Laki-laki` : `Perempuan`"></dd></dl>
                    <dl><dt>Status dalam Keluarga</dt><dd x-text="detailSantri.status_keluarga"></dd></dl>
                    <dl><dt>Anak Ke-</dt><dd x-text="detailSantri.anak_ke"></dd></dl>
                    <dl><dt>Saudara Kandung</dt><dd x-text="detailSantri.jumlah_saudara_kandung > 0 ? detailSantri.jumlah_saudara_kandung + `orang` : `-`"></dd></dl>
                    <dl><dt>Saudara Tiri</dt><dd x-text="detailSantri.jumlah_saudara_tiri > 0 ? detailSantri.jumlah_saudara_tiri + `orang` : `-`"></dd></dl>
                    <dl><dt>Saudara Angkat</dt><dd x-text="detailSantri.jumlah_saudara_angkat > 0 ? detailSantri.jumlah_saudara_angkat + `orang` : `-`"></dd></dl>
                    <dl><dt>hobi</dt><dd x-text="detailSantri.hobi ?? `-`"></dd></dl>
                    <dl><dt>cita</dt><dd x-text="detailSantri.cita ?? `-`"></dd></dl>
                    <hr>
                    <dl><dt>Asal Sekolah</dt><dd x-text="detailSantri.asal_sekolah"></dd></dl>
                    <dl><dt>NPSN</dt><dd x-text="detailSantri.npsn_sekolah"></dd></dl>
                    <dl><dt>Alamat</dt><dd x-text="detailSantri.alamat_sekolah"></dd></dl>
                    <hr>
                    <dl><dt>Nam Ayah</dt><dd x-text="detailSantri.nama_ayah"></dd></dl>
                    <dl><dt>NIK</dt><dd x-text="detailSantri.nik_ayah"></dd></dl>
                    <dl><dt>Tempat/Tanggal Lahir</dt><dd x-text="detailSantri.tempat_lahir_ayah + `, ` + formatDate(detailSantri.tanggal_lahir_ayah)"></dd></dl>
                    <dl><dt>No. Kontak</dt><dd x-text="detailSantri.kontak_ayah"></dd></dl>
                    <dl><dt>Pekerjaan</dt><dd x-text="detailSantri.pekerjaan_ayah"></dd></dl>
                    <dl><dt>Pendidikan Formal Terakhir</dt><dd class="text-uppercase" x-text="detailSantri.pendidikan_ayah"></dd></dl>
                    <hr>
                    <dl><dt>Nama Ibu</dt><dd x-text="detailSantri.nama_ibu"></dd></dl>
                    <dl><dt>NIK</dt><dd x-text="detailSantri.nik_ibu"></dd></dl>
                    <dl><dt>Tempat/Tanggal Lahir</dt><dd x-text="detailSantri.tempat_lahir_ibu + `, ` + formatDate(detailSantri.tanggal_lahir_ibu)"></dd></dl>
                    <dl><dt>No.Kontak</dt><dd x-text="detailSantri.kontak_ibu"></dd></dl>
                    <dl><dt>Pekerjaan</dt><dd x-text="detailSantri.pekerjaan_ibu"></dd></dl>
                    <dl><dt>Pendidikan Formal Terakhir</dt><dd class="text-uppercase" x-text="detailSantri.pendidikan_ibu"></dd></dl>
                    <hr>
                    <dl><dt>Penghasilan Ayah-Ibu</dt><dd x-text="detailSantri.penghasilan"></dd></dl>
                    <dl><dt>Alamat</dt><dd x-text="detailSantri.alamat"></dd></dl>
                    <dl><dt>RT/RW</dt><dd x-text="detailSantri.rtrw"></dd></dl>
                    <dl><dt>Desa/Kelurahan</dt><dd x-text="detailSantri.desa"></dd></dl>
                    <dl><dt>Kecamatan</dt><dd x-text="detailSantri.kecamatan"></dd></dl>
                    <dl><dt>Kota/Kabupaten</dt><dd x-text="detailSantri.kota"></dd></dl>
                    <dl><dt>Provinsi</dt><dd x-text="detailSantri.provinsi"></dd></dl>
                    <dl><dt>Kodepos</dt><dd x-text="detailSantri.kodepos"></dd></dl>
                    <hr>
                    <dl><dt>tahun_masuk</dt><dd x-text="detailSantri.tahun_masuk"></dd></dl>
                    <dl><dt>iuran_bulanan</dt><dd x-text="detailSantri.iuran_bulanan"></dd></dl>
                    </div>
                    <div class="tab-pane fade" id="presensi" role="tabpanel">
                        Maecenas malesuada, massa non vehicula dictum, lorem ipsum semper lectus, id egestas
                        velit leo vel nunc. Vestibulum dapibus diam viverra, pulvinar erat ut, maximus ipsum.
                        Curabitur gravida, arcu nec venenatis auctor, urna nisi gravida mauris, quis semper eros
                        lorem id dui. Duis euismod est non mi volutpat tristique. Integer et nibh nisl. Morbi
                        condimentum nisl enim, sit amet bibendum tortor aliquam vel.
                    </div>
                </div>
            </div>
        </div>
        <!-- End OffCanvas -->

        <!-- Dialog Form -->
        <div class="modal fade dialogbox" id="addSantriModal" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Santri</h5>
                    </div>
                    <form>
                        <div class="modal-body text-start mb-2">
                            <p>Masukkan NIS atau NISN santri pada kolom di bawah ini</p>
                            <div class="form-group boxed">
                                <div class="input-wrapper">
                                    <label class="form-label" for="email1">NIS/NISN</label>
                                    <input type="text" class="form-control" x-model="searchNIS">
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>

                            <p class="alert alert-warning" x-show="NISFound.found == 0">Data dengan NIS/NISN yang dicari tidak ditemukan.</p>
                            <div class="listview image-listview media" x-show="NISFound.nama_santri">
                                <li>
                                    <div class="item px-0">
                                        <div class="imageWrapper">
                                            <img src="https://tarbiyya.test/mobilekit/assets/img/walisantri/avatar/m.svg" alt="image" class="imaged w64">
                                        </div>
                                        <div class="in">
                                            <div>
                                                <span x-text="NISFound.nama_santri"></span>
                                                <div class="text-muted" x-text="NISFound.class_name"></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-text-primary" x-show="NISFound.found != 1" x-on:click="checkNIS">Cek Data</button>
                                <button type="button" class="btn btn-success" x-show="NISFound.nama_santri" x-on:click="addSantri">Tambahkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- * Dialog Form -->
        
    </div>
    <!-- * App Capsule -->
</div>