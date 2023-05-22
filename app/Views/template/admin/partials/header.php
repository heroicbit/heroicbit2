<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<meta http-equiv="x-pjax-version" content="v123">
<base href="{site_url('admin')}">

<title><?= $page_title; ?></title>

<meta name='robots' content='noindex, nofollow' />

<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="apple-touch-icon" href="apple-touch-icon.png">

<!-- Theme initialization -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" integrity="sha512-42kB9yDlYiCEfx2xVwq0q7hT4uf26FUgSIZBK8uiaEnTdShXjwr8Ip1V4xGJMg3mHkUt9nNuTDxunHF0/EgxLQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.css">

<!-- Vendor JS: jquery.js',metisMenu.js',nprogress.js',popper.js',bootstrap.js' -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js" integrity="sha512-bUg5gaqBVaXIJNuebamJ6uex//mjxPk8kljQTdM1SwkNrQD7pjS+PerntUSD+QRWPNJ0tq54/x4zRV8bLrLhZg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>NProgress.start();</script>

<!-- FIELDTYPE ASSETS -->

<!-- jquery Choosen -->
<link rel="stylesheet" href="<?= $theme_url . 'assets/chosen/chosen.min.css'; ?>">
<link rel="stylesheet" href="<?= $theme_url . 'assets/chosen/component-chosen.min.css'; ?>">

<!-- Select2 -->
<link rel="stylesheet" href="<?= $theme_url . 'assets/select2/select2.min.css'; ?>">

<!-- Tagsinput -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">

<!-- Can be replace with app-blue, green, orange, purple, red, seagreen, pink -->
<link rel="stylesheet" id="theme-style" href="<?= $theme_url . 'assets/css/app-blue.css?v1.3'; ?>">
<link rel="stylesheet" id="theme-style" href="<?= $theme_url . 'assets/css/custom.css?v1.1'; ?>">

<!-- Fancybox -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

<!-- Toastr for toast notification -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Custom -->
<style>
	img.avatar.small {
		object-fit: cover;
		border-radius: 50%;
		height: 50px;
		width: 50px;
	}

	img.avatar.medium {
		object-fit: cover;
		border-radius: 50%;
		height: 60px;
		width: 60px;
	}

	img.avatar.large {
		object-fit: cover;
		border-radius: 50%;
		height: 80px;
		width: 80px;
	}

	.bgtop {
		background-color: <?= setting_item('theme.admin_bgcolor') ?>;
		<?= setting_item('theme.admin_bg_css') ?>?? ''}
	}

	.sidebar a {
		cursor: pointer;
	}
	.sidebar .sidebar-menu>li.active>a,
	.sidebar .sidebar-menu>li.active>a:hover {
		background-color: <?= setting_item('theme.admin_bgcolor') ?> !important;
	}
</style>

{embed_entry_style()}