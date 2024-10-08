<div id="member_test" x-data="pageData('pages/member/test')">
    <div class="appHeader bg-primary scrolled">
        <div class="left">
        </div>
        <div class="pageTitle" x-text="data.title">Page Title</div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule" class="shadow">

        <div class="header-large-title">
            <h1 class="title" x-text="data.title"></h1>
            <h4 class="subtitle"></h4>
        </div>

        <ul class="listview image-listview flush transparent mt-3 mb-2">
            <template x-for="row in data.components">
                <li>
                    <a href="#" class="item">
                        <div class="icon-box bg-primary text-white">
                            <ion-icon x-bind:name="row.icon" role="img" class="md hydrated" aria-label="chatbubble ellipses outline"></ion-icon>
                        </div>
                        <div class="in" x-text="row.label"></div>
                    </a>
                </li>
            </template>
        </ul>

    </div>
    <!-- * App Capsule -->
</div>