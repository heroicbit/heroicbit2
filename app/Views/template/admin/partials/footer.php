<!-- Js Dependency -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/jadus/jquery-sortScroll/v1.3.0/jquery.sortScroll.js"></script>
<script type="text/javascript" src="<?= $theme_url . 'assets/chosen/chosen.jquery.js'; ?>"></script>
<script src="<?= $theme_url . 'assets/select2/select2.min.js'; ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-pjax@2.0.1/jquery.pjax.min.js"></script>
<script src="<?= $theme_url . 'assets/js/custom.js'; ?>"></script>

<div id="toastr-message" class="d-none"><?= ci()->session->flashdata('toastr'); ?></div>
<script>
    $(function() {
        // PJAX
        if ($.support.pjax) {
            $(document).pjax('a:not([data-pjax=false])', '#pjax-container');
            $(document).on('pjax:start', function() {
                NProgress.start();
            });
            $(document).on('pjax:end', function() {
                NProgress.done();
            });
        }

        // SIDEBAR
        $('#sidebar-menu, #customize-menu').metisMenu({
            activeClass: 'open'
        });
        $('#sidebar-collapse-btn').on('click', function(event) {
            event.preventDefault();
            $("#app").toggleClass("sidebar-open");
        });
        $("#sidebar-overlay").on('click', function() {
            $("#app").removeClass("sidebar-open");
        });

        // Show Toastr message from flashdata
        toastr.options = {
            "positionClass": "toast-bottom-right"
        };
        var toastrMsg = $('#toastr-message').text();
        if (toastrMsg) {
            var toastrData = JSON.parse(toastrMsg);
            toastr[toastrData['type']](toastrData['message']);
        }

        $('[data-toggle="popover"]').popover()

        var base_url = $('#base_url').val();
        $('body').addClass('loaded');

        /** Open and active class to admin menu of the current page */
        let activeMenu = $('.sidebar-menu').find('li.active');
        activeMenu.parent('ul').addClass('in');
        activeMenu.parent('ul').parent('li').addClass('active open');

        $('[data-toggle="chosen"]').chosen();

        var form = $('form');
        // form.get(0).reset();
        form.find('[data-toggle="chosen"]').trigger('chosen:updated');

        $(document).on('click', '.remove', function() {
            return confirm('Sure?');
        });

        window.setTimeout(function() {
            $(".alert").fadeOut();
        }, 10000);
        $(".alert").on('click', function() {
            $(this).fadeOut();
        })

        $(".slugify input.title").keyup(function() {
            var title = $(this).val();
            $("input.slug").val(convertToSlug(title));
        });

        $(".btn-add-type").click(function() {
            var type = $('.type').val();

            window.location.replace(base_url + 'admin/post/post/add/' + convertToSlug(type));
        });

        /** Module Setting JS */
        $('.btn-save-setting').click(function() {

            var site_title = $('#site_title').val();
            var site_logo = $('#site_logo').val();
            var theme = $('#theme').val();
            var entries_config = editor.getValue();

            $('.btn-save-setting').html('Please wait ..');

            $.post(base_url + 'admin/setting/setting/update', {
                    site_title: site_title,
                    site_logo: site_logo,
                    theme: theme,
                    entries_config: entries_config
                })
                .done(function(data) {

                    if (data.status == 'success') {
                        $('.btn-save-setting').html('Save');

                        swal("Success", "Successfully saved the setting.", "success");
                    } else {
                        swal("Error", "Something wrong with the system :(", "error");
                    }
                });

            return false;
        });

        /** Module Entry JS */
        var to_reffer = $('.slugify').data('referer');

        $("#" + to_reffer).keyup(function() {
            var content = $(this).val();
            $('.slugify').val(convertToSlug(content));
        });

        $('.btn-connect-relation').click(function() {
            var id = $('#id').val();
            var entry = $('#entry').val();
            var relation = $('#relation').val();
            var choosen = $('#choice input:checked').map(function() {
                return $(this).val();
            });

            $.post(base_url + 'admin/entry/entry/update_relation', {
                    id: id,
                    entry: entry,
                    relation: relation,
                    choosen: choosen.get()
                })
                .done(function(data) {
                    if (data = 'done') {
                        location.reload();
                    }
                });
        });

        $('.load-on-click').on('click', function() {
            $(this).html('<em>Wait..</em>').addClass('disabled');
            $(this).prop('disabled', true);
        })

        NProgress.done();
    });

    /** Helper */
    function convertToSlug(Text) {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
</script>

<!-- Reference block for JS -->
<div class="ref" id="ref">
    <div class="color-primary"></div>
    <div class="chart">
        <div class="color-primary"></div>
        <div class="color-secondary"></div>
    </div>
</div>

<!-- Embed Entry Js -->
<?= embed_entry_script(); ?>