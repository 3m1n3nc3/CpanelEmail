<!-- Main content -->
<section class="content">
	<div class="container-fluid">

    	<?= $this->session->flashdata('message') ?? '' ?>

		<div class="row">
			<div class="col-md-8">

				<?php if (isset($this->uid) && $this->uid == $user['uid']):?>
				<button class="btn btn-block btn-primary text-white mb-2" id="webmail-login-btn">
					<h2 class="mt-2">
						<i class="fa fa-envelope fa-fw fa-lg"></i> 
						Go to Webmail 
			            <span class="text-center preloader d-none"> 
			              <div class="spinner-light text-white spinner-grow" role="status"> 
			                <span class="sr-only">Loading...</span> 
			              </div>
			            </span>
					</h2>
				</button>
				<div class="border border-secondary bg-white mb-2 p-2 rounded text-secondary">
					Please note that you may need your email address (<span class="text-info"><?=$user['email']?></span>) and your password to login to the Webmail console.
				</div>
				<?php endif; ?>

				<div class="card">
					<div class="card-header p-2">
						<ul class="nav nav-pills">
							<li class="nav-item">
								<a class="nav-link<?= !$this->input->post('update_profile') && !$this->session->flashdata('update_profile') && !$this->session->flashdata('change_password') ? ' active' : ''?>" href="#profile" data-toggle="tab">Profile</a>
							</li>
							<?php if (isset($this->uid) && $this->uid == $user['uid']):?>
							<li class="nav-item">
								<a class="nav-link<?= $this->input->post('update_profile') || $this->session->flashdata('update_profile') && !$this->session->flashdata('change_password') ? ' active' : ''?>" href="#settings" data-toggle="tab">Settings</a>
							</li>
							<li class="nav-item">
								<a class="nav-link<?= $this->input->post('change_password') || $this->session->flashdata('change_password') && !$this->input->post('update_profile') && !$this->session->flashdata('update_profile') ? ' active' : ''?>" href="#change_password" data-toggle="tab">Change Password</a>
							</li>
							<?php endif; ?>
						</ul>
					</div><!-- /.card-header -->

					<div class="card-body">
						<div class="tab-content">
							<div class="tab-pane<?= !$this->input->post('update_profile') && !$this->session->flashdata('update_profile') && !$this->session->flashdata('change_password') && !$this->input->post('change_password') ? ' active' : ''?>" id="profile">
								<strong><i class="fas fa-user mr-1"></i> Name</strong>
								<p class="text-muted">
									<?=$user['name']?>
								</p>
								<hr>
								<strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
								<p class="text-muted">
									<?=$user['address']?>
								</p>
								<hr>
								<strong><i class="fas fa-at mr-1"></i> Email</strong>
								<p class="text-muted">
									<?=$user['email'] ?? 'N/A'?>
								</p>
								<hr>
								<strong><i class="fas fa-phone mr-1"></i> Phone</strong>
								<p class="text-muted">
									<?=$user['phone'] ?? 'N/A'?>
								</p> 
								<?php if ($user['password_def'] && ((isset($this->uid) && $this->uid == $user['uid']) || has_privilege('manage-employee'))):?>
								<hr>
								<strong><i class="fas fa-key mr-1"></i> Default Password</strong>
								<p class="text-muted">
									<?=$user['password_def'] ?? 'N/A'?>
								</p> 
								<?php endif; ?>
							</div> 

							<?php if (isset($this->uid) && $this->uid == $user['uid']):?>
							<div class="tab-pane<?= $this->input->post('update_profile') || $this->session->flashdata('update_profile') && !$this->session->flashdata('change_password') ? ' active' : ''?>" id="settings">
								<?= form_open('profile/view/'.$user['uid'])?> 
									<input type="hidden" name="update_profile" value="1">

									<div class="form-group row">
										<label for="firstname" class="col-sm-3 col-form-label">First Name</label>
										<div class="col-sm-9">
											<input type="text" id="firstname" name="firstname" class="form-control" value="<?= set_value('firstname', $user['firstname']) ?>" required>
	                  						<?= form_error('firstname'); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="lastname" class="col-sm-3 col-form-label">Last Name</label>
										<div class="col-sm-9">
											<input type="text" id="lastname" name="lastname" class="form-control" value="<?= set_value('lastname', $user['lastname']) ?>" required>
	                  						<?= form_error('lastname'); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-3 col-form-label">Email</label>
										<div class="col-sm-9">
					                    	<input type="text" id="email" class="form-control disabled" value="<?= set_value('email', $user['email']) ?>" disabled readonly>
					                  		<?= form_error('email'); ?>
										</div>
									</div>  
									<div class="form-group row">
										<label for="phone" class="col-sm-3 col-form-label">Phone Number</label>
										<div class="col-sm-9">
					                    	<input type="text" id="phone" name="phone" class="form-control" value="<?= set_value('phone', $user['phone']) ?>" required>
					                  		<?= form_error('phone'); ?>
										</div>
									</div>  
									<div class="form-group row">
										<label for="address" class="col-sm-3 col-form-label">Address</label>
										<div class="col-sm-9">
				                            <textarea id="address" name="address" class="form-control"><?= set_value('address', $user['address']) ?></textarea>
				                            <?= form_error('address'); ?>
										</div>
									</div>      
									<div class="form-group row">
										<div class="offset-sm-2 col-sm-10">
											<button class="btn btn-danger">Submit</button>
										</div>
									</div>
								<?=form_close()?>
							</div>
							<div class="tab-pane<?= $this->input->post('change_password') || $this->session->flashdata('change_password') && !$this->input->post('update_profile') && !$this->session->flashdata('update_profile') ? ' active' : ''?>" id="change_password">
								<?= form_open('profile/ch_password/'.$user['uid'])?> 
									<input type="hidden" name="change_password" value="1">

									<div class="form-group row">
										<label for="password" class="col-sm-3 col-form-label">Old Password</label>
										<div class="col-sm-9">
											<input type="password" id="password" name="password" class="form-control" required>
	                  						<?= form_error('password'); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="password_new" class="col-sm-3 col-form-label">New Password</label>
										<div class="col-sm-9">
											<input type="password" id="password_new" name="password_new" class="form-control" required>
	                  						<?= form_error('password_new'); ?>
										</div>
									</div>  
									<div class="form-group row">
										<label for="password_rep" class="col-sm-3 col-form-label">Repeat Password</label>
										<div class="col-sm-9">
											<input type="password" id="password_rep" name="password_rep" class="form-control" required>
	                  						<?= form_error('password_rep'); ?>
										</div>
									</div>     
									<div class="form-group row">
										<div class="offset-sm-2 col-sm-10">
											<button class="btn btn-danger">Submit</button>
										</div>
									</div>
								<?=form_close()?>
							</div>
							<!-- /.tab-pane -->
							<?php endif; ?>

						</div>
					<!-- /.tab-content -->
					</div><!-- /.card-body -->
				</div>
				<!-- /.nav-tabs-custom -->
			</div>
			<!-- /.col -->
			<div class="col-md-4">
				<!-- Profile Image -->
				<div class="card card-primary card-outline">
					<div class="card-body box-profile">
						<div class="text-center">
                            <a href="javascript:void(0)" onclick="modalImageViewer('.profile-user-img')">
								<img class="profile-user-img img-fluid rounded img-thumbnail customer" src="<?= $this->creative_lib->fetch_image($user['image'], 3); ?>" alt="User profile picture">
							</a> 
						</div>
						<h3 class="profile-username text-center"><?=$user['name']?></h3> 

					<?php if (isset($this->uid) && $this->uid == $user['uid']):?>
						<?php if ($user): ?>
	                        
	                        <div id="upload_resize_image" data-endpoint="employee" data-endpoint_id="<?= $user['uid']; ?>" class="d-none"></div>
	                        <button type="button" id="resize_image_button" class="btn btn-success mb-2 btn-block text-white upload_resize_image" data-type="avatar" data-endpoint="employee" data-endpoint_id="<?= $user['uid'];?>" data-toggle="modal" data-target="#uploadModal"><b><?=lang('change_image')?></b></button>

	                    <?php else: ?>

	                        <?php alert_notice(lang('save_to_upload'), 'info', TRUE, 'FLAT') ?>

	                    <?php endif; ?>
	                <?php endif; ?> 
						<a href="<?=site_url('users/add/'.$user['uid'])?>" class="btn btn-primary btn-block text-white"><b>Update User Data</b></a> 
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card --> 
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
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
		<?php//=site_url('profile/webmail/'.$user['uid'])?>

<script>
	window.onload = function () {
		$('#webmail-login-btn').click(function() {
			var _trigger = $(this);
			$(_trigger).attr('disabled', true).find('span.preloader').removeClass('d-none');
			$.post(site_url('profile/webmail/'+<?php echo $user['uid']?>), function(data) {
				$(_trigger).find('span.preloader').addClass('d-none');
				if (data.error) { 
					$(_trigger).removeAttr('disabled');
					$(_trigger).next('div').find('.alert').remove();
					$(_trigger).next('div').prepend(data.error);
				} else {
					if (data.message) {
						$(_trigger).next('div').find('.alert').remove();
						$(_trigger).next('div').prepend(data.message);
					}

				  	$form = $("<form></form>");
					$form.attr({action: data.host, id: 'webmail-login', method: 'post'})
						.append('<input type="hidden" name="session" value="'+data.session+'">');
					$('body').append($form);
					$('form#webmail-login').submit();
				}
			});
		});
	}
</script>
