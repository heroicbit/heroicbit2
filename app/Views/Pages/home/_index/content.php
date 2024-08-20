<div id="template-container">
    <div class="appHeader bg-primary scrolled">
        <div class="left">
        </div>
        <div class="pageTitle">
            Discover
        </div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="header-large-title">
            <h1 class="title">Discover</h1>
            <h4 class="subtitle">Welcome to Mobilekit</h4>
        </div>

        <div class="section mt-3 mb-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="card-subtitle">Discover</h6>
                        <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                            Dark Mode
                        </h5>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodecontent">
                        <label class="form-check-label" for="darkmodecontent"></label>
                    </div>

                </div>
            </div>
        </div>

        <div class="section mt-3 mb-3">
            <div class="card">
                <img src="<?= $themeURL ?>assets/img/sample/photo/wide4.jpg" class="card-img-top" alt="image">
                <div class="card-body">
                    <h6 class="card-subtitle">Discover</h6>
                    <h5 class="card-title">Components</h5>
                    <p class="card-text">
                        Reusable components designed for the mobile interface and ready to use.
                    </p>
                    <a href="app-components.html" class="btn btn-primary">
                        <ion-icon name="cube-outline"></ion-icon>
                        Preview
                    </a>
                </div>
            </div>
        </div>

        <div class="section mt-3 mb-3">
            <div class="card">
                <img src="<?= $themeURL ?>assets/img/sample/photo/wide2.jpg" class="card-img-top" alt="image">
                <div class="card-body">
                    <h6 class="card-subtitle">Discover</h6>
                    <h5 class="card-title">Pages</h5>
                    <p class="card-text">
                        Mobilekit comes with basic pages you may need and use in your projects easily.
                    </p>
                    <a href="app-pages.html" class="btn btn-primary">
                        <ion-icon name="layers-outline"></ion-icon>
                        Preview
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <?= $this->include($themePath . 'partials/bottommenu') ?>
    <!-- * App Bottom Menu -->
</div>