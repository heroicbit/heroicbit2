<div class="appBottomMenu" x-show="Alpine.store('member').showBottomMenu">
    <a href="/" id="bottommenu-member" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
        </div>
    </a>
    <a href="/components" id="bottommenu-components" class="item" x-on:click.self="updateActiveBottomMenu()">
        <div class="col">
            <ion-icon name="cube-outline"></ion-icon>
        </div>
    </a>
</div>