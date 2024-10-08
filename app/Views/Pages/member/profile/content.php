<div id="template-container" x-data="member_profile()">
<div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Profile</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
        <div id="appCapsule" class="shadow">
        <div class="appContent" style="min-height:90vh">
            <section class="section-top full mb-5 pb-5">
                <div class="">
                    <div class="p-2 text-center position-relative bg-brand" style="height:100px;"></div>
                    <div class="card shadow-none bg-light bg-opacity-50 glassmorph text-dark container-fluid rounded-5 py-3 rounded-4 pt-4 pb-5" style="margin-top:-95px">
                        <div class="d-flex align-items-center justify-content-start gap-3">
                            <div><img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" class="rounded-circle" alt="Toni Haryanto" style="width:56px"></div>
                            <div class="use text-whiter">
                                <div class="h5 m-0">Toni Haryanto</div>
                                <div>17.5383 | Super</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="/edit-profil" class="item">
                                    <i class="bi bi-person fs-2 text-primary me-2"></i>
                                    <span>Perbaharui Profil</span>
                                </a>
                            </li>
                            <li>
                                <a href="/invoice" class="item">
                                    <i class="bi bi-receipt fs-2 text-primary me-2"></i>
                                    <span>Transaksi Saya</span>
                                </a>
                            </li>
                        </ul>

                        <div class="listview-title mt-2">Administrasi</div>
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="/allsantri" class="item">
                                    <i class="bi bi-people-fill fs-2 text-primary me-2"></i>
                                    <span>Data Santri</span>
                                </a>
                            </li>
                            <li>
                                <a href="/presensi" class="item">
                                    <i class="bi bi-calendar2-check-fill fs-2 text-primary me-2"></i>
                                    <span>Cek Presensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/settings" class="item">
                                    <i class="bi bi-gear-fill fs-2 text-primary me-2"></i>
                                    <span>Pengaturan Aplikasi</span>
                                </a>
                            </li>
                        </ul>

                        <div class="listview-title mt-2">Aplikasi Tarbiyya</div>
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="/page/about-app" class="item">
                                    <i class="bi bi-info-circle text-primary fs-4 me-2"></i>
                                    <span>Tentang Aplikasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/page/contact-us" class="item">
                                    <i class="bi bi-telephone text-primary fs-4 me-2"></i>
                                    <span>Kontak Kami</span>
                                </a>
                            </li>
                            <li>
                                <a href="/page/tnc" class="item">
                                    <i class="bi bi-file-earmark-text text-primary fs-4 me-2"></i>
                                    <span>Syarat dan Ketentuan</span>
                                </a>
                            </li>
                            <li>
                                <a href="/page/privacy" class="item">
                                    <i class="bi bi-file-earmark-text text-primary fs-4 me-2"></i>
                                    <span>Kebijakan Privasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/page/faq" class="item">
                                    <i class="bi bi-patch-question text-primary fs-4 me-2"></i>
                                    <span>Pertanyaan Umum</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void()" x-on:click="logout" class="item">
                                    <i class="bi bi-lock text-danger fs-4 me-2"></i>
                                    <span class="text-danger">Keluar</span>
                                </a>
                            </li>
                        </ul>

                    </div>

                </div>
            </section>
        </div>
    </div>
    <!-- * App Capsule -->
</div>