<div class="container pengumuman-home mb-5">
    <ul class="listview image-listview media mb-1 px-2" style="border-bottom: 1px solid #666; border-top: 0;">
        <li class="border-0">
            <a href="/member/pengumuman" class="item rounded-top-5 bg-brand-2 shadow">
                <i class="fs-2 text-white-50 me-2 bi bi-megaphone"></i>
                <div class="in">
                    <div class="text-light">
                        <h3 class="text-white m-0" style="font-size: 13px;">Pengumuman Baru</h3>
                        <div style="font-size: 1rem;line-height: 1.3rem;" x-text="data.pengumuman.title"></div>
                        <div class="d-flex">
                            <small class="text-white-50" style="font-size: 13px;" x-show="unreadPengumuman > 1">+<span x-text="unreadPengumuman-1">n</span> pengumuman lainnya</small>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>