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
            <div class="section mt-1 mb-5">
                <form action="app-pages.html">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email1" placeholder="Alamat email" x-model="data.username">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password1" placeholder="Kata sandi" autocomplete="off" x-model="data.password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-links mt-2">
                        <div>
                            <a href="page-register.html">Daftar Akun Baru</a>
                        </div>
                        <div><a href="page-forgot-password.html" class="text-muted">Lupa Kata Sandi?</a></div>
                    </div>

                    <div class="form-button-group">
                        <button type="button" x-on:click="login" class="btn btn-primary btn-block btn-lg">MASUK</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>