<style>
  .item .icon, .item .bi { color:#555 !important; }
  .appBottomMenu { max-width: 576px !important; margin: 0 auto; }
  .btn-float strong { display: none !important; }
  .btn-float i { padding-top: 13px; }
  .btn-float {
    border-radius: 50%;
    background: #fff;
    border: 2px solid #fffa;
    padding: 13px 6px;
    width: 60px !important;
    height: 60px;
    vertical-align: middle;
    position: absolute;
    bottom: 8px;
}
.bottomMenuContainer {
  display: block;
  width: 100%;
  min-height: 56px;
  position: fixed;
  bottom: 0;
  left: 0;
}
</style>

<!-- App Bottom Menu -->
<div class="bottomMenuContainer bg-white shadow-lg">
  <div class="appBottomMenu d-flex">
    <a href="{$nav['url_type'] == 'uri' ? site_url($nav['url']) : $nav_url}" class="item {$module} {$module == $nav['url'] ? 'active' ?? ''}">
      <div class="col {$index == 2 && setting_item('theme.highlight_center_bottommenu') == '1' ? 'btn-float shadow' : ''}">
        <i class="{$nav['icon']}"></i>
        <strong>{$nav['caption']}</strong>
      </div>
    </a>
  </div>
</div>
<!-- * App Bottom Menu -->
 