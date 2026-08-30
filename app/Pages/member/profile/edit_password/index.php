<div id="member-profile-edit-password" x-data="profile_edit_password()">

    <div class="appHeader bg-brand">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton text-white">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle text-white">Ganti Kata Sandi</div>
        <div class="right"></div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow">

        <div class="section full mt-1">
            <div class="section-title">Kata Sandi Baru</div>

            <div class="wide-block pt-2 pb-2">

                <div class="form-group boxed">
                    <div class="text-start input-wrapper">
                        <label class="form-label" for="password">Kata Sandi Baru</label>
                        <div class="position-relative">
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="password" autocomplete="new-password" x-model="model.password" required>
                            <i x-on:click="showPwd = !showPwd" class="input-icon-append">
                                <ion-icon id="pw-icon" :name="showPwd ? 'eye-outline' : 'eye-off-outline'"></ion-icon>
                            </i>
                        </div>
                        <small class="text-danger" x-show="errors.password" x-text="errors.password"></small>
                    </div>
                </div>

                <div class="form-group boxed">
                    <div class="text-start input-wrapper">
                        <label class="form-label" for="repeat_password">Konfirmasi Kata Sandi Baru</label>
                        <div class="position-relative">
                            <input :type="showPwd ? 'text' : 'password'" class="form-control" id="repeat_password" autocomplete="new-password" x-model="model.repeat_password" required>
                        </div>
                        <small class="text-danger" x-show="errors.repeat_password" x-text="errors.repeat_password"></small>
                    </div>
                </div>

                <div class="form-group mt-2 mb-2">
                    <button type="button" x-on:click="save" class="btn btn-primary btn-block fs-6">SIMPAN</button>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

</div>
