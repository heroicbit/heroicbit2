<div id="template-container" x-data="member_home()">
    <div class="appHeader bg-brand">
        <div class="left ps-2">
            <img :src="data.logo ? data.logo : `<?= $themeURL ?>assets/img/logo.png`" alt="" style="height: 36px">
        </div>
        <div class="right">
            <template x-if="Alpine.store('tarbiyya').user.role != `member`">
                <div class="dropdown" x-data="{ open: false }" @click.outside="open = false">
                    <button class="btn text-white pe-0" type="button" @click="open = !open">
                        <i class="bi bi-window-stack"></i>
                    </button>
                    <ul class="card" x-show="open" style="width: 200px; list-style: none; position: absolute; right: 0px; padding: 10px; display: none;">
                        <li><a class="fs-6 d-flex align-items-center gap-2" href="/member/checkin">
                                <i class="bi bi-person-check"></i>
                                <span>Halaman Presensi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </template>
        </div>
    </div>

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="bg-brand backlayer"></div>
        <div class="header-large-title my-3 ms-1">
            <div class="d-flex align-items-center justify-content-start gap-3">
                <div class="use text-white">
                    <div>Ahlan wa sahlan,</div>
                    <div class="h5 m-0" x-text="Alpine.store('tarbiyya').user.name"></div>
                </div>
            </div>
        </div>

        <?= $this->include('member/home/_icons') ?>

        <?= $this->include('member/home/_pengumuman') ?>

        <?= $this->include('member/home/_articles') ?>

        <?= $this->include('member/home/_kajian') ?>

    </div>
    <!-- * App Capsule -->
</div>