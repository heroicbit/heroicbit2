<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-primary scrolled">
        <div class="left">
        </div>
        <div class="pageTitle" x-text="title"></div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="header-large-title mb-3">
            <h1 class="title" x-text="title"></h1>
            <h4 class="subtitle"></h4>
        </div>

        <div class="ais-InstantSearch">
            <div id="searchbox" class="px-3"></div>
            <div id="hits"></div>
        </div>

    </div>
    <!-- * App Capsule -->
</div>