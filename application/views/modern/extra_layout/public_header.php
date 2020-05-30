<?php $title = isset($title) ? $title  : 'welcome' ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?=$title?></title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 
        <link rel="icon" href="<?= $this->creative_lib->fetch_image(my_config('favicon'), 2); ?>" type="image/png">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url('backend/modern/plugins/fontawesome-free/css/all.min.css'); ?>">  
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url('backend/modern/dist/css/adminlte.min.css'); ?>">
		<![endif]-->
		<!-- Google Font -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body class="hold-transition layout-top-nav">
		<script type="text/javascript">
			site_url = '<?php echo base_url(); ?>';
		</script>
		<div class="wrapper">

			<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
				<div class="container">
					<a href="<?php echo base_url() ?>" class="navbar-brand">
						<img src="<?= $this->creative_lib->fetch_image(my_config('site_logo'), 2); ?>" alt="<?=my_config('site_name')?> Logo" class="brand-image"
						style="opacity: .8">
						<span class="brand-text font-weight-light"><?=my_config('site_name')?></span>
					</a>
	 				
					<button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse order-3" id="navbarCollapse">
						<!-- Left navbar links -->
						<ul class="navbar-nav">
							<li class="nav-item">
								<a href="<?php echo base_url() ?>" class="nav-link">Home</a>
							</li>
							<li class="nav-item">
								<a href="<?= site_url('contact-us')?>" class="nav-link">Contact</a>
							</li> 
						</ul> 
					</div> 
				</div>
			</nav>
			<!-- /.navbar -->




























 
