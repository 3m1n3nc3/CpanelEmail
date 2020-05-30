<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <?= $this->session->flashdata('message') ?? '' ?>
        <div class="row">
            <!-- /.col-md-4 Important Shortcuts -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="m-0">
                        <i class="fa fa-user mx-2 text-gray"></i>
                        Edit User
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <?= form_open('users/add/'.($user['uid'] ?? ''))?>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="email"><?=lang('email_address')?></label>
                                    <input type="text" id="email" class="form-control" value="<?=$user['email'] ?>" readonly>
                                    <?= form_error('email'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="username"><?=lang('username')?></label>
                                    <input type="text" id="username" name="username" class="form-control" value="<?= set_value_switch('username', $user['username']) ?>" required>
                                    <?= form_error('username'); ?>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="firstname"><?=lang('firstname')?></label>
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="<?= set_value_switch('firstname', $user['firstname']) ?>" required>
                                    <?= form_error('firstname'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="lastname"><?=lang('lastname')?></label>
                                    <input type="text" id="lastname" name="lastname" class="form-control" value="<?= set_value_switch('lastname', $user['lastname']) ?>" required>
                                    <?= form_error('lastname'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="telephone"><?=lang('phone')?></label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="<?= set_value_switch('phone', $user['phone']) ?>" required>
                                    <?= form_error('phone'); ?>
                                </div>
                            </div>    
                            <div class="col-md-12">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="address"><?=lang('address')?></label>
                                    <textarea id="address" name="address" class="form-control"><?= set_value_switch('address', $user['address'] ?? '') ?></textarea>
                                    <?= form_error('address'); ?>
                                </div>
                            </div>   
                            
                        </div>
                        
                        <button class="button btn btn-success">Update</button>
                        <?= form_close()?>
                        
                    </div>
                </div>
            </div>
            <!-- /.col-md-12 -->
          
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title"><?= lang('content_image') ?></h5>
                    </div>
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            <a href="<?//= $link ?>">
                                <img class="profile-user-img img-fluid border-gray" src="<?= $this->creative_lib->fetch_image($user['image'], 3); ?>" alt="...">
                            </a>
                        </div>

                    <?php if ($user): ?>
                        
                        <div id="upload_resize_image" data-endpoint="employee" data-endpoint_id="<?= $user['uid']; ?>" class="d-none"></div>
                        <button type="button" id="resize_image_button" class="btn btn-success mb-2 btn-block text-white upload_resize_image" data-type="avatar" data-endpoint="employee" data-endpoint_id="<?= $user['uid'];?>" data-toggle="modal" data-target="#uploadModal"><b><?=lang('change_image')?></b></button>

                    <?php else: ?>

                        <?php alert_notice(lang('save_to_upload'), 'info', TRUE, 'FLAT') ?>

                    <?php endif; ?>

                    </div>
                </div>
            </div>

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
