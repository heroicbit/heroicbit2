<div id="template-container" x-data="member_login()">

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/walisantri/thumbnail-notif.png" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h2>Pesantren Persis 67 Benda Tasikmalaya</h2>
                <h3 class="my-3">Silakan masuk untuk melanjutkan</h3>
            </div>

            <div class="section mt-1">
                <div>
                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="identity">Email atau no.WhatsApp, mis. 6289xxxx</label>
                            <input type="text" class="form-control" id="identity" x-model="data.username">
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="text-start input-wrapper">
                            <label for="identity">Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="pwd" placeholder="Kata sandi" autocomplete="off" x-model="data.password">
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="text-start mt-2">
                        <button type="button" x-on:click="login" class="btn btn-primary btn-block btn-lg mb-2">MASUK</button>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <div><a href="/register">Daftar Akun Baru</a></div>
                            <div><a href="page-forgot-password.html">Lupa Kata Sandi?</a></div>
                        </div>
                        <div><a class="mb-1 text-info" href="/kodepesantren">Ganti Kode Pesantren</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>