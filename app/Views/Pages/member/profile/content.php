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
                                <a href="https://masagi.test/profile/edit" class="item">
                                    <i class="bi bi-person fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Perbaharui Profil</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/profile/edit" class="item">
                                    <i class="bi bi-receipt fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Transaksi Saya</span>
                                </a>
                            </li>
                        </ul>

                        <div class="listview-title mt-1">Administrasi</div>
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="https://masagi.test/anggota/kode_aktivasi" class="item">
                                    <i class="bi bi-people-fill fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Data Santri</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/iuran" class="item">
                                    <i class="bi bi-calendar2-check-fill fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Cek Presensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/iuran" class="item">
                                    <i class="bi bi-gear-fill fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Pengaturan Aplikasi</span>
                                </a>
                            </li>
                        </ul>

                        <div class="listview-title mt-1">Aplikasi Tarbiyya</div>
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="https://masagi.test/pageinfo/about" class="item">
                                    <i class="bi bi-info-circle text-primary fs-4"></i>
                                    <span class="ms-2">Tentang Aplikasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/pageinfo/contact" class="item">
                                    <i class="bi bi-telephone text-primary fs-4"></i>
                                    <span class="ms-2">Kontak Kami</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/pageinfo/tnc" class="item">
                                    <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    <span class="ms-2">Syarat dan Ketentuan</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/pageinfo/pp" class="item">
                                    <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    <span class="ms-2">Kebijakan Privasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="https://masagi.test/pageinfo/faq" class="item">
                                    <i class="bi bi-patch-question text-primary fs-4"></i>
                                    <span class="ms-2">Pertanyaan Umum</span>
                                </a>
                            </li>
                            <li>
                                <a onclick="logout()" class="item">
                                    <i class="bi bi-lock text-danger fs-4"></i>
                                    <span class="ms-2 text-danger">Keluar</span>
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