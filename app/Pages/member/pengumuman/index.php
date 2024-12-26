<div id="member_pengumuman" x-data="member_pengumuman()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Pengumuman Pesantren</div>
        <!-- <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#ModalForm">
                <ion-icon name="create-outline" role="img" class="md hydrated text-white" aria-label="star outline"></ion-icon>
            </a>
        </div> -->
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">
        <p class="text-center pt-5" x-show="data.pengumuman.length == 0">Belum ada pengumuman</p>

        <ul class="listview image-listview media mb-2 ">
            <template x-for="(post,postIndex) in data.pengumuman">
                <li>
                    <a href="javascript:void()" class="item" data-bs-toggle="offcanvas" data-bs-target="#detailCanvas" aria-controls="detailCanvas" x-on:click="showDetail(postIndex)">
                        <i :class="data.icons[post.category]" class="fs-2 text-primary me-2"></i>
                        <div class="in">
                            <div class="title">
                                <small class="text-black-50 fw-bold d-block" x-text="post.category"></small>
                                <span x-text="post.title"></span>
                                <div class="text-muted" x-text="formatDate(post.publish_date)"></div>
                            </div>
                        </div>
                    </a>
                </li>
            </template>
        </ul>
        
        <!-- Offcanvas detail -->
        <div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
            <div class="offcanvas-header">
                <i :class="data.icons[detailPengumuman.category]" class="fs-2 text-primary me-2"></i>
                <h5 class="offcanvas-title" id="detailCanvasLabel" x-text="detailPengumuman.category"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <dl>
                    <dt class="text-black-50">Tanggal</dt>
                    <dd>
                        <small x-text="formatDate(detailPengumuman.publish_date)"></small>
                    </dd>
                    <dt class="text-black-50">Perihal</dt>
                    <dd>
                        <h3 x-text="detailPengumuman.title"></h3>
                    </dd>
                </dl>

                <div class="card p-2 mt-2 shadow-lg">
                    <p class="mt-2" x-html="detailPengumuman.content"></p>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>