<div id="member-kodepesantren" x-data="member_kodepesantren()">
    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <form action="<?= site_url('member/kodepesantren') ?>" method="post">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/logo-md-192x192.png" alt="image" class="form-image">
            </div>
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
                         x-on:keyup.enter="checkKodePesantren"
                         x-model="kode">
                            <option value="">- Pilih pesantren -</option>
                            <template x-for="pesantren in allcodes">
                                <option x-bind:value="pesantren.kode_pesantren" x-text="pesantren.nama_pesantren"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="form-button-group">
                    <button 
                     type="submit"
                     :disabled="buttonDisabled"
                     class="btn btn-primary btn-block btn-lg">
                        BUKA APLIKASI
                    </button>
                </div>

                <?php if($pesantrenID ?? null): // Dari force kode pesantren by url ?>
                    <input type="hidden" x-init="forceKodePesantren(`<?= $pesantrenID ?? '' ?>`)">
                <?php elseif($_SESSION['kodepesantren'] ?? null): // Dari form pilih kode pesantren ?>
                    <input type="hidden" x-init="registerKodePesantren(`<?= $_SESSION['pesantrenID'] ?? '' ?>`)">
                <?php endif; ?>
            </div>
        </div>
        </form>
    </div>
    <!-- * App Capsule -->

</div>