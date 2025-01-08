<div id="tagihan">
    <style>
    .nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link { background-color: #eee; }
    .form-check-input{ width: 1.2em; height: 1.2em; border-color: #666;} a i{ color: white;} .nav-link.active{ background-color: #fff !important; border-color: #ffffff !important; color: #2495c6 !important;}
    </style>
    
    <div id="app-header" class="appHeader bg-brand">
        <div class="left"></div>
        <div class="pageTitle text-white"><span>Tagihan</span></div>
        <div class="right"></div>
    </div>

    <div id="appCapsule" class="shadow" style="background: url(<?= base_url('mobilekit/assets/img/bg-header-min.png'); ?>) no-repeat top left; background-size: contain">
        <div class="appContent" style="min-height:90vh">
            
            <section id="billContainer" class="section-top full pb-1" style="margin-bottom:60px;">
                <div class="p-2 text-center rounded-bottom-4 bg-success-2 position-relative" style="height:100px"></div>
                <div class="mx-3" id="tabs-custom">
                    <div class="d-flex justify-content-center">
                        <div class="bg-white shadow-sm me-2 text-dark py-3 px-3 rounded-4" style="margin-top:-75px">
                            <h6 class="text-muted mb-1">Tagihan Menunggu</h6>
                            <h4 x-show="data.loading" style="display: none;"><span class="placeholder col-4"></span></h4>
                            <h4 class="text-primary mb-0 text-center">Rp <span>220,000</span></h4>
                        </div>
                        <div class="bg-white shadow-sm text-dark py-3 px-3 rounded-4" style="margin-top:-75px">
                            <h6 class="text-muted mb-1">Item Menunggu</h6>
                            <h4 x-show="data.loading" style="display: none;"><span class="placeholder col-4"></span></h4>
                            <h4 class="text-primary mb-0 text-center"><span>12</span></h4>
                        </div>
                    </div>

                    <div class="rounded-bottom-4 text-success mt-2">
                        <ul class="nav nav-pills nav-fill nav-justified shadow-sm" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button 
                                 class="nav-link rounded-0 border text-muted py-2 active" 
                                 id="pills-belum-tab" 
                                 data-bs-toggle="pill" 
                                 data-bs-target="#pills-belum" 
                                 type="a" 
                                 role="tab" 
                                 aria-controls="pills-belum" 
                                 aria-selected="false">
                                    Menunggu
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button 
                                 class="nav-link rounded-0 border text-muted py-2" i
                                 d="pills-semua-tab" 
                                 data-bs-toggle="pill" 
                                 data-bs-target="#pills-semua" 
                                 type="a" 
                                 role="tab" 
                                 aria-controls="pills-semua" 
                                 aria-selected="true">
                                    Lunas
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Belum Lunas -->
                        <div class="tab-pane fade show active" id="pills-belum" role="tabpanel" aria-labelledby="pills-belum-tab">
                            <div class="border-top">
                                <div class="text-center pb-3" style="display: none;"> Tidak ada tagihan menunggu</div>

                                <ul class="listview">
                                    <li class="" :class="bill.start_date>(new Date().toJSON().slice(0, 10)) ? 'opacity-50' : ''">
                                        <div class="d-flex align-items-top">
                                            <div class="me-2"><input x-bind:id="`check-${ bill.id }`" class="form-check-input" type="checkbox" x-on:click="selectBill(bill, $event.target.checked)" id="check-8"></div>
                                            <div class="">
                                                <h6 class="m-0 text-muted" x-bind:class="bill.status == 'paid' ? 'text-white' : 'text-muted'">SPP Maret 2024 <br>Toni Haryanto</h6>
                                                <h5 class="my-1 text-primary">Rp <span>20,000</span></h5>
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
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Sudah Lunas -->
                        <div class="tab-pane fade" id="pills-semua" role="tabpanel" aria-labelledby="pills-semua-tab">
                        <div class="border-top">
                                <div class="text-center pb-3" style="display: none;"> Belum ada tagihan lunas</div>

                                <ul class="listview">
                                    <li class="" :class="bill.start_date>(new Date().toJSON().slice(0, 10)) ? 'opacity-50' : ''">
                                        <div class="d-flex align-items-top">
                                            <div class="">
                                                <h6 class="m-0 text-muted" x-bind:class="bill.status == 'paid' ? 'text-white' : 'text-muted'">SPP Maret 2024 <br>Toni Haryanto</h6>
                                                <h6 class="fs-6 my-1 text-secondary">Rp <span>20,000</span></h6>
                                            </div>
                                            <div class="ms-auto text-end">
                                                <div class="badge bg-white fw-bold text-success">LUNAS</div>
                                                <div class="btn-group dropstart">
                                                    <div class="btn-group dropstart"><button type="button" class="badge bg-dark-subtle border-0" x-on:click="setDetail(bill.id)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom"><i class="bi bi-three-dots h5 m-0"></i></button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottomMenuContainer" style="bottom:56px;background-color:transparent;">
                    <div class="appBottomMenu bg-secondary shadow d-flex justify-content-between align-items-center px-3" style="bottom:56px">
                        <div class="d-flex align-items-center gap-2 text-white"><span>Total tagihan :</span>
                            <p class="m-0 fs-6 fw-bold">Rp 0</p>
                        </div><button class="btn btn-warning" :disabled="data.totalBill == 0 ? true : false || data.process" x-on:click="payBill" disabled="disabled">
                            <div x-show="data.process" class="spinner-border spinner-border-sm" role="status"></div><span>Bayar</span>
                        </button>
                    </div>
                </div>
            </section>
            <div class="offcanvas offcanvas-bottom h-75 rounded-top-4" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Detail Tagihan</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
    <div class="bottomMenuContainer glassmorph shadow-lg">
        <div class="appBottomMenu d-flex"><a href="https://masagiapp.com/dashboard" class="item iuran ">
                <div><i class="bi bi-house"></i><strong>Beranda</strong></div>
            </a><a href="https://masagiapp.com/anggota" class="item iuran ">
                <div><i class="bi bi-person-vcard"></i><strong>Anggota</strong></div>
            </a><a href="https://masagiapp.com/iuran" class="item iuran active">
                <div><i class="bi bi-cash-coin"></i><strong>Iuran</strong></div>
            </a><a href="https://masagiapp.com/profile" class="item iuran ">
                <div><i class="bi bi-person-circle"></i><strong>Akun</strong></div>
            </a></div>
    </div>
</div>