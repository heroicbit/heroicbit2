<div id="template-container" x-data="member_tagihan()">
<div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Tagihan</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow">
        <div class="appContent" style="min-height:90vh">
            <style>.form-check-input {
                width: 1.2em;
                height: 1.2em;
                border-color: #666;
                }
                a i {
                color: white;
                }
                .nav-link.active {
                background-color: #fff !important;
                border-color: #ffffff !important;
                color: #2495c6 !important;
                }
            </style>
            <section id="billContainer" class="section-top full pb-1" style="margin-bottom:60px;">
                <div class="p-2 text-center rounded-bottom-4 bg-brand position-relative" style="height:100px"></div>
                <div 
                 class="mx-4" 
                 id="tabs-custom">
                    <div class="card text-dark py-4 px-3 rounded-4" style="margin-top:-75px">
                        <p class="fs-5">Tagihan Anda:</p>
                        <h4 x-show="data.loading" style="display: none;"><span class="placeholder col-4"></span></h4>
                        <h4 class="text-primary mb-0" style="">Rp <span>160,000</span></h4>
                    </div>
                    <div class="rounded-bottom-4 text-success mt-3">
                        <ul class="nav nav-pills nav-fill mt-2 nav-justified shadow-sm" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation"><button class="nav-link rounded-0 border text-muted py-2 active" id="pills-belum-tab" data-bs-toggle="pill" data-bs-target="#pills-belum" type="a" role="tab" aria-controls="pills-belum" aria-selected="false"><small>Belum Lunas</small></button></li>
                            <li class="nav-item" role="presentation"><button class="nav-link rounded-0 border text-muted py-2" id="pills-semua-tab" data-bs-toggle="pill" data-bs-target="#pills-semua" type="a" role="tab" aria-controls="pills-semua" aria-selected="true"><small>Sudah Lunas</small></button></li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-belum" role="tabpanel" aria-labelledby="pills-belum-tab">
                            <div class="card shadow-sm rounded-0 rounded-bottom-4 px-lg-4 px-3 pt-3 pb-2 border-top">
                                <div x-show="data.loading" style="display: none;">
                                    <div class="card px-3 py-2 rounded-3">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <span class="placeholder col-8"></span>
                                                <div class="fw-bold"><span class="placeholder col-5"></span></div>
                                            </div>
                                            <div class="col-3"><span class="placeholder col-12"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="!data.loading && data.bills.filter(bill=>bill.status == 'pending').length == 0" class="text-center pb-3" style="display: none;"> Tidak ada tagihan menunggu</div>
                                <template x-show="!data.loading" x-for="bill in data.bills.filter(bill=>bill.status == 'pending')" style="">
                                    <div class="card px-3 py-2 mb-2 rounded-3" :class="bill.start_date>(new Date().toJSON().slice(0, 10)) ? 'opacity-50' : ''">
                                        <div class="d-flex align-items-top">
                                            <div class="me-2"><input x-bind:id="`check-${ bill.id }`" class="form-check-input" type="checkbox" x-on:click="selectBill(bill, $event.target.checked)"></div>
                                            <div class="">
                                                <h6 x-text="bill.title" class="m-0" x-bind:class="bill.status == 'paid' ? 'text-white' : 'text-muted'"></h6>
                                                <strong>Rp <span x-text="convertRupiah(bill.amount)"></span></strong>
                                            </div>
                                            <div class="">
                                                <div x-show="bill.status == 'paid'" class="badge bg-white text-success">LUNAS</div>
                                                <div x-show="bill.status == 'pending'"><i class="fa fa-shoping-cart"></i></div>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <div class="btn-group dropstart">
                                                    <div class="btn-group dropstart"><button type="button" class="badge bg-dark-subtle border-0" x-on:click="setDetail(bill.id)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"><i class="bi bi-three-dots h5 m-0"></i></button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="card px-3 py-2 mb-2 rounded-3" :class="bill.start_date>(new Date().toJSON().slice(0, 10)) ? 'opacity-50' : ''">
                                    <div class="d-flex align-items-top">
                                        <div class="me-2"><input x-bind:id="`check-${ bill.id }`" class="form-check-input" type="checkbox" x-on:click="selectBill(bill, $event.target.checked)" id="check-7"></div>
                                        <div class="">
                                            <strong>Rp <span>20,000</span></strong>
                                            <h6 class="m-0 text-muted" x-bind:class="bill.status == 'paid' ? 'text-white' : 'text-muted'">Infaq Februari 2024 <br>Wildan Zukhruf Ilmi</h6>
                                        </div>
                                        <div class="">
                                            <div x-show="bill.status == 'paid'" class="badge bg-white text-success" style="display: none;">LUNAS</div>
                                            <div x-show="bill.status == 'pending'"><i class="fa fa-shoping-cart"></i></div>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <div class="btn-group dropstart">
                                                <div class="btn-group dropstart"><button type="button" class="badge bg-dark-subtle border-0" x-on:click="setDetail(bill.id)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"><i class="bi bi-three-dots h5 m-0"></i></button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-semua" role="tabpanel" aria-labelledby="pills-semua-tab">
                            <div class="card px-lg-4 px-2 pt-3 pb-2 rounded-0 rounded-bottom-4 text-muted shadow-sm border-top">
                                <div x-show="data.loading" style="display: none;">
                                    <div class="card px-3 py-2 rounded-3">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <span class="placeholder col-8"></span>
                                                <div class="fw-bold"><span class="placeholder col-5"></span></div>
                                            </div>
                                            <div class="col-3"><span class="placeholder col-12"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="!data.loading && data.bills.filter(bill=>bill.status != 'pending').length == 0" class="text-center pb-3" style="display: none;"> Belum ada tagihan</div>
                                <template x-show="!data.loading" x-for="bill in data.bills.filter(bill=>bill.status == 'paid')" style="">
                                    <div class="card px-3 py-2 mb-2 rounded-3 bg-success-2">
                                        <div class="d-flex align-items-center">
                                            <div class="">
                                                <h6 x-text="bill.title" class="m-0 text-white"></h6>
                                                <strong>Rp <span x-text="convertRupiah(bill.amount)"></span></strong>
                                            </div>
                                            <div class="ms-auto">
                                                <div class="badge rounded-pill bg-white text-success">LUNAS</div>
                                            </div>
                                            <div class="ms-2 text-end">
                                                <div class="btn-group dropstart">
                                                    <div class="btn-group dropstart"><button type="button" class="badge bg-dark-subtle border-0" x-on:click="setDetail(bill.id)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"><i class="bi bi-three-dots h5 m-0"></i></button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="card px-3 py-2 mb-2 rounded-3 bg-secondary">
                                    <div class="d-flex align-items-center">
                                        <div class="">
                                            <strong>Rp <span>20,000</span></strong>
                                            <h6 class="m-0 text-white">Iuran Agustus 2023 <br>Wildan Zukhruf Ilmi</h6>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="badge rounded-pill bg-success">LUNAS</div>
                                        </div>
                                        <div class="ms-2 text-end">
                                            <div class="btn-group dropstart">
                                                <div class="btn-group dropstart"><button type="button" class="badge bg-dark-subtle border-0" x-on:click="setDetail(bill.id)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"><i class="bi bi-three-dots h5 m-0"></i></button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>.appBottomMenu {
                    max-width: 576px !important;
                    margin: 0 auto;
                    }
                    .btn-float strong {
                    display: none !important;
                    }
                    .btn-float i {
                    padding-top: 13px;
                    }
                    .btn-float {
                    border-radius: 50%;
                    background: #fff;
                    border: 2px solid #fffa;
                    padding: 13px 6px;
                    width: 60px !important;
                    height: 60px;
                    vertical-align: middle;
                    position: absolute;
                    bottom: 8px;
                    }
                </style>
                <div class="bottomMenuContainer" style="bottom:56px;background-color:transparent;">
                    <div class="appBottomMenu bg-secondary shadow d-flex justify-content-between align-items-center px-3" style="bottom:56px">
                        <div class="d-flex align-items-center gap-2">
                            <span>Total tagihan :</span>
                            <p class="m-0 fw-bold" x-text="'Rp ' + convertRupiah(data.totalBill)">Rp 0</p>
                        </div>
                        <button class="btn btn-warning" :disabled="data.totalBill == 0 ? true : false || data.process" x-on:click="payBill" disabled="disabled">
                            <div x-show="data.process" class="spinner-border spinner-border-sm" role="status" style="display: none;"></div>
                            <span>Bayar</span>
                        </button>
                    </div>
                </div>
            </section>
            <div class="offcanvas offcanvas-bottom h-75 rounded-top-4" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Detail Tagihan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body small py-4">
                    <h6 id="nis"></h6>
                    <div class="pb-4 mb-3 border-bottom">
                        <table>
                            <tbody>
                                <tr>
                                    <td width="180">Jenis Tagihan</td>
                                    <td width="10">:</td>
                                    <td id="bill_type"></td>
                                </tr>
                                <tr>
                                    <td>Atas Nama</td>
                                    <td>:</td>
                                    <td id="name"></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Dibuat Tagihan</td>
                                    <td>:</td>
                                    <td id="created_at"></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Jatuh Tempo</td>
                                    <td>:</td>
                                    <td id="due_date"></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td id="status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
</div>