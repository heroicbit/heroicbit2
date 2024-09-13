<div class="appBottomMenu no-border" x-show="Alpine.store('member').showBottomMenu">
    <a href="/" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/beranda.png" style="width:28px">
            <strong>Beranda</strong>
        </div>
    </a>
    <a href="/info" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/info.png" style="width:28px">
            <strong>Kabar</strong>
        </div>
    </a>
    <a href="/tagihan" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/tagihan.png" style="width:28px">
            <strong>Tagihan</strong>
        </div>
    </a>
    <a href="/santri" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/santri.png" style="width:28px">
            <strong>Santri</strong>
        </div>
    </a>
    <a href="/profile" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
        <img src="<?= $themeURL ?>assets/img/icon/presensi.png" style="width:28px">
            <strong>Lainnya</strong>
        </div>
    </a>
</div>