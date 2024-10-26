<div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="detailCanvasLabel" x-text="detailSantri.nama_santri"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="text-center mb-2">
            <img :src="detailSantri.photo ? detailSantri.photo : `<?= $themeURL ?>assets/img/walisantri/avatar/${detailSantri.jenis_kelamin}.svg`" alt="image" class="imaged w-50">
        </div>

        <div class="card shadow-lg p-1">
            <ul class="nav nav-tabs capsuled" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#profil" role="tab" aria-selected="true">
                        Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#presensi" role="tab" aria-selected="false" x-on:click="loadDetailPresensi">
                        Presensi
                    </a>
                </li>
            </ul>
            <div class="tab-content p-1">
                <div class="tab-pane mt-2 fade active show" id="profil" role="tabpanel">
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
                    <dl><dt>Tahun Masuk</dt><dd x-text="detailSantri.tahun_masuk"></dd></dl>
                    <dl><dt>Infaq Bulanan</dt><dd x-text="detailSantri.iuran_bulanan"></dd></dl>
                </div>
                <div class="tab-pane fade" id="presensi" role="tabpanel">
                    <div id="calendar" style="width:100%"></div>

                    <div x-transition x-show="Object.keys(selectedPresensi).length > 0" class="presensi-status-container p-1 rounded" :class="selectedPresensi.status">
                        <div class="section-title justify-content-start p-0" x-text="`Presensi ` + formatDate(selectedDate)"></div>
                        <p class="m-0"><strong x-html="selectedPresensi.caption"></strong></p>
                        <p class="m-0 text-muted small" x-show="selectedPresensi.description" x-text="selectedPresensi.description"></p>
                    </div>

                    <div class="section-title mt-2">Rekap presensi semester ini</div>
                    <div class="wide-block p-1">
                        <span class="text-info fs-4">&bull;</span> Total sakit: <span x-text="detailSantri.total_sakit"></span><br>
                        <span class="text-warning fs-4">&bull;</span> Total izin: <span x-text="detailSantri.total_izin"></span><br>
                        <span class="text-danger fs-4">&bull;</span> Total tanpa keterangan: <span x-text="detailSantri.total_alpa"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
