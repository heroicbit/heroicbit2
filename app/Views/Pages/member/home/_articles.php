<section id="latest-articles" class="py-2" style="background-color: #30D6CC;">
    <div class="py-3 px-0">
        <div class="d-flex mb-2 px-3">
            <h5 class="m-0 me-auto text-dark">Info Terbaru</h5>
            <a href="{site_url()}pustaka">
                <small class="text-primary fw-bold" style="font-size: 12px">
                    LIHAT SEMUA
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
        <div class="swiper swiper-article" x-init="initSwiper">
            <div class="swiper-wrapper py-2">
                <template x-for="article in data.articles">
                    <div class="swiper-slide">
                        <a :href="`{site_url()}pustaka/${ article.id }/${ article.slug }`">
                            <div class="card card-hover rounded-4">
                                <img :src="article.featured_image" class="card-img-top rounded-top-4" alt="..."/>
                                <div class="card-body py-3 px-2" style="min-height: 110px;">
                                    <div class="mb-2 text-truncate-2" x-text="article.title"></div>
                                    <small style="font-size: 12px" class="card-text" x-text="`Oleh ` + article.author_name"></small>
                                </div>
                            </div>
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>