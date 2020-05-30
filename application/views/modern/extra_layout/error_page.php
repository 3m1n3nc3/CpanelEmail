  <?php $this->load->view($this->h_theme.'/extra_layout/public_header');?>
  <div class="content-wrapper text-lg"> 
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?=$error?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo (isset($this->is_admin) ? site_url('admin/admin/dashboard') : site_url('welcome/dashboard')) ?>">Home</a></li>
              <li class="breadcrumb-item active"><?=$error?></li>
            </ol>
          </div>
        </div>
      </div> 
    </section>
 
    <section class="content">
      <div class="error-page">
        <h2 class="headline font-weight-bold <?=$class?>"><?=$code?></h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle <?=$class?>"></i> <?=$title?> </h3>

          <p>
            <?=$message?>
            Meanwhile, you may <a href="<?php echo site_url() ?>">return to homepage</a> or <a href="<?php echo (isset($this->is_admin) ? site_url('admin/admin/dashboard') : site_url('dashboard')) ?>">head to dashboard</a>!
          </p> 

        </div> 
      </div> 
    </section> 
  </div> 
  <?php $this->load->view($this->h_theme.'/footer', ['page' => 'Error Page', 'hide_this_div' => 1]);?>
