<div id="member_videos_detail" x-data="member_videos_detail($router.params.id)">
    <div class="appHeader">
        <div class="left">
            <a href="javascript:void()" onclick="history.back()" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle" x-text="title"></div>
        <div class="right">
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-success-2 rounded-bottom-4">
            <div class="section mt-0 p-0" x-show="video.length > 0">
                <div class="card border-top pb-3">
                    <template x-if="video[0]?.youtube_id">
                        <div class="plyr__video-embed" id="player">
                            <iframe
                            :src="`https://www.youtube.com/embed/${video[0]?.youtube_id}`"
                            allowfullscreen
                            allowtransparency
                            allow="autoplay"
                            autoplay=1
                            x-init="initPlyr()"
                            ></iframe>
                        </div>
                    </template>
                   
                    <div class="card-body px-3 pt-2 pb-3">
                        <h2 class="h5" x-text="video[0]?.title"></h2>
                        <small class="text-muted" x-text="formatDate(video[0]?.published_at)"></small>
                        <div class="card-header px-0 pt-1 pb-1 mb-2">
                            <img :src="video[0]?.avatar ? video[0]?.avatar : `${base_url}mobilekit/assets/img/walisantri/avatar/user.png`" alt="image" class="imaged w32 rounded me-1">
                            <span x-text="video[0]?.author_name"></span>
                        </div>
                        <p class="" x-html="nl2br(video[0]?.content)"></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>