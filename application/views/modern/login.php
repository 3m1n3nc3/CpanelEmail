<?php $this->load->view($this->h_theme.'/header_plain', ['body_class' => 'hold-transition login-page'])?>
			
	<div class="login-box">
		<div class="login-logo">
			<img src="<?= $this->creative_lib->fetch_image(my_config('site_logo'), 2); ?>" style="max-height: 50px;">
			<br>
			<a href="<?=site_url()?>"><?=my_config('site_name')?></a>
		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg"><?=lang('admin_login')?></p>
				
				<?= $this->session->flashdata('message') ?> 

				<?= form_open('login');?>
					<div class="input-group mb-3">
						<input type="username" id="username" autocomplete="off" name="username" class="form-control" placeholder="<?=lang('username')?>" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" id="password" autocomplete="off" name="password" class="form-control" placeholder="<?=lang('password')?>" required>
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" id="Field" name="Field">
								<label for="remember">
									<?=lang('remember_me')?>
								</label>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-4">
							<button type="submit" class="btn btn-primary btn-block"><?=lang('signin')?></button>
						</div>
						<!-- /.col -->
					</div>
				<?= form_close();?>

				<p class="mb-1">
					<a href="<?= site_url('forget')?>"><?=lang('forgot_password')?></a>
				</p>
<!-- 				<p class="mb-0">
					<a href="<?= site_url('register')?>" class="text-center"><?=lang('register')?></a>
				</p> -->
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>
	<!-- /.login-box -->

	<!-- jQuery -->
	<script src="<?= base_url('backend/modern/plugins/jquery/jquery.min.js'); ?>"></script>
	<!-- Bootstrap 4 -->
	<script src="<?= base_url('backend/modern/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url('backend/modern/dist/js/adminlte.min.js'); ?>"></script>

	</body>
</html>
