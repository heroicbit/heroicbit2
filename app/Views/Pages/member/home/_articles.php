<section id="latest-articles" class="pb-4">
    <div class="d-flex px-3 mb-1 justify-content-between">
        <h3 class="m-0 me-auto text-dark">Kabar Terbaru</h3>
        <a href="/member/feeds">
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

    <template x-if="data.posts">
        <div class="swiper swiper-article" x-init="initSwiperArticles">
            <div class="swiper-wrapper py-2">
                <template x-for="article in data.posts">
                    <div class="swiper-slide">
                        <a :href="`/member/feed/${ article.id }`">
                            <div class="card card-hover rounded-4">
                                <div class="thumbnail-image position-relative">
                                    <img :src="article.medias[0].url" :alt="article.title" class="card-img-top swiper-thumbnail-image rounded-top-4"/>
                                </div>
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
    </template>
</section>