<div id="member-kodepesantren" x-data="member_kodepesantren()">
<div class="bg-image" style="background-image: url('<?=$themeURL ?>assets/img/bg-green-min.jpg'); background-repeat: no-repeat; background-size: cover; width: 100%; background-position: center; background-color: #add7cb; height: 100%; position: fixed;"></div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow pt-5">
        <?php if($pesantrenID ?? null): // Dari force kode pesantren by url ?>
            <h2>AUTO</h2>
            <input type="hidden" x-init="forceKodePesantren(`<?= $pesantrenID ?? '' ?>`)">
        <?php elseif($_SESSION['kodepesantren'] ?? null): // Dari form pilih kode pesantren ?>
            <h2>SESSION</h2>
            <input type="hidden" x-init="registerKodePesantren(`<?= $_SESSION['pesantrenID'] ?? '' ?>`)">
        <?php endif; ?>

        <form action="<?= site_url('member/kodepesantren') ?>" method="post">
        <div class="login-form mt-1">
            <div class="section">
                <img src="<?= $themeURL ?>assets/img/logo-white-min.png" alt="image" class="form-image">
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
                         x-model="kode">
                            <option value="">- Pilih pesantren -</option>
                            <template x-for="pesantren in allcodes">
                                <option x-bind:value="pesantren.kode_pesantren" x-text="pesantren.nama_pesantren"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="form-button-group" style="background-color:transparent;">
                    <button 
                     type="submit"
                     :disabled="buttonDisabled"
                     class="btn btn-outline-secondary bg-white btn-block btn-lg">
                        BUKA APLIKASI
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>
    <!-- * App Capsule -->

</div>