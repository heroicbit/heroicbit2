<div id="member-login" x-data="member_login()">
    <div class="bg-image" style="background-image: url('<?=$themeURL ?>assets/img/bg-green-min.jpg'); background-repeat: no-repeat; background-size: cover; width: 100%; background-position: center; background-color: #add7cb; height: 100%; position: fixed;"></div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <div class="login-form mt-1">
            <div class="section">
                <img :src="data.logo" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h2 class="mb-5" x-text="`Aplikasi ` + data.sitename" style="color: white;text-shadow: 1px 1px 2px #666;"></h2>
                <p class="fs-5 my-3 text-white">Silakan masuk untuk melanjutkan</p>
            </div>

            <div class="section mt-1">
                <div>
                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label class="text-white fs-6" for="identity">WhatsApp/Email</label>
                            <input type="text" class="form-control" id="identity" x-model="data.username">
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label class="text-white fs-6" for="identity">Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="pwd" autocomplete="off" x-model="data.password">
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="text-start mt-2">
                        <button type="button" x-on:click="login" class="btn btn-primary btn-block btn-lg mb-2" :disabled="buttonSubmitting">
                            <span class="spinner-border spinner-border-sm me-1" x-show="buttonSubmitting" aria-hidden="true"></span>
                            MASUK
                        </button>
                        <hr>
                        <a href="/register" class="btn btn-outline-secondary bg-white btn-block btn-lg mb-2">DAFTAR</a>
                        <div class="d-flex justify-content-between mb-2">
                            <div x-show="!forceKodePesantren">
                                <a class="mb-1" href="/kodepesantren" style="font-size: 1.1rem;font-weight: 500;color: #005e55;">Ganti Pesantren</a>
                            </div>
                            <div>
                                <a href="/reset_password" style="font-size:1.1rem;color:white;text-shadow:1px 1px 2px #666">Lupa Kata Sandi?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <div id="toast-login-error" 
        class="toast-box toast-bottom bg-danger" 
        :class="errorMessage ? 'show' : ''">
        <div class="in">
            <div class="text" x-text="errorMessage"></div>
        </div>
        <button type="button" class="btn btn-sm btn-text-light" x-on:click="errorMessage = false">OK</button>
    </div>
</div>