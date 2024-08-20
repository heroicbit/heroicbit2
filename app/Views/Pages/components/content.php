<?= $this->extend('template/mobilekit/layouts/basic') ?>

<?php $this->section('content') ?>

<div id="template-container">
    <!-- App Header -->
    <div class="appHeader bg-primary scrolled">
        <div class="left">
        </div>
        <div class="pageTitle">
            Components
        </div>
        <div class="right">
            <a href="#" class="headerButton toggle-searchbox">
                <ion-icon name="search-outline"></ion-icon>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- Search Component -->
    <div id="search" class="appHeader">
        <form class="search-form">
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

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="header-large-title">
            <h1 class="title">Components</h1>
        </div>


        <div class="listview-title mt-2">Navigation</div>
        <ul class="listview image-listview flush transparent">

            <li>
                <a href="component-appbottommenu.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="albums-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Bottom Menu
                    </div>
                </a>
            </li>
            <li>
                <a href="component-appheader.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Header
                    </div>
                </a>
            </li>
            <li>
                <a href="component-animated-header.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="arrow-down-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Animated Header
                    </div>
                </a>
            </li>
            <li>
                <a href="component-appheader-tab.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="ellipsis-horizontal"></ion-icon>
                    </div>
                    <div class="in">
                        Header with Tab
                    </div>
                </a>
            </li>
            <li>
                <a href="#sidebarPanel" class="item" data-bs-toggle="offcanvas">
                    <div class="icon-box bg-primary">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Sidebar
                    </div>
                </a>
            </li>
        </ul>


        <div class="listview-title mt-2">Alerts</div>
        <ul class="listview image-listview flush transparent mb-1">
            <li>
                <a href="component-notification.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="notifications-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Notifications
                    </div>
                </a>
            </li>
            <li>
                <a href="component-toast.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="disc-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Toast
                    </div>
                </a>
            </li>
            <li>
                <a href="component-dialog.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="albums-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Dialog Box
                    </div>
                </a>
            </li>
            <li>
                <a href="component-alert.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="alert-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Alert Box
                    </div>
                </a>
            </li>
            <li>
                <a href="component-error-page.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="checkmark-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Error Page
                    </div>
                </a>
            </li>
            <li>
                <a href="component-online-detection.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="wifi-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Online / Offline Detection
                    </div>
                </a>
            </li>
        </ul>


        <div class="listview-title mt-2">Action Modals</div>
        <ul class="listview image-listview flush transparent">
            <li>
                <a href="component-action-sheet.html" class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="browsers-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Action Sheet
                    </div>
                </a>
            </li>
            <li>
                <a href="component-add-to-home.html" class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="add-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Add to Home
                    </div>
                </a>
            </li>
            <li>
                <a href="component-modal.html" class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="layers-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Modal
                    </div>
                </a>
            </li>
            <li>
                <a href="component-offcanvas.html" class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="tablet-portrait-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Offcanvas
                    </div>
                </a>
            </li>
            <li>
                <a href="component-stories.html" class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="camera-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Stories
                    </div>
                </a>
            </li>
        </ul>


        <div class="listview-title mt-2">List Groups</div>
        <ul class="listview image-listview flush transparent">
            <li>
                <a href="component-listview.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="list-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Listview
                    </div>
                </a>
            </li>
            <li>
                <a href="component-multi-listview.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="filter-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Multiple Listview
                    </div>
                </a>
            </li>
            <li>
                <a href="component-sticky-listview.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="link-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Sticky Listview
                    </div>
                </a>
            </li>
            <li>
                <a href="component-accordion.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="chevron-down-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Accordion
                    </div>
                </a>
            </li>
            <li>
                <a href="component-timeline.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="hourglass-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Timeline
                    </div>
                </a>
            </li>
            <li>
                <a href="component-comment-boxes.html" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="chatbox-ellipses-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Comment Boxes
                    </div>
                </a>
            </li>
        </ul>

        <div class="listview-title mt-2">Form Elements</div>
        <ul class="listview image-listview flush transparent">
            <li>
                <a href="component-inputs.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="pencil-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Inputs
                    </div>
                </a>
            </li>
            <li>
                <a href="component-checkbox.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="checkbox-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Checkbox
                    </div>
                </a>
            </li>
            <li>
                <a href="component-toggle.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="toggle"></ion-icon>
                    </div>
                    <div class="in">
                        Toggle
                    </div>
                </a>
            </li>
            <li>
                <a href="component-radio.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="radio-button-on-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Radio
                    </div>
                </a>
            </li>
            <li>
                <a href="component-stepper.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="add-circle"></ion-icon>
                    </div>
                    <div class="in">
                        Stepper
                    </div>
                </a>
            </li>
            <li>
                <a href="component-form-validation.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="checkmark-done"></ion-icon>
                    </div>
                    <div class="in">
                        Form Validation
                    </div>
                </a>
            </li>
            <li>
                <a href="component-form-wizard.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="book-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Form Wizard
                    </div>
                </a>
            </li>
            <li>
                <a href="component-search.html" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Search
                    </div>
                </a>
            </li>
        </ul>

        <div class="listview-title mt-2">UI Elements</div>
        <ul class="listview image-listview flush transparent">
            <li>
                <a href="component-badge.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="radio-button-off-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Badges
                    </div>
                </a>
            </li>
            <li>
                <a href="component-button.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="tablet-landscape-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Buttons
                    </div>
                </a>
            </li>
            <li>
                <a href="component-card.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="browsers-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Cards
                    </div>
                </a>
            </li>
            <li>
                <a href="component-carousel.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="albums-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Carousel
                    </div>
                </a>
            </li>
            <li>
                <a href="component-chips.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="flash-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Chips
                    </div>
                </a>
            </li>
            <li>
                <a href="component-dropdown.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="caret-down-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Dropdown
                    </div>
                </a>
            </li>
            <li>
                <a href="component-fab-button.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="add-circle-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Fab Button
                    </div>
                </a>
            </li>
            <li>
                <a href="component-icons.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="leaf-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Icons
                    </div>
                </a>
            </li>
            <li>
                <a href="component-images.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="images-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Images
                    </div>
                </a>
            </li>
            <li>
                <a href="component-pagination.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="ellipsis-horizontal-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Pagination
                    </div>
                </a>
            </li>
            <li>
                <a href="component-placeholders.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="extension-puzzle-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Placeholders
                    </div>
                </a>
            </li>
            <li>
                <a href="component-preloader.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="refresh-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Preloader
                    </div>
                </a>
            </li>
            <li>
                <a href="component-progressbar.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="flame-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Progress bar
                    </div>
                </a>
            </li>
            <li>
                <a href="component-table.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="apps-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Table
                    </div>
                </a>
            </li>
            <li>
                <a href="component-tabs.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="ellipsis-horizontal"></ion-icon>
                    </div>
                    <div class="in">
                        Tabs
                    </div>
                </a>
            </li>
            <li>
                <a href="component-tooltips.html" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="chatbox-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Tooltips
                    </div>
                </a>
            </li>
        </ul>

        <div class="listview-title mt-2">Others</div>
        <ul class="listview image-listview flush transparent mb-2">
            <li>
                <a href="component-os-detection.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="phone-portrait-outline"></ion-icon>
                    </div>
                    <div class="in">
                        OS Detection
                    </div>
                </a>
            </li>
            <li>
                <a href="component-contentbox.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="grid-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Content Boxes
                    </div>
                </a>
            </li>
            <li>
                <a href="component-divider.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="filter-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Divider
                    </div>
                </a>
            </li>
            <li>
                <a href="component-go-to-top.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="arrow-up-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Go to Top
                    </div>
                </a>
            </li>
            <li>
                <a href="component-adboxes.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="easel-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Ad Boxes
                    </div>
                </a>
            </li>

            <li>
                <a href="component-typography.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="text-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Typography
                    </div>
                </a>
            </li>
            <li>
                <a href="component-grid.html" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="grid-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Grid
                    </div>
                </a>
            </li>
        </ul>

        <!-- app footer -->
        <div class="appFooter">
            <img src="assets/img/logo.png" alt="icon" class="footer-logo mb-2">
            <div class="footer-title">
                Copyright © Mobilekit <span class="yearNow"></span>. All Rights Reserved.
            </div>
            <div>Mobilekit is PWA ready Mobile UI Kit Template.</div>
            Great way to start your mobile websites and pwa projects.

            <div class="mt-2">
                <a href="#" class="btn btn-icon btn-sm btn-facebook">
                    <ion-icon name="logo-facebook"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-twitter">
                    <ion-icon name="logo-twitter"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-linkedin">
                    <ion-icon name="logo-linkedin"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-instagram">
                    <ion-icon name="logo-instagram"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-whatsapp">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                </a>
                <a href="#" class="btn btn-icon btn-sm btn-secondary goTop">
                    <ion-icon name="arrow-up-outline"></ion-icon>
                </a>
            </div>

        </div>
        <!-- * app footer -->

    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <?= $this->include($themePath . 'partials/bottommenu') ?>
    <!-- * App Bottom Menu -->
</div>

<?php $this->endSection() ?>