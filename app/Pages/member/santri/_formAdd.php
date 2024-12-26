<div class="modal fade dialogbox" id="addSantriModal" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Santri</h5>
            </div>
            <form>
                <div class="modal-body text-start mb-2">
                    <p>Masukkan NIS atau NISN santri pada kolom di bawah ini</p>
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="form-label" for="email1">NIS/NISN</label>
                            <input type="text" class="form-control" x-model="searchNIS">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <p class="alert alert-warning" x-show="NISFound.found == 0">Data dengan NIS/NISN yang dicari tidak ditemukan.</p>
                    <div class="listview image-listview media" x-show="NISFound.nama_santri">
                        <li>
                            <div class="item px-0">
                                <div class="imageWrapper">
                                    <img src="https://tarbiyya.test/mobilekit/assets/img/walisantri/avatar/m.svg" alt="image" class="imaged w64">
                                </div>
                                <div class="in">
                                    <div>
                                        <span x-text="NISFound.nama_santri"></span>
                                        <div class="text-muted" x-text="NISFound.class_name"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-text-primary" x-show="NISFound.found != 1" x-on:click="checkNIS">Cek Data</button>
                        <button type="button" class="btn btn-success" x-show="NISFound.nama_santri" x-on:click="addSantri">Tambahkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
