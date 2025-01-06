<div id="member-reset-password" x-data="member_reset_password()">
<div class="bg-image" style="background-image: url('<?=$themeURL ?>assets/img/bg-green-min.jpg'); background-repeat: no-repeat; background-size: cover; width: 100%; background-position: center; background-color: #add7cb; height: 100%; position: fixed;"></div>

    <div class="appHeader">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Reset Kata Sandi</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5 mt-5 pb-2">
        <div class="login-form mt-1">
            <div class="section">
                <img :src="logo" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <p class="text-white">Masukkan nomor WhatsApp yang Anda gunakan untuk mendaftar di aplikasi</p>
            </div>
            <div class="section mt-1 mb-5 px-0">
                <div>                    
                    <div class="py-3">
                        <div class="form-group px-3 boxed text-start">
                            <div class="text-start input-wrapper">
                                <label class="fw-bold text-white">Nomor WhatsApp</label>
                                <input type="text" class="form-control" placeholder="62xxxxxx" autocomplete="new-password" x-model="phone" required>
                            </div>
                            <small class="text-danger" x-show="error" x-text="error"></small>
                        </div>

                        <div class="d-flex justify-content-center" id="grecaptcha"></div>
                    </div>


                    <div class="form-group px-3 mt-3">
                        <button type="button" x-on:click="confirm" class="btn btn-primary btn-block btn-lg" :disabled="sending || phone.length < 10">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" x-show="sending"></span>
                            <span x-text="sending ? `Memverifikasi..` : `Atur Ulang Kata Sandi`"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

</div>