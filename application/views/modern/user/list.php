    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col-md-6 Important Shortcuts -->
          <div class="col-lg-12"> 
            <a href="<?= site_url('users/generate')?>" class="btn btn-info text-white my-2">
              <i class="fas fa-plus"></i> Generate Accounts
            </a>
            <?= $this->session->flashdata('message') ?? '' ?>
            <div class="card">
              <div class="card-header">
                <strong class="m-0 p-0">
                  <i class="fa fa-users mx-2 text-gray"></i>
                  List Users
                </strong>
                <div class="float-right d-none d-sm-inline text-sm my-0 p-0">
                  <?= $pagination ?>
                </div>
              </div>
              <div class="card-body p-1">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th> Name </th>
                      <th> Email Address</th> 
                      <th> Username </th>
                      <th> Phone Number </th> 
                      <th class="td-actions"> Actions </th>
                      <th> <i class="fa fa-key fa-fw text-danger" data-toggle="tooltip" title="Permision"></i> </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($users): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                      <td>
                        <a href="<?= site_url('profile/view/'.$user->uid) ?>">
                          <?=$user->firstname . ' ' . $user->lastname;?>
                        </a>
                      </td>
                      <td> <?=$user->email;?> </td> 
                      <td> <?=$user->username;?> </td>
                      <td> <?=$user->phone;?> </td> 
                      <td class="td-actions p-0">
                        <?php if ($user->uid !== '0'): ?>
                        <a href="<?= site_url('profile/view/'.$user->uid) ?>" class="btn btn-sm btn-success m-1" data-toggle="tooltip" title="Profile">
                          <i class="btn-icon-only fa fa-user text-white fa-fw"></i>
                        </a>
                        <a href="<?= site_url('users/add/'.$user->uid) ?>" class="btn btn-sm btn-primary m-1" data-toggle="tooltip" title="Edit">
                          <i class="btn-icon-only fa fa-edit text-white fa-fw"></i>
                        </a>
                        <a href="<?= site_url('admin/permissions/assign/'.$user->uid) ?>" class="btn btn-sm btn-warning m-1" data-toggle="tooltip" title="Change Permissions">
                          <i class="btn-icon-only fa fa-key text-danger fa-fw"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="return confirmDelete('<?= site_url('users/delete/'.$user->uid) ?>', 1)" class="btn btn-danger btn-sm m-1" data-toggle="tooltip" title="Delete">
                          <i class="btn-icon-only fa fa-trash text-white fa-fw"></i>
                        </a>
                        <?php endif; ?>
                      </td>
                      <?php $privilege =  $this->privilege_model->get($user->role_id); ?>
                      <td class="text-danger font-weight-bold p-0" data-toggle="tooltip" title="<?=$privilege['title']?> Permision: <?=$privilege['info']?>"> 
                        <?php if ($privilege): ?>
                        <?=substr($privilege['title'], 0, 3);?> 
                        <i class="btn-icon-only fa fa-level-up-alt text-danger fa-fw"></i>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center"><?php alert_notice('No employees available', 'info', TRUE, FALSE) ?></td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
