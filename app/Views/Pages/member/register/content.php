<div id="template-container" x-data="member_register()">

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/walisantri/thumbnail-notif.png" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h2 class="my-3">Registrasi Data</h2>
                <p>Silahkan isi formulir berikut untuk membuat akun</p>
            </div>

            <div class="section mt-1 mb-5">
                <div>
                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" x-model="data.name" required>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" x-model="data.email" required>
                        </div>
                    </div>
                    
                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="whatsapp">No. Whatsapp</label>
                            <small>&bull; Awali dengan 62, mis. 6289xxxxxx</small>
                            <input type="text" class="form-control" id="whatsapp" x-model="data.whatsapp" required>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="identity">Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="pwd" autocomplete="new-password" x-model="data.password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>
                    
                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="identity">Ulangi Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="repeat-pwd" autocomplete="new-password" x-model="data.repeat_password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group border-top mt-3 pt-3">
                        <button type="button" x-on:click="register" class="btn btn-primary btn-block btn-lg">Daftar Akun</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>