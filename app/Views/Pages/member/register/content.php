<div id="template-container" x-data="member_login()">

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/sample/photo/vector4.png" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Selamat Datang</h1>
                <h4>Silakan masuk untuk melanjutkan</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form action="app-pages.html">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email1" placeholder="Email address">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password1" placeholder="Password" autocomplete="off">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-links mt-2">
                        <div>
                            <a href="page-register.html">Register Now</a>
                        </div>
                        <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
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