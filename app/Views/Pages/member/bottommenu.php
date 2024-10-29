<div class="appBottomMenu no-border" x-show="Alpine.store('member').showBottomMenu">
    <a href="/" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/beranda-min.png" style="width:28px">
            <strong>Beranda</strong>
        </div>
    </a>
    <a href="/feeds" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/info-min.png" style="width:28px">
            <strong>Kabar</strong>
        </div>
    </a>
    <a href="/videos" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/videos.png" style="width:28px">
            <strong>Video</strong>
        </div>
    </a>
    <!-- <a href="/tagihan" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/tagihan-min.png" style="width:28px">
            <strong>Tagihan</strong>
        </div>
    </a> -->
    <a href="/santri" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/santri-min.png" style="width:28px">
            <strong>Santri</strong>
        </div>
    </a>
    <a href="/profile" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/lainnya-min.png" style="width:28px">
            <strong>Lainnya</strong>
        </div>
    </a>
</div>