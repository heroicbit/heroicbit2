<div id="template-container" x-data="member_info()">
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

            <div class="header-large-title">
                <h1 class="title mt-0" x-text="data.title"></h1>
                <h4 class="subtitle mt-0"></h4>
            </div>

            <div class="section mt-0 p-0">
                <div class="card border-top">
                    <div class="card-header p-1">
                        <img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" alt="image" class="imaged w32 rounded me-1">
                        <span>Edward Lindgren</span>
                    </div>
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="" alt="image">
                    <div class="card-body">
                        <small class="text-muted">19 September 2024</small>
                        <h5 class="card-title fs-5">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>
                <div class="card border-top">
                    <div class="card-header p-1">
                        <img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" alt="image" class="imaged w32 rounded me-1">
                        <span>Edward Lindgren</span>
                    </div>
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="" alt="image">
                    <div class="card-body">
                        <small class="text-muted">19 September 2024</small>
                        <h5 class="card-title fs-5">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>
                <div class="card border-top">
                    <div class="card-header p-1">
                        <img src="https://masagiapp.com/uploads/masagi/entry_files/le-me.jpg" alt="image" class="imaged w32 rounded me-1">
                        <span>Edward Lindgren</span>
                    </div>
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="" alt="image">
                    <div class="card-body">
                        <small class="text-muted">19 September 2024</small>
                        <h5 class="card-title fs-5">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of
                            the card's content.</p>
                    </div>
                </div>

                <div class="text-center">
                    <button class="btn btn-outline-secondary my-3">Lihat lainnya</button>
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