<div id="template-container" x-data="member_info()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
            <img src="<?= $themeURL ?>assets/img/logo.png" alt="" style="height: 70%">
        </div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox text-white">
                <ion-icon name="notifications"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-success-2 rounded-bottom-4 pb-5" style="height: 150px; margin-bottom: 100px">

            <div class="header-large-title">
                <h1 class="title" x-text="data.title"></h1>
                <h4 class="subtitle"></h4>
            </div>

            <div class="section mt-0 p-0">
                <div class="card mb-3 rounded-0">
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="card-img-top" alt="image">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card's content.</p>
                    </div>
                </div>
                <div class="card mb-3 rounded-0">
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="card-img-top" alt="image">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card's content.</p>
                    </div>
                </div>
                <div class="card mb-3 rounded-0">
                    <img src="https://image.web.id/images/sampleaf19727c239ff431.jpg" class="card-img-top" alt="image">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                            card's content.</p>
                    </div>
                </div>

            </div>
            <hr class="pb-5">
        </div>
        
    </div>
    <!-- * App Capsule -->
</div>