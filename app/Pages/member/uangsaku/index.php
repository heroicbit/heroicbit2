<div id="member_uangsaku" x-data="member_uangsaku()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Uang Saku</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section p-0">
            <div class="px-3 pt-3">
                <template x-if="loadingSaldoList">
                    <div class="text-center py-3">
                        <div class="spinner-border text-success" role="status"></div>
                        <p class="mt-2 text-muted small">Memuat saldo...</p>
                    </div>
                </template>
                <template x-for="(santri, index) in data.santri">
                    <div class="card shadow-sm mb-3 overflow-hidden">
                        <a href="javascript:void()" class="text-decoration-none" x-on:click="bukaDetail(index)">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 text-body" x-text="santri.nama_santri"></h5>
                                        <small class="text-muted" x-text="`Kelas ` + santri.class_name"></small>
                                    </div>
                                    <div class="flex-shrink-0 text-end">
                                        <template x-if="saldoList[santri.nis]">
                                            <div>
                                                <small class="text-muted d-block">Saldo</small>
                                                <strong class="text-success fs-6" x-text="formatRupiah(saldoList[santri.nis].saldo_uangsaku)"></strong>
                                            </div>
                                        </template>
                                        <template x-if="saldoList[santri.nis] === undefined && !loadingSaldoList">
                                            <div class="text-muted small">Memuat...</div>
                                        </template>
                                        <template x-if="saldoList[santri.nis] === null && !loadingSaldoList">
                                            <div class="text-muted small">Gagal</div>
                                        </template>
                                        <ion-icon name="chevron-forward-outline" class="text-muted ms-2"></ion-icon>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
</div>