<div id="member-register-confirm" x-data="member_register_confirm($router.params.token)">

    <div class="appHeader">
        <div class="left">
        </div>
        <div class="pageTitle">Konfirmasi Registrasi</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5 mt-5 pb-2">
        <div class="login-form mt-1">
            <div class="section">
                <img :src="data.logo" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <p>Masukkan kode registrasi untuk melanjutkan pendaftaran. Kode registrasi akan dikirimkan oleh aplikasi ke alamat email atau nomor WhatsApp Anda.</p>
            </div>
            <div class="section mt-1 mb-5 px-0">
                <div>                    
                    <div class="py-3">
                        <div class="form-group px-3 text-start">
                            <div class="d-flex justify-content-between">
                                <label class="fw-bold mb-1">Dapatkan Kode Registrasi</label>
                            </div>
                            <div class="d-flex">
                                <button type="button" x-on:click="sendOTPToEmail" class="btn bg-success text-white btn-sm me-1"><span class="bi bi-envelope me-1"></span> Kirim Kode ke Email</button>
                                <button type="button" x-on:click="sendOTPToWA" class="btn bg-success text-white btn-sm"><span class="bi bi-whatsapp me-1"></span> Kirim Kode ke WhatsApp</button>
                            </div>
                            <small>Kirim ulang dalam 01:00</small>
                        </div>
                        
                        <div class="form-group px-3 boxed mt-3 text-start">
                            <div class="text-start input-wrapper">
                                <label class="fw-bold">Masukkan kode registrasi di sini</label>
                                <input type="text" maxlength="6" class="form-control" placeholder="_ _ _ _ _ _" autocomplete="new-password" x-model="data.otp" required>
                            </div>
                            <small class="text-danger" x-show="error" x-text="error"></small>
                        </div>
                    </div>

                    <div class="form-group px-3 mt-3">
                        <button type="button" x-on:click="confirm" class="btn btn-primary btn-block btn-lg">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>