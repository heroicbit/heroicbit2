<style>
  header {
    background-color: <?=setting_item('theme.navbar_color')?>;
  }

  .main .headerMenu,
  .main .headerButton {
    color: <?=setting_item('theme.navbar_text_color')?> !important;
  }

  .appHeader .pageTitle {
    font-size: 20px;
    font-weight: 700;
  }

  .appHeader.scrolled {
    transition: none;
  }

  .appHeader.scrolled .pageTitle {
    opacity: 1 !important;
  }

  .appHeader .pageTitle span {
    font-size: 1.5rem;
  }
</style>

<!-- App Header -->
<div class="appHeader main" style="background-color: <?=setting_item('theme.navbar_color')?>;">
  <div class="left">
  </div>
  <div class="pageTitle">
    <span><?=service('settings')->get('Site.siteName')?></span>
  </div>
  <div class="right">
    <a href="#" class="headerButton toggle-searchbox">
      <i class="bi bi-search"></i>
    </a>
  </div>
</div>

<!-- Search Component -->
<div id="search" class="appHeader">
  <form class="search-form" action="<?=site_url('search')?>" method="get" accept-charset="utf-8">
    <div class="form-group searchbox">
      <input type="text" class="form-control" placeholder="Search...">
      <i class="input-icon">
        <ion-icon name="search-outline"></ion-icon>
      </i>
      <a href="#" class="ms-1 close toggle-searchbox">
        <ion-icon name="close-circle"></ion-icon>
      </a>
    </div>
  </form>
</div>
<!-- * Search Component -->