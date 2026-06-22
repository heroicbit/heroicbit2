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
                    <div class="card-body text-center">
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
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Info Topup -->
                <div class="card card-info shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold"><ion-icon name="information-circle-outline" class="me-1"></ion-icon> Cara Top Up Saldo</h5>
                        <ol class="mb-0 ps-3">
                            <li>Nominal top-up minimal Rp 10.000.</li>
                            <li>Setiap transaksi top-up akan dikenakan biaya admin Rp 2.500.</li>
                            <li>Transfer ke nomor rekening <em>virtual account</em> BSI di bawah ini untuk top-up saldo uang saku <strong x-text="santri.nama_santri"></strong>.</li>
                        </ol>
                        <hr class="my-2">
                        <p class="small mb-0">
                            <div class="mb-1 text-center">No. Rekening Virtual Account:</div>
                            <div class="text-center fs-4">
                                <strong class="text-secondary">(451)</strong>
                                <strong class="text-primary">900</strong><strong class="text-secondary">7303</strong><strong x-text="saldoData?.siswa?.nis"></strong>
                            </div>
                        </p>
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