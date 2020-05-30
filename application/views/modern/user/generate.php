<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('message') ?? '' ?>
        <div class="row">
            <!-- /.col-md-4 Important Shortcuts -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">
                        <i class="fa fa-users mx-2 text-gray"></i>
                        Generate Accounts
                        </h5>
                    </div>
                    <div class="card-body">
                        <?= form_open('users/generate/'.($user['uid'] ?? ''), ['id' => 'generate_user_form', 'data-submit' => 0, 'data-block' => 1])?>
                        <div class="row form-block border-bottom border-danger mb-1" id="1">
                            <div class="col-sm-3 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="email"><?=lang('email_address')?></label>
                                    <input type="text" name="email[]" class="form-control form-control-sm">
                                    <span class="error_message"></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                <label for="remove">&nbsp;</label><br>
                                    <input type="text" name="emailx[]" class="form-control form-control-sm" value="@<?=my_config('primary_server')?>" readonly> 
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="phone"><?=lang('phone')?></label>
                                    <input type="text" name="phone[]" class="form-control form-control-sm" required>
                                    <span class="error_message"></span>
                                </div>
                            </div>  
                            <div class="col-sm-3 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="password"><?=lang('password')?></label>
                                    <input type="text" name="password[]" class="form-control form-control-sm password">
                                    <span class="error_message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="firstname"><?=lang('firstname')?></label>
                                    <input type="text" name="firstname[]" class="form-control form-control-sm" required>
                                    <span class="error_message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="lastname"><?=lang('lastname')?></label>
                                    <input type="text" name="lastname[]" class="form-control form-control-sm" required>
                                    <span class="error_message"></span>
                                </div>
                            </div>   
                            <div class="col-sm-2 col-xs-2 inline-form"> 
                                <label for="remove">&nbsp;</label><br>
                                <button type="button" class="btn btn-sm btn-danger remove_last float-right ml-1" data-last="1"><i class="fa fa-times"></i> Remove</button> 
                            </div>     
                            
                        </div> 
                        <div class="mb-1">    
                            <button type="button" class="btn btn-sm btn-info more_fields float-right mr-1"><i class="fa fa-plus"></i> More Fields</button>
                        </div>   
                        
                        <button class="button btn btn-success submit disabled" disabled>Generate Accounts</button>
                        <?= form_close()?>
                    </div>
                </div>
            </div>
            <!-- /.col-md-12 --> 

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

  
<?php
    $param = array(
        'modal_target' => 'uploadModal',
        'modal_title' => 'Upload Image',
        'modal_size' => 'modal-md',
        'modal_content' => ' 
            <div class="m-0 p-0 text-center" id="upload_loader">
                <div class="loader"><div class="spinner-grow text-warning"></div></div> 
            </div>'
    );
    $this->load->view($this->h_theme.'/modal', $param);
?>
