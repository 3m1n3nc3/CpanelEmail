      <?php if(!isset($hide_this_div)):?>
      </div> 
      <?php endif;?>
      <!-- /.content-wrapper (Opened at view/classic/header.php)-->
      
      <!-- Load the actions modal here -->
      <?php
        $param = array(
          'modal_target' => 'actionModal',
          'modal_title' => 'Action Modal',
          'modal_size' => 'modal-sm',
          'modal_content' => ' 
            <div class="m-0 p-0 text-center" id="upload_loader1">
                <div class="loader"><div class="spinner-grow text-warning"></div></div> 
            </div>'
        );
        $this->load->view($this->h_theme.'/modal', $param);
      ?> 
      <!-- Main Footer -->
      <footer class="main-footer text-sm">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
      <!--     <span class="mx-5"> 
            <a href="<?= site_url($this->uri->uri_string().'?set_theme='.($this->h_theme == 'classic' ? 'modern' : 'classic'))?>" class="text-danger">
              <i class="fas fa-lg fa-tint"></i>
            </a> 
            <span class="text-info">Theme: <?=ucwords($this->h_theme)?></span> 
          </span> -->

          <?php echo HMS_NAME . ' ' . HMS_VERSION ?> <?php echo  (ENVIRONMENT === 'development') ?  ' | Page rendered in <strong>{elapsed_time}</strong> seconds. CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
        </div>
        <!-- Default to the left -->
        <strong>
          Copyright &copy; <?= date('Y'); ?> <a href="<?= base_url(); ?>"><?= my_config('site_name'); ?></a>.
        </strong> All rights reserved.
      </footer>

    </div>
    <!-- ./wrapper (Opened at view/classic/header.php) -->


    <!-- REQUIRED SCRIPTS -->
    <!-- Placed at the end of the document so the pages load faster --> 
    <!-- =============================================== -->
    <!-- jQuery -->
    <script src="<?= base_url('backend/modern/plugins/jquery/jquery.js'); ?>"></script>
    <script src="<?= base_url('backend/modern/plugins/jquery/jquery.form.js'); ?>"></script>
    <!-- Croppie -->
    <script src="<?= base_url('backend/js/plugins/croppie.js'); ?>"></script>
    <!-- jQuery UI -->
    <script src="<?= base_url('backend/modern/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('backend/modern/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('backend/modern/dist/js/adminlte.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('backend/modern/plugins/bootbox/bootbox.all.js'); ?>"></script>
    <!-- DateTimePicker -->
    <script src="<?= base_url('backend/modern/plugins/datetimepicker/build/jquery.datetimepicker.full.js'); ?>"></script> 
    <!-- Summernote -->
    <script src="<?= base_url('backend/modern/plugins/jodit/jodit.js'); ?>"></script> 
 
    <script src="<?= base_url('backend/js/base.js?time='.strtotime('NOW')); ?>"></script> 

    <!-- Hotel Management System -->
    <script src="<?= base_url('backend/js/hhms.js?time='.strtotime('NOW')); ?>"></script>  

    <!-- =========================================================== -->  

    <!-- Notifications and more -->
    <?php if ($this->account_data->logged_in()): ?>
      <script>                  
        jQuery(document).ready(function($) {

            $("#get-notifications").click(function(event) {
                var notf_list = $("#notifications__list");
                var preloader = notf_list.next('.preloader').clone().removeClass('d-none');
                notf_list.html(preloader);
                get_notifications();console.log(notf_list.children('.preloader'));
                delay(function(){
                
                },400); 
            });  

           // Jodit
            $('.textarea').each(function () { 
                var editor = new Jodit(this);
            });
        });
      </script>
    <?php endif ?>

    <!-- Datatables -->
    <?php if (isset($use_table) && $use_table): ?>
      <script src="<?php echo base_url(); ?>backend/modern/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="<?php echo base_url(); ?>backend/modern/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
      <!-- page script -->
      <script>
        $(function () {
          $('#datatables_table').DataTable({  
              "scrollX": true,    
            "pageLength" : 10,
            "serverSide": true,
            "order": [[0, "asc" ]],
            "ajax":{
                  url :  '<?= site_url('ajax/datatables/'.$table_method); ?>',
                  type : 'POST'
              },
              rowId: 20
          }) 
        })
      </script>
    <?php endif ?> 
  </body>
</html>  
