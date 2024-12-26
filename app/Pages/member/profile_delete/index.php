<div id="member-profile-delete" x-data="member_profile_delete()">

    <div class="appHeader">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Hapus Akun</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5 mt-5 pb-2">
        <div class="delete-form text-start mt-1">
            <div class="section mt-1">
                <h4>Aksi ini akan menghapus akun Anda di aplikasi <span x-text="Alpine.store('member').tarbiyyaSetting.site_title"></span></h4>
                <p>Kami menyediakan fasilitas penghapusan akun ini dalam rangka memenuhi kebijakan privasi aplikasi dan demi kenyamanan Anda sebagai pengguna.
                    Anda dapat melakukan penghapusan akun bilamana dirasa sudah tidak memerlukan lagi akun pada aplikasi ini.
                </p>
                <p class="fw-bold">Untuk melanjutkan penghapusan akun, silakan masukkan nomor whatsapp dan kata sandi Anda yang aktif.</p>
            </div>

            <div class="section mt-1 mb-5 px-0">
                <div>
                    <div class="form-group px-3 boxed">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="whatsapp">Nomor WhatsApp</label>
                            <input type="text" class="form-control" id="whatsapp" x-model="data.whatsapp" required>
                        </div>
                    </div>
                    
                    <div class="form-group px-3 boxed text-start">
                        <div class="text-start input-wrapper">
                            <label class="fw-bold" for="identity">Kata Sandi</label>
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="pwd" autocomplete="new-password" x-model="data.password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-off-outline' : 'eye-outline'"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group px-3 mt-3">
                        <button type="button" x-on:click="removeAccount" class="btn btn-danger btn-block btn-lg">Hapus Akun</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <div id="toast-delete-error" 
        class="toast-box toast-bottom bg-warning" 
        :class="errorMessage ? 'show' : ''"
        x-init="$watch('errorMessage', v => v ? setTimeout(() => errorMessage = false, 3000) : null)">
        <div class="in">
            <div class="text-dark" x-text="errorMessage"></div>
        </div>
        <button type="button" class="btn btn-sm" x-on:click="errorMessage = false">OK</button>
    </div>
</div>