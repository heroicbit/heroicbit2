<div id="member-kodepesantren" x-data="member_kodepesantren()">
<div class="bg-image" style="background-image: url('<?=$themeURL ?>assets/img/bg-green-min.jpg'); background-repeat: no-repeat; background-size: cover; width: 100%; background-position: center; background-color: #add7cb; height: 100%; position: fixed;"></div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <?php if($pesantrenID ?? null): // Dari force kode pesantren by url ?>
            <div x-init="forceKodePesantren(`<?= $pesantrenID ?>`)"></div>
        <?php endif; ?>

        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/logo-white-min.png" alt="image" class="form-image">
            </div>
            
            <?php if(! ($pesantrenID ?? null)): ?>
                <div class="section mt-3">
                    <h1>Selamat Datang</h1>
                    <h4>Silakan masukkan kode pesantren</h4>
                </div>
                <div class="section mt-1 mb-5">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <select 
                            name="kodepesantren"
                            class="form-select" 
                            id="kodepesantren"
                            x-on:change="enableButton"
                            x-model="kode">
                                <option value="">- Pilih pesantren -</option>
                                <template x-for="pesantren in allcodes">
                                    <option x-bind:value="pesantren.sitename" x-text="pesantren.nama_pesantren"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="form-button-group" style="background-color:transparent;">
                        <button 
                        type="submit"
                        :disabled="buttonDisabled"
                        x-on:click="choosePesantren"
                        class="btn btn-outline-secondary bg-white btn-block btn-lg">
                            <span class="spinner-border spinner-border-sm me-1" x-show="buttonSubmitting" aria-hidden="true"></span>
                            BUKA APLIKASI
                        </button>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <!-- * App Capsule -->

</div>