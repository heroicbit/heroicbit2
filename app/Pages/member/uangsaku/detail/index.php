<div id="member_uangsaku_detail" x-data="member_uangsaku_detail($router.params.nis)">
    <div class="appHeader bg-brand">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon class="text-white fs-3" name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">
        <template x-if="santri">
        <div>
            <div class="bg-brand rounded-bottom-4 pb-3">
                <div class="d-flex align-items-center px-3">
                    <div>
                        <h5 class="text-white mb-0" x-text="santri.nama_santri"></h5>
                        <small class="fs-6 text-white-50" x-text="`NIS: ` + santri.nis + ` - Kelas ` + santri.class_name"></small>
                    </div>
                </div>
            </div>

            <div class="px-3" style="margin-top: -20px;">
                <!-- Kartu Saldo -->
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center pb-1">
                        <template x-if="loadingSaldo">
                            <div class="text-center py-3">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="mt-2 text-muted small">Memuat saldo...</p>
                            </div>
                        </template>
                        <template x-if="!loadingSaldo && errorSaldo">
                            <div class="text-center py-3">
                                <p class="text-danger" x-text="errorSaldo"></p>
                                <button class="btn btn-sm btn-success" x-on:click="loadSaldo">Muat Ulang</button>
                            </div>
                        </template>
                        <template x-if="!loadingSaldo && !errorSaldo && saldoData">
                            <div>
                                <div class="mb-2">
                                    <div class="text-muted small mb-1">Saldo Uang Saku</div>
                                    <div class="fs-1 fw-bold text-success" x-text="formatRupiah(saldoData.saldo_uangsaku)"></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Total Masuk</small>
                                        <strong class="text-primary" x-text="formatRupiah(saldoData.total_pemasukan)"></strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Total Keluar</small>
                                        <strong class="text-danger" x-text="formatRupiah(saldoData.total_pengeluaran)"></strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Hari Ini</small>
                                        <strong class="text-warning" x-text="formatRupiah(saldoData.pengeluaran_hari_ini)"></strong>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <button class="btn btn-link btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#topupModal">
                                        <i class="bi bi-info-circle fs-6"></i>
                                        Lihat Cara Top Up Saldo 
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold"><ion-icon name="receipt-outline" class="me-1"></ion-icon>Riwayat Transaksi</h5>
                        <template x-if="loadingHistory">
                            <div class="text-center py-3">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="mt-2 text-muted small">Memuat riwayat...</p>
                            </div>
                        </template>
                        <template x-if="!loadingHistory && errorHistory">
                            <div class="text-center py-3">
                                <p class="text-danger" x-text="errorHistory"></p>
                                <button class="btn btn-sm btn-success" x-on:click="loadHistory">Muat Ulang</button>
                            </div>
                        </template>
                        <template x-if="!loadingHistory && !errorHistory && historyData.length === 0">
                            <p class="text-muted small text-center py-3">Belum ada riwayat transaksi.</p>
                        </template>
                        <template x-if="!loadingHistory && historyData.length > 0">
                            <div>
                                <template x-for="(date, dateIndex) in getUniqueDates()" :key="dateIndex">
                                    <div>
                                        <div class="text-light px-2 bg-info small fw-bold mt-2 mb-1" x-text="formatDateGroup(date)"></div>
                                        <template x-for="(trx, trxIndex) in getTransactionsByDate(date)" :key="trxIndex">
                                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                <div>
                                                    <div class="fw-bold small" x-text="trx.keterangan"></div>
                                                    <small class="text-muted" x-text="`Pukul ${formatTime(trx.tgl)}`"></small>
                                                    <br>
                                                    <small class="text-muted" x-show="trx.uraian" x-text="trx.uraian"></small>
                                                </div>
                                                <div class="text-end">
                                                    <template x-if="trx.jenis == 'MASUK'">
                                                        <div>
                                                            <span class="text-success fw-bold" x-text="'+' + formatRupiah(trx.debet || 0)"></span>
                                                            <br><small class="text-success">Top Up</small>
                                                        </div>
                                                    </template>
                                                    <template x-if="trx.jenis == 'KELUAR'">
                                                        <div>
                                                            <span class="text-danger fw-bold" x-text="'-' + formatRupiah(trx.kredit || 0)"></span>
                                                            <br><small class="text-danger">Pembelian</small>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Modal Cara Top Up -->
                <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="topupModalLabel">
                                    <ion-icon name="information-circle-outline" class="me-1"></ion-icon> Cara Top Up Saldo
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <div class="accordion" id="topupAccordion">

                                    <!-- BYOND by BSI -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="topupHeading1">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#topupCollapse1" aria-expanded="false" aria-controls="topupCollapse1">
                                                BYOND by BSI
                                            </button>
                                        </h2>
                                        <div id="topupCollapse1" class="accordion-collapse collapse" aria-labelledby="topupHeading1" data-bs-parent="#topupAccordion">
                                            <div class="accordion-body small">
                                                <ol class="ps-3 mb-0">
                                                    <li>Buka Aplikasi <strong>BYOND by BSI</strong></li>
                                                    <li>Pilih Menu <strong>BAYAR &amp; BELI</strong></li>
                                                    <li>Klik <em>Show More</em>, pilih Menu <strong>AKADEMIK</strong></li>
                                                    <li>Ketik <strong>7303</strong>, lalu pilih <strong>Pesantren Persis Benda Uang Jajan</strong></li>
                                                    <li>Pada Menu <em>Customer ID/Payment Code</em>, masukkan <strong>NIS <span x-text="santri.nis"></span></strong></li>
                                                    <li>Silahkan Masukan <strong>Nominal Top-Up</strong></li>
                                                    <li>Akan muncul Informasi Nama Penerima. <strong>PASTIKAN</strong> nama santri yang muncul adalah <strong>Nama Putra/Putri Bapak/Ibu</strong>.</li>
                                                    <li>Selesaikan transaksi sampai berhasil.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mesin ATM BSI -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="topupHeading2">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#topupCollapse2" aria-expanded="false" aria-controls="topupCollapse2">
                                                Mesin ATM BSI
                                            </button>
                                        </h2>
                                        <div id="topupCollapse2" class="accordion-collapse collapse" aria-labelledby="topupHeading2" data-bs-parent="#topupAccordion">
                                            <div class="accordion-body small">
                                                <ol class="ps-3 mb-0">
                                                    <li>Masukkan <strong>Kartu ATM BSI</strong></li>
                                                    <li>Pilih <strong>BAHASA INDONESIA</strong></li>
                                                    <li>Masukkan <strong>PIN</strong></li>
                                                    <li>Pilih <strong>MENU UTAMA</strong></li>
                                                    <li>Pilih <strong>TRANSFER</strong></li>
                                                    <li>Pilih <strong>REKENING BSI LAINNYA</strong></li>
                                                    <li>Cetak Struk, pilih <strong>YA</strong></li>
                                                    <li>Ketik Nomor Rekening <strong>900 7303 <span x-text="santri.nis"></span></strong></li>
                                                    <li>Akan Muncul Informasi Penerima. <strong>PASTIKAN</strong> Nama Santri yang muncul adalah <strong>Nama Putra/Putri Bapak/Ibu</strong>. Kemudian Klik <strong>LANJUTKAN</strong></li>
                                                    <li>Silahkan Masukkan Nominal TOP-UP. Kemudian Klik <strong>YA</strong>. Akan muncul Informasi detail. Klik <strong>YA</strong>.</li>
                                                    <li>Selesaikan transaksi sampai berhasil.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mesin ATM Bank Lain -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="topupHeading3">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#topupCollapse3" aria-expanded="false" aria-controls="topupCollapse3">
                                                Mesin ATM Bank Lain
                                            </button>
                                        </h2>
                                        <div id="topupCollapse3" class="accordion-collapse collapse" aria-labelledby="topupHeading3" data-bs-parent="#topupAccordion">
                                            <div class="accordion-body small">
                                                <ol class="ps-3 mb-0">
                                                    <li>Lakukan Langkah <strong>sesuai Mesin ATM masing-masing</strong>.</li>
                                                    <li>Pilih menu <strong>TRANSFER</strong></li>
                                                    <li>Nomor TOP-UP = <strong>451 900 7303 <span x-text="santri.nis"></span></strong></li>
                                                    <li>Akan Muncul Informasi Penerima. <strong>PASTIKAN</strong> Nama Santri yang muncul adalah Nama Putra/Putri Bapak/Ibu.</li>
                                                    <li>Silahkan Masukkan <strong>Nominal TOP-UP</strong>.</li>
                                                    <li>Selesaikan transaksi sampai berhasil.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile Banking Bank Lain -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="topupHeading4">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#topupCollapse4" aria-expanded="false" aria-controls="topupCollapse4">
                                                Mobile Banking Bank Lain
                                            </button>
                                        </h2>
                                        <div id="topupCollapse4" class="accordion-collapse collapse" aria-labelledby="topupHeading4" data-bs-parent="#topupAccordion">
                                            <div class="accordion-body small">
                                                <ol class="ps-3 mb-0">
                                                    <li>Buka Aplikasi <strong>MOBILE BANKING</strong>nya</li>
                                                    <li>Lakukan Langkah-Langkah sesuai aplikasi masing-masing.</li>
                                                    <li>Pilih Menu <strong>TRANSFER</strong></li>
                                                    <li>Cari dan Pilih <strong>BANK BSI / BANK SYARIAH INDONESIA</strong></li>
                                                    <li>Masukan Nomor TOP-UP <strong>900 7303 <span x-text="santri.nis"></span></strong></li>
                                                    <li><strong>PASTIKAN</strong> Nama Santri Yang Muncul adalah Nama Putra/Putri Bapak/Ibu.</li>
                                                    <li>Masukan Nominal TOP-UP.</li>
                                                    <li>Pastikan memilih METODE <strong>TRANSFER ONLINE</strong> atau <strong>REALTIME ONLINE</strong>.</li>
                                                    <li>Selesaikan transaksi sampai berhasil.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </template>

        <template x-if="loaded && !santri">
            <div class="text-center py-3 px-3">
                <p>Data uang saku santri dengan NIS yang dimaksud tidak ditemukan.</p>
                <button class="btn btn-sm btn-outline-info" onclick="history.back()"><i class="bi bi-arrow-left"></i> Kembali</button>
            </div>
        </template>
    </div>
    <!-- * App Capsule -->
</div>