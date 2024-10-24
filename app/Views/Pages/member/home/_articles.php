<section id="latest-articles">
    <div class="d-flex px-3 mb-1 justify-content-between">
        <h4 class="m-0 me-auto text-dark">Kabar Terbaru</h4>
        <a href="{site_url()}pustaka">
            <small class="text-primary fw-bold" style="font-size: 12px">
                Lihat Semua
            </small>
        </a>
    </div>
    <div x-show="data.loading" class="swiper swiper-article">
        <div class="swiper-wrapper py-2">
            <template x-for="data in Array(3)">
                <div class="swiper-slide">
                    <img
                    src="https://mobilekit.bragherstudio.com/view29/assets/img/sample/photo/wide0.jpg"
                    class="w-100 rounded-4"
                    alt="feed"
                    />
                </div>
            </template>
        </div>
    </div>
    <div class="swiper swiper-article" x-init="initSwiperArticles">
        <div class="swiper-wrapper py-2">
            <template x-for="article in data.microblog">
                <div class="swiper-slide">
                    <a :href="`/member/feed/${ article.id }`">
                        <div class="card card-hover rounded-4">
                            <img :src="article.medias[0].url" :alt="article.title" class="card-img-top swiper-thumbnail-image rounded-top-4"/>
                            <div class="card-body py-3 px-2" style="min-height: 110px;">
                                <div class="text-elipsis text-elipsis-3" style="line-height:1.1rem" x-text="article.title"></div>
                                <small style="font-size: 12px" class="card-text" x-text="`Oleh ` + article.author_name"></small>
                            </div>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </div>
</section>