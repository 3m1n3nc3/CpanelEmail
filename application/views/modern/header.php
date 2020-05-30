<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<?php 
  header('Content-type: text/html');  
  $user = $this->user_model->getUser($this->uid, 1);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= $this->creative_lib->fetch_image(my_config('favicon'), 2); ?>" type="image/png">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?=$title?></title>

    <!-- Font Awesome Icons --> 
    <link rel="stylesheet" href="<?= base_url('backend/modern/plugins/fontawesome-free/css/all.min.css'); ?>">  

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('backend/modern/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
    
    <!-- DateTimePicker -->
    <link rel="stylesheet" href="<?= base_url('backend/modern/plugins/datetimepicker/jquery.datetimepicker.css'); ?>">
    
    <!-- Summernote -->
    <link rel="stylesheet" href="<?= base_url('backend/modern/plugins/jodit/jodit.css'); ?>">
    
    <!-- Datatables -->
    <?php if (isset($use_table) && $use_table): ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/modern/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <?php endif ?> 

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('backend/modern/dist/css/adminlte.min.css'); ?>">
    <!-- Croppie -->
    <link rel="stylesheet" href="<?= base_url('backend/css/plugins/croppie.css'); ?>"> 

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style type="text/css">
      .calendar {
        -webkit-user-select: none; -moz-user-select: none;
      }
    </style> 

  </head>
  <body class="hold-transition sidebar-mini accent-info" data-page="<?=$page?>">
    
    <script type="text/javascript">
      siteUrl = "<?=site_url()?>";
      site_theme = "<?=$this->h_theme?>";   
      function is_logged() {
        return '<?=$this->account_data->logged_in()?>';
      }
      function site_url(path){
        return '<?=site_url()?>' + path;
      }
    </script>
 
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-light navbar-warning text-sm">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=site_url('dashboard')?>" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=site_url('page/contact-us')?>" class="nav-link">Contact</a>
          </li>
        </ul> 
        
        <!-- SEARCH FORM -->
        <?= form_open('search', ['class' => 'form-inline ml-3', 'method' => 'post'])?> 
          <div class="input-group input-group-sm">
            <input type="text" name="customer" class="form-control form-control-navbar"  placeholder="Search Customer" aria-label="Search" value="<?= set_value('customer')?>">
            <div class="input-group-append">
              <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        <?= form_close()?> 

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

          <!-- Add Messages Dropdown Menu Here -->       
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" id="get-notifications" href="#">
              <i class="far fa-bell" id="notification_bell"></i>
              <span class="badge badge-danger navbar-badge" id="new__notif"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notifications__list">

            </div>
            <div class="text-center preloader d-none"> 
              <div class="spinner-light text-info spinner-grow" role="status"> 
                <span class="sr-only">Loading...</span> 
              </div>
            </div>
          </li>

          <!-- Add Notifications Dropdown Menu Here -->  

          <?php if(UID): ?> 
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <img src="<?= $this->creative_lib->fetch_image($user['image'], 3); ?>" class="user-image img-circle elevation-2" alt="User Image">
              <span class="d-none d-md-inline"><?=USERNAME?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <!-- User image -->
              <li class="user-header bg-light">
                <img src="<?= $this->creative_lib->fetch_image($user['image'], 3); ?>" class="img-circle elevation-2" alt="User Image">

                <p>
                  <?=FULLNAME?>  
                </p>
              </li> 
              <!-- Menu Footer-->
              <li class="user-footer bg-warning">
                <a href="<?=site_url('profile/view/my_profile')?>" class="btn btn-default btn-flat"><?=lang('profile')?></a>
                <a href="<?=site_url('login/logout')?>" class="btn btn-default btn-flat float-right">Sign out</a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-light-warning elevation-4">
        <!-- Brand Logo -->
        <a href="<?= site_url() ?>" class="brand-link text-sm">
          <img src="<?= $this->creative_lib->fetch_image(my_config('site_logo'), 2); ?>" alt="<?=my_config('site_name')?> Logo" class="brand-image elevation-3"
          style="opacity: .8">
          <span class="brand-text font-weight-light"> <?=my_config('site_name_abbr') ?? '&nbsp;'?></span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar"> 

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
              <?php if (has_privilege('dashboard')): ?>
                <li class="nav-item">
                  <a href="<?= site_url('dashboard')?>" class="nav-link <?= ($page == "dashboard" ? 'active' : '')?>">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                      Dashboard
                      <!-- <span class="right badge badge-danger">New</span> -->
                    </p>
                  </a>
                </li>
              <?php endif;?> 

              <?php if (has_privilege('customers')): ?>
                <li class="nav-item">
                  <a href="<?= site_url('users/list')?>" class="nav-link <?= ($page == "users" ? 'active' : '')?>">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Users
                    </p>
                  </a>
                </li> 
              <?php endif;?> 


              <?php if (has_privilege('manage-configuration')): ?>
                <li class="nav-item">
                  <a href="<?= site_url('admin/configuration')?>" class="nav-link <?= ($page == "configuration" ? 'active' : '')?>">
                    <i class="fa fa-cogs nav-icon"></i>
                    <p>
                      Configuration
                    </p>
                  </a>
                </li> 
              <?php endif; ?>

              <?php if (has_privilege('manage-privilege')): ?>
                <li class="nav-item">
                  <a href="<?= site_url('admin/permissions')?>" class="nav-link <?= ($page == "privilege" ? 'active' : '')?>">
                    <i class="fa fa-id-card nav-icon"></i>
                    <p>
                      User Privileges
                    </p>
                  </a>
                </li> 
              <?php endif; ?>
 
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content (Closes at view/classic/footer.php)-->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <?php $sub_page_title = isset($sub_page_title) ? ' - ' . $sub_page_title : ''?>
                <h1 class="m-0 text-dark">
                  <span class="font-weight-bold"><?= ucwords(supr_replace($page))?></span>
                  <?= $sub_page_title ?>
                </h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <a href="<?= site_url() ?>">Home</a>
                  </li>
                  <?php if ($this->uri->segment(1, NULL) && !$this->uri->segment(2, NULL)): ?>
                    <li class="breadcrumb-item active">
                      <?= ucwords(supr_replace($this->uri->segment(1, NULL))) ?>
                    </li> 
                  <?php elseif ($this->uri->segment(1, NULL) && $this->uri->segment(2, NULL)): ?>
                    <li class="breadcrumb-item">
                      <a href="<?= site_url($this->uri->segment(1, NULL)) ?>"><?= ucwords(supr_replace($this->uri->segment(1, NULL))) ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                      <?= ucwords(supr_replace($this->uri->segment(2, NULL))) ?>
                    </li> 
                  <?php else: ?> 
                    <li class="active">
                        Home
                    </li>
                  <?php endif; ?> 
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
