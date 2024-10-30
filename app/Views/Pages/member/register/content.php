<div id="template-container" x-data="member_register()">

    <div class="appHeader">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Registrasi</div>
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
                <p>Silahkan isi formulir pendaftaran di bawah ini</p>
            </div>

            <div class="section mt-1 mb-5 px-0">
                <div>
                    <div class="form-group px-3 boxed">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" x-model="data.name" required>
                        </div>
                    </div>

                    <div class="form-group px-3 boxed">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="email">Email</label>
                            <input type="email" class="form-control" id="email" x-model="data.email" required>
                        </div>
                    </div>
                    
                    <div class="form-group px-3 boxed">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="whatsapp">No. Whatsapp</label>
                            <small>&bull; Awali dengan 62, mis. 6289xxxxxx</small>
                            <input type="text" class="form-control" id="whatsapp" x-model="data.whatsapp" required>
                        </div>
                    </div>

                    <div class="form-group px-3 boxed">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="identity">Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="pwd" autocomplete="new-password" x-model="data.password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>
                    
                    <div class="form-group px-3 boxed pb-3">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="identity">Ulangi Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="repeat-pwd" autocomplete="new-password" x-model="data.repeat_password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>
                    
                    <div class="border-top border-bottom py-3 bg-success bg-opacity-25">
                        <div class="form-group px-3 text-start">
                            <div class="d-flex justify-content-between">
                                <label class="fw-bold mb-1">Dapatkan Kode Registrasi</label>
                                <small>Kirim ulang dalam 01:00</small>
                            </div>
                            <div class="d-flex">
                                <button type="button" x-on:click="sendOTPToEmail" class="btn btn-suscess bg-success text-light btn-sm me-1"><span class="bi bi-envelope me-1"></span> Kirim Kode ke Email</button>
                                <button type="button" x-on:click="sendOTPToWA" class="btn btn-suscess bg-success text-light btn-sm"><span class="bi bi-whatsapp me-1"></span> Kirim Kode ke WhatsApp</button>
                            </div>
                        </div>
                        
                        <div class="form-group px-3 boxed">
                            <div class="text-start input-wrapper">
                                <label for="identity">Masukkan kode registrasi di sini</label>
                                <input :type="number" class="form-control" placeholder="_ _ _ _ _ _" autocomplete="new-password" x-model="data.otp" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group px-3 mt-3">
                        <button type="button" x-on:click="register" class="btn btn-primary btn-block btn-lg">Daftar Akun</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>