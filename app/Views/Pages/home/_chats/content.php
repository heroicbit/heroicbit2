<div id="template-container">
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Chat</div>
        <div class="right">
            <a href="#" class="headerButton">
                <ion-icon name="videocam-outline"></ion-icon>
            </a>
            <a href="#" class="headerButton">
                <ion-icon name="call-outline"></ion-icon>
                <span class="badge badge-danger">1</span>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="message-divider">
            Friday, Sep 20, 10:40 AM
        </div>

        <div class="message-item">
            <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="avatar">
            <div class="content">
                <div class="title">John</div>
                <div class="bubble">
                    Hi everyone, how are you?
                </div>
                <div class="footer">8:40 AM</div>
            </div>
        </div>

        <div class="message-item">
            <img src="assets/img/sample/avatar/avatar2.jpg" alt="avatar" class="avatar">
            <div class="content">
                <div class="title">Marry</div>
                <div class="bubble">
                    I'm fine, how are you today john, do you feel good?
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>

        <div class="message-item user">
            <div class="content">
                <div class="bubble">
                    Would you please repost the photo you sent yesterday?
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>

        <div class="message-divider">
            Friday, Sep 20, 10:40 AM
        </div>

        <div class="message-item">
            <img src="assets/img/sample/avatar/avatar2.jpg" alt="avatar" class="avatar">
            <div class="content">
                <div class="title">Marry</div>
                <div class="bubble">
                    <img src="assets/img/sample/photo/1.jpg" alt="photo" class="imaged w160">
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>

        <div class="message-item">
            <img src="assets/img/sample/avatar/avatar4.jpg" alt="avatar" class="avatar">
            <div class="content">
                <div class="title">Katie</div>
                <div class="bubble">
                    Nice photo !
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>

        <div class="message-item">
            <img src="assets/img/sample/avatar/avatar2.jpg" alt="avatar" class="avatar">
            <div class="content">
                <div class="title">Marry</div>
                <div class="bubble">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vitae nisl et nibh iaculis
                    sagittis. In hac habitasse platea dictumst. Sed eu massa lacinia, interdum ex et, sollicitudin elit.
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>

        <div class="message-item user">
            <div class="content">
                <div class="bubble">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque vitae nisl et nibh iaculis
                    sagittis. In hac habitasse platea dictumst. Sed eu massa lacinia, interdum ex et, sollicitudin elit.
                </div>
                <div class="footer">10:40 AM</div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

    <!-- Add Action Sheet -->
    <div class="offcanvas offcanvas-bottom action-sheet inset" tabindex="-1" id="actionSheetAdd">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Share</h5>
        </div>
        <div class="offcanvas-body">
            <ul class="action-button-list">
                <li>
                    <a href="#" class="btn btn-list" data-bs-dismiss="offcanvas">
                        <span>
                            <ion-icon name="camera-outline"></ion-icon>
                            Take a photo
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="btn btn-list" data-bs-dismiss="offcanvas">
                        <span>
                            <ion-icon name="videocam-outline"></ion-icon>
                            Video
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="btn btn-list" data-bs-dismiss="offcanvas">
                        <span>
                            <ion-icon name="image-outline"></ion-icon>
                            Upload from Gallery
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="btn btn-list" data-bs-dismiss="offcanvas">
                        <span>
                            <ion-icon name="document-outline"></ion-icon>
                            Documents
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="btn btn-list" data-bs-dismiss="offcanvas">
                        <span>
                            <ion-icon name="musical-notes-outline"></ion-icon>
                            Sound file
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- * Add Action Sheet -->

    <!-- chat footer -->
    <div class="chatFooter">
        <form>
            <a href="#" class="btn btn-icon btn-secondary rounded" data-bs-toggle="offcanvas"
                data-bs-target="#actionSheetAdd">
                <ion-icon name="add"></ion-icon>
            </a>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" placeholder="Type a message...">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
            </div>
            <button type="button" class="btn btn-icon btn-primary rounded">
                <ion-icon name="send"></ion-icon>
            </button>
        </form>
    </div>
    <!-- * chat footer -->
</div>