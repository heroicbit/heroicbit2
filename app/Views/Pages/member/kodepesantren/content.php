<div id="template-container" x-data="member_kodepesantren()">

    <!-- App Capsule -->
    <div id="appCapsule" class="pt-5">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/sample/photo/vector4.png" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Selamat Datang</h1>
                <h4>Silakan masukkan kode pesantren</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form action="app-pages.html">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" class="form-control text-uppercase text-center" id="kodepesantren" placeholder="Kode pesantren" x-model="kode">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-button-group">
                        <button type="button" x-on:click="checkKodePesantren" class="btn btn-primary btn-block btn-lg">CEK KODE PESANTREN</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>