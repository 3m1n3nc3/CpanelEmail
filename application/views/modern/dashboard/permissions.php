<!-- Main content -->
<div class="content">
  <div class="container-fluid"> 

    <?= $this->session->flashdata('message') ?? '' ?> 

    <div class="row">
      	<!-- /.col-md-4 Important Shortcuts -->
      	<div class="col-lg-6 col-md-6"> 

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-key mx-2 text-gray"></i>
                        <?= lang('assign_privilege') ?>
                    </h5> 
                </div>
                <div class="card-body">
                    
                    <?= form_open('admin/permissions/assign')?>
                    <input type="hidden" name="action" value="assign">
                    <div class="row">
                        <div class="form-group col-md-12"> 
                            <label for="id">Employee ID</label>
                            <input type="text" id="id" name="id" class="form-control" value="<?= set_value('id', $action_id) ?>" placeholder="Employee ID" required>
                            <?= form_error('id'); ?> 
                        </div> 

                        <div class="form-group col-md-12">
                            <label for="role_id"><?= lang('privilege') ?></label>
                            <select class="form-control" id="role_id" name="role_id">
                                <option value="0" <?= set_select('role_id', '0'); ?>>Reset</option>
                                <?php foreach ($this->privilege_model->get() AS $option): ?>
                                    <?php $p = $this->privilege_model->get($option['id']); 
                                          if (!verify_permision($p['permissions'], 'super') || has_privilege('super')):
                                    ?>
                                        <option value="<?= $option['id']; ?>" <?= set_select('role_id', $option['id']); ?>>
                                            <?= $option['title']; ?> 
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('role_id'); ?>
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <div class="send-button">
                            <button type="submit" class="btn btn-success btn-md">
                                <?= lang('assign_privilege') ?>
                            </button>
                        </div>
                    </div>
                    <?= form_close()?>
                    
                </div>
            </div>
      	</div>


        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-key mx-2 text-gray"></i>
                        <?= lang('create_privilege') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?=form_open('admin/permissions/create/'.($privileges['id'] ?? ''), ['class'=>'form-row'])?>

                    <input type="hidden" name="action" value="create">
                    <div class="form-group col-md-6">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" placeholder="Title" name="title" value="<?= set_value('title', $privileges['title']) ?>">
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="info">Description</label>
                        <input type="text" class="form-control" id="info" placeholder="Description" name="info" value="<?= set_value('info', $privileges['info']) ?>">
                        <?php echo form_error('info'); ?>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="permissions">Permissions (Comma Separated)</label>
                        <input type="text" class="form-control" id="permissions" placeholder="Permissions" name="permissions" value="<?= set_value('permissions', list_permissions($privileges['permissions'])) ?>">
                        <?php echo form_error('permissions'); ?>
                        <hr>
                        <small class="text-secondary text-md"><?= lang('list_privileges'); ?></small>
                    </div>

                    <?php if (!verify_permision($privileges['permissions'], 'super') || has_privilege('super')): //super ?>
                    <div class="form-group col-12">
                        <div class="send-button">
                            <button type="submit" class="btn btn-primary btn-md"><?=  $action == 'create' && $action_id ? lang('update_privilege') : lang('create_privilege') ?></button>
                            <?php if ($privileges['id']): ?>
                            <a href="<?= site_url('admin/permissions/delete/'.$privileges['id'])?>" class="btn btn-danger btn-md"><i class="fa fa-trash text-white fa-fw"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?= form_close() ?>
                </div>
                <div class="card-footer bg-light">
                    <?php foreach ($this->privilege_model->get() AS $priv): ?>
                    | <a class="font-weight-bold text-info" href="<?= site_url('admin/permissions/create/'.$priv['id']); ?>"> <?= $priv['title']; ?> </a> |
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
      <!-- /.col-md-12 --> 
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
