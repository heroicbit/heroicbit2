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
                    <div class="card ps-3 shadow-none bg-light text-dark container-fluid rounded-top-5 pt-3 pb-3" style="margin-top:-95px">
                        <div class="d-flex align-items-center justify-content-start gap-3">
                            <div>
                                <img :src="data?.profile?.avatar ? data?.profile?.avatar : `<?= $themeURL ?>assets/img/icon/default-avatar-user.webp`" 
                                class="rounded-circle" 
                                :alt="data?.profile?.name" 
                                style="width:56px">
                            </div>
                            <div class="use text-whiter">
                                <div class="h5 m-0" x-text="data?.profile?.name"></div>
                                <small>
                                    <!-- <a href="/profile/edit"><span class="bi bi-pencil"></span> Edit Profil</a> -->
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <ul class="listview image-listview flush transparent">
                            <!-- <li>
                                <a href="/profile_edit" class="item">
                                    <i class="fs-4 me-2 bi bi-pencil text-primary"></i>
                                    <span>Edit Profil</span>
                                </a>
                            </li>
                            <li>
                                <a href="/change_password" class="item">
                                    <i class="fs-4 me-2 bi bi-asterisk text-primary"></i>
                                    <span>Ganti Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="/invoice" class="item">
                                    <i class="bi bi-receipt fs-4 text-primary me-2"></i>
                                    <span>Transaksi Saya</span>
                                </a>
                            </li> -->
                        </ul>

                        <!-- <div class="listview-title mt-2">Administrasi</div>
                        <ul class="listview image-listview flush transparent">
                            <li>
                                <a href="/allsantri" class="item">
                                    <i class="bi bi-people-fill fs-4 text-primary me-2"></i>
                                    <span>Data Santri</span>
                                </a>
                            </li>
                            <li>
                                <a href="/presensi" class="item">
                                    <i class="bi bi-calendar2-check-fill fs-4 text-primary me-2"></i>
                                    <span>Cek Presensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/settings" class="item">
                                    <i class="bi bi-gear-fill fs-4 text-primary me-2"></i>
                                    <span>Pengaturan Aplikasi</span>
                                </a>
                            </li>
                        </ul> -->

                        <div class="listview-title mt-2">
                            Aplikasi Tarbiyya
                            <span>v<?= $version; ?></span>
                        </div>
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
                                <a href="/profile/delete" class="item">
                                    <i class="bi bi-file-earmark-text text-danger fs-4 me-2"></i>
                                    <span>Hapus Akun</span>
                                </a>
                            </li>
                            <!-- <li>
                                <a href="/page/faq" class="item">
                                    <i class="bi bi-patch-question text-primary fs-4 me-2"></i>
                                    <span>Pertanyaan Umum</span>
                                </a>
                            </li> -->
                        </ul>

                        <div class="listview-title mt-2"></div>
                        <ul class="listview image-listview flush transparent border-top">
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