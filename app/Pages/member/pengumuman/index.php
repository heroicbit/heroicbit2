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
        <p class="text-center pt-5" x-show="pengumuman.length == 0">Belum ada pengumuman</p>

        <ul class="listview image-listview media mb-2 ">
            <template x-for="(post,postIndex) in pengumuman">
                <li>
                    <a href="javascript:void()" class="item" data-bs-toggle="offcanvas" data-bs-target="#detailCanvas" aria-controls="detailCanvas" x-on:click="showDetail(postIndex)">
                        <i :class="icons[post.category]" class="fs-2 text-primary me-2"></i>
                        <div class="in">
                            <div class="title">
                                <small class="text-black-50 fw-bold d-block" x-text="post.category"></small>
                                <span x-text="post.title"></span>
                                <div class="text-muted" x-text="formatDate(post.publish_date)"></div>
                            </div>
                            <span 
                             class="badge badge-primary" 
                             x-show="Alpine.store('tarbiyya').user.date_join < post.publish_date && pengumumanRead.includes(post.id) == false">&nbsp;</span>
                        </div>
                    </a>
                </li>
            </template>

            <div class="text-center">
                <button type="button" class="btn btn-outline-secondary my-3" x-show="!empty" x-on:click="loadMore">Muat lainnya</button>
                <p class="my-3 py-3" x-show="empty">Tidak ada post lain.</p>
            </div>
        </ul>
        
        <!-- Offcanvas detail -->
        <div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
            <div class="offcanvas-header">
                <i :class="icons[detailPengumuman?.category]" class="fs-2 text-primary me-2"></i>
                <h5 class="offcanvas-title" id="detailCanvasLabel" x-text="detailPengumuman?.category"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <dl>
                    <dt class="text-black-50">Tanggal</dt>
                    <dd>
                        <small x-text="formatDate(detailPengumuman?.publish_date)"></small>
                    </dd>
                    <dt class="text-black-50">Perihal</dt>
                    <dd>
                        <h3 x-text="detailPengumuman?.title"></h3>
                    </dd>
                </dl>

                <div class="card p-2 mt-2 shadow-lg">
                    <p class="mt-2" x-html="detailPengumuman?.content"></p>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>