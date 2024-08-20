<style>
  .profileBox {
    background: #fafafa;
  }
  .profileBox .image-wrapper .avatar {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    object-fit: cover;
  }    
</style>

<!-- Sidebar Menu -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarPanel">
  <div class="offcanvas-body">
    
    <!-- profile box -->
    <div class="profileBox">
        <div class="image-wrapper">
          <img src="<?=base_url().'views/mobilekit/assets/img/default-avatar-m.png'?>" alt="avatar" class="avatar">
        </div>
        <div class="in">
            <strong class="text-dark">Yllumi</strong>
            <div class="text-secondary">
                <ion-icon name="location"></ion-icon>
                Padalarang
            </div>
        </div>
        <a href="#" class="close-sidebar-button text-secondary" data-bs-dismiss="offcanvas">
            <ion-icon name="close"></ion-icon>
        </a>
    </div>
    <!-- * profile box -->

    <ul class="listview flush transparent no-line image-listview mt-2 pt-2">
      <li>
        <a class="item" data-bs-dismiss="offcanvas" href="<?=site_url('courses')?>">
          <div class="icon-box">
              <ion-icon name="book"></ion-icon>
          </div>
          <div class="in">
              Learn
          </div>
        </a>
      </li>
      <li>
        <a class="item" data-bs-dismiss="offcanvas" href="<?=site_url('catalog')?>">
          <div class="icon-box">
              <ion-icon name="gift"></ion-icon>
          </div>
          <div class="in">
              Catalog
          </div>
        </a>
      </li>
      <li>
        <a class="item" data-bs-dismiss="offcanvas" href="<?=site_url('earning')?>">
          <div class="icon-box">
              <ion-icon name="cash"></ion-icon>
          </div>
          <div class="in">
              Referral
          </div>
        </a>
      </li>

    </ul>
  </div>
</div>
<!-- * Sidebar Menu -->