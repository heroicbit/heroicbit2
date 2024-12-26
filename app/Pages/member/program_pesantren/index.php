<div id="member_program_pesantren" x-data="member_program_pesantren()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Program Pesantren</div>
        <!-- <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#ModalForm">
                <ion-icon name="create-outline" role="img" class="md hydrated text-white" aria-label="star outline"></ion-icon>
            </a>
        </div> -->
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">
            <ul class="listview link-listview mb-2">
                <template x-for="post in data.pages">
                    <li>
                        <a href="javascript:void()" class="item" data-bs-toggle="offcanvas" data-bs-target="#detailCanvas" aria-controls="detailCanvas" x-on:click="showDetail(post.slug)">
                            <div class="in">
                                <div class="title">
                                    <span x-text="post.menu_title"></span>
                                </div>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>
        

        <!-- Offcanvas detail -->
        <div class="offcanvas offcanvas-fullwidth offcanvas-end" tabindex="-1" id="detailCanvas" aria-labelledby="detailCanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="detailCanvasLabel" x-text="detail.title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <p class="mt-2" x-html="detail.content"></p>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>