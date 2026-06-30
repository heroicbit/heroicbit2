<div id="member_uangsaku" x-data="member_uangsaku()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Smartcard</div>
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
                    <a href="javascript:void()" class="text-decoration-none d-block mb-3" x-on:click="bukaDetail(index)">
                        <div class="atm-card" :style="{ background: cardGradients[index % cardGradients.length] }">
                            <!-- Top row: chip + label -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-white fw-semibold small" style="opacity:.8; letter-spacing:.04em">Smartcard</span>
                            </div>
                            <!-- Saldo -->
                            <div>
                                <div class="text-white small mb-0" style="opacity:.7">Saldo</div>
                                <template x-if="saldoList[santri.nis]">
                                    <div class="text-white fw-bold fs-4 mb-2" x-text="formatRupiah(saldoList[santri.nis].saldo_uangsaku)"></div>
                                </template>
                                <template x-if="saldoList[santri.nis] === undefined && !loadingSaldoList">
                                    <div class="text-white small" style="opacity:.7">Memuat...</div>
                                </template>
                                <template x-if="saldoList[santri.nis] === null && !loadingSaldoList">
                                    <div class="text-white small" style="opacity:.7">Tidak tersedia</div>
                                </template>
                            </div>
                            <!-- Bottom row: name + class + NIS -->
                            <div class="d-flex justify-content-between align-items-end">
                                <div style="min-width:0">
                                    <div class="text-white fw-semibold small text-uppercase text-truncate" style="letter-spacing:.05em" x-text="santri.nama_santri"></div>
                                    <div class="text-white small" style="opacity:.7" x-text="'Kelas ' + santri.class_name"></div>
                                </div>
                                <div class="text-end flex-shrink-0 ms-2">
                                    <div class="text-white" style="opacity:.6; font-size:.65rem; letter-spacing:.06em">NIS</div>
                                    <div class="text-white small fw-semibold" x-text="santri.nis"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
</div>