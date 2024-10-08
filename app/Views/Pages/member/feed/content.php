<div id="member_feed" x-data="member_feed($router.params.id)">
    <div class="appHeader">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Detail Info</div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-success-2 rounded-bottom-4">
            <div class="section mt-0 p-0">
                <div class="card border-top pb-3">
                    <img :src="feed[0].medias[0].url" class="w-100" alt="image">
                    <div class="card-header p-1">
                        <img :src="feed[0].avatar ? feed[0].avatar : `${base_url}mobilekit/assets/img/walisantri/avatar/user.png`" alt="image" class="imaged w32 rounded me-1">
                        <span x-text="feed[0].author_name"></span>
                    </div>
                    <div class="card-body px-3 pt-2 pb-3">
                        <h1 class="h2 card-title" x-text="feed[0].title"></h1>
                        <p class="card-text" x-html="nl2br(feed[0].content)"></p>
                        <small class="text-muted" x-text="formatDate(feed[0].published_at)"></small>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>