<div id="member_feeds" x-data="member_feeds()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
        </div>
        <div class="pageTitle text-white">Kabar Pesantren</div>
        <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#ModalForm">
                <ion-icon name="create-outline" role="img" class="md hydrated text-white" aria-label="star outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-success-2 rounded-bottom-4 pb-5" style="height: 150px; margin-bottom: 100px">

            <div class="section mt-0 p-2" style="max-width:470px;margin:auto">
                <template x-for="(article,articleIndex) in feeds">
                    <div class="card border-top mb-3">
                        <div class="card-header p-1">
                            <img :src="article.avatar ? article.avatar : `${base_url}mobilekit/assets/img/walisantri/avatar/user.png`" alt="image" class="imaged w32 rounded me-1">
                            <span x-text="article.author_name"></span>
                        </div>
                        <a href="javascript:void()" x-on:click="showDetail(articleIndex)">
                            <img :src="article.medias[0].url" class="w-100" alt="image">
                        </a>
                        <div class="card-body px-3 pt-2 pb-3">
                            <small class="text-muted" x-text="formatDate(article.published_at)"></small>
                            <a href="javascript:void()" x-on:click="showDetail(articleIndex)">
                                <h5 class="card-title fs-5" x-text="article.title"></h5>
                            </a>
                            <p class="card-text" x-html="stripIntro(16, article.content, articleIndex)"></p>
                        </div>
                    </div>
                </template>

                <div class="text-center">
                    <button type="button" class="btn btn-outline-secondary my-3" x-show="!empty" x-on:click="loadMore">Muat lainnya</button>
                    <p class="my-3 py-3" x-show="empty">Tidak ada post lain.</p>
                </div>

            </div>
            <hr class="pb-5">
        </div>

        <!-- Modal Form -->
        <div class="modal fade modalbox" id="ModalForm" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rilis Info Baru</h5>
                        <a href="#" data-bs-dismiss="modal">Tutup</a>
                    </div>
                    <div class="modal-body">
                        <div class="login-form">
                            <div class="section mt-2">
                                <h1>Get started</h1>
                                <h4>Fill the form to log in</h4>
                            </div>
                            <div class="section mt-4 mb-5">
                                <form>
                                    <div class="form-group basic">
                                        <div class="input-wrapper">
                                            <label class="form-label" for="email1">E-mail</label>
                                            <input type="email" class="form-control" id="email1"
                                                placeholder="Your e-mail">
                                            <i class="clear-input">
                                                <ion-icon name="close-circle"></ion-icon>
                                            </i>
                                        </div>
                                    </div>

                                    <div class="form-group basic">
                                        <div class="input-wrapper">
                                            <label class="form-label" for="password1">Password</label>
                                            <input type="password" class="form-control" id="password1"
                                                autocomplete="off" placeholder="Your password">
                                            <i class="clear-input">
                                                <ion-icon name="close-circle"></ion-icon>
                                            </i>
                                        </div>
                                    </div>

                                    <div class="form-links mt-2">
                                        <div>
                                            <a href="#">Register Now</a>
                                        </div>
                                        <div><a href="#" class="text-muted">Forgot Password?</a></div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary btn-block btn-lg"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Modal Form -->

    </div>
    <!-- * App Capsule -->
</div>