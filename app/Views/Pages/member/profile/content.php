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
                    <div class="card shadow-none bg-light glassmorph text-dark container-fluid rounded-5 py-3 rounded-4 pt-4 pb-5" style="margin-top:-95px">
                        <div class="d-flex align-items-center justify-content-start gap-3">
                            <div><img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" class="rounded-circle" alt="Toni Haryanto" style="width:56px"></div>
                            <div class="use text-whiter">
                                <div class="h5 m-0">Toni Haryanto</div>
                                <div>17.5383 | Super</div>
                            </div>
                        </div>
                        <div class="text-center pt-5">
                            <div class="">
                                <a data-no-swup="" href="https://masagi.test/profile/edit" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-person-fill fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Perbaharui Profil</span>
                                </a>
                                <a href="https://masagi.test/iuran" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-receipt fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Iuran Anggota</span>
                                </a>
                                <a href="https://masagi.test/anggota/kode_aktivasi" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-code fs-2 text-primary me-2"></i>
                                    <span class="ms-1">Kode Aktivasi Anggota PC</span>
                                </a>
                            </div>
                            <hr style="border-color:#aaa;">
                        </div>
                        <div class="text-center">
                            <div>
                                <a href="https://masagi.test/pageinfo/about" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-info-circle text-primary fs-4"></i>
                                    <span class="ms-2">Tentang Masagi</span>
                                </a>
                                <a href="https://masagi.test/pageinfo/contact" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-telephone text-primary fs-4"></i>
                                    <span class="ms-2">Kontak Kami</span>
                                </a>
                                <a href="https://masagi.test/pageinfo/tnc" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    <span class="ms-2">Syarat dan Ketentuan</span>
                                </a>
                                <a href="https://masagi.test/pageinfo/pp" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                    <span class="ms-2">Kebijakan Privasi</span>
                                </a>
                                <a href="https://masagi.test/pageinfo/faq" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-patch-question text-primary fs-4"></i>
                                    <span class="ms-2">Pertanyaan Umum</span>
                                </a>
                                <a onclick="logout()" class="link bg-white text-dark shadow-sm rounded-4 d-flex align-items-center py-2 px-3 mb-2">
                                    <i class="bi bi-lock text-danger fs-4"></i>
                                    <span class="ms-2 text-danger">Keluar</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- * App Capsule -->
</div>