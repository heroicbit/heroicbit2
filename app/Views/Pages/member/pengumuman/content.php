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
            <ul class="listview image-listview media mb-2 ">
                <template x-for="(post,postIndex) in data.posts">
                    <li>
                        <a href="javascript:void()" class="item" data-bs-toggle="offcanvas" data-bs-target="#detailCanvas" aria-controls="detailCanvas" x-on:click="showDetail(postIndex)">
                            
                            <i class="bi bi-megaphone-fill fs-2 text-primary me-2"></i>
                            <div class="in">
                                <div class="title"><span  x-text="post.title"></span>
                                    <div class="text-muted" x-text="post.date"></div>
                                </div>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>
        

        <!-- Offcanvas detail -->
        <div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="detailCanvasLabel">Detail Pengumunan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h2 x-text="detailFeed.title"></h2>
                <small x-text="detailFeed.date"></small>
                <p class="mt-2" x-text="detailFeed.body"></p>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>