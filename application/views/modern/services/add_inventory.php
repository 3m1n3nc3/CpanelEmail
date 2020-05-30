		<!-- Main content -->
		<div class="content">
			<div class="container-fluid">
				<div class="row">
					<!-- /.col-md-6 Important Shortcuts -->
					<div class="col-lg-12">

            			<?= $this->session->flashdata('message') ?? '' ?>

						<div class="card">
							<div class="card-header">
								<strong class="m-0 p-0">
								<i class="fa fa-plus mx-2 text-gray"></i>
								<?=$tl?> Inventory Item
								</strong>
								<div class="float-right d-none d-sm-inline text-sm my-0 p-0">
									<?//= $pagination ?>
								</div>
							</div>
							<div class="card-body">
								<?= form_open('services/add_inventory'.($item_id ? '/'.$item_id : '')) ?>
								<div class="row">
									<div class="col-md-6">
										<!-- text input -->
										<div class="form-group">
											<label for="item_name">Item Name</label>
											<input type="text" id="item_name" name="item_name" class="form-control" placeholder="Item Name" value="<?= set_value_switch('item_name', $inventory['item_name']) ?>" required>
											<?= form_error('item_name'); ?>
										</div>
									</div>
					                <div class="col-md-6">
					                  	<!-- text input -->
					                  	<div class="form-group">
					                    	<label for="item_service">Sales Service</label>
					                    	<select id="item_service" name="item_service" class="form-control" required>
												<option>Select a sales service</option>
											<?php foreach ($services as $service): ?>
												<option value="<?= $service->service_name ?>"<?= $service->service_name == $inventory['item_service'] ? ' selected' : ''?>>
													<?= $service->service_name?>
												</option>
											<?php endforeach;?>
					                    	</select>
					                  		<?= form_error('customer'); ?>
					                  	</div>
					                </div> 
									<div class="col-md-6">
										<!-- text input -->
										<div class="form-group">
											<label for="item_quantity">Item Quantity</label>
											<input type="item_quantity" id="item_quantity" name="item_quantity" class="form-control" placeholder="Item Quantity" value="<?= set_value_switch('item_quantity', $inventory['item_quantity']) ?>">
											<?= form_error('item_quantity'); ?>
										</div>
									</div> 
									<div class="col-md-6">
										<!-- text input -->
										<div class="form-group">
											<label for="item_price">Item Price</label>
											<input type="item_price" id="item_price" name="item_price" class="form-control" placeholder="Item Price" value="<?= set_value_switch('item_price', $inventory['item_price']) ?>">
											<?= form_error('item_price'); ?>
										</div>
									</div> 
									<div class="col-md-12">
										<!-- text input -->
										<div class="form-group">
											<label for="price">Service Details</label>
											<textarea type="text" id="item_details" name="item_details" class="form-control" placeholder="Service Details"><?= set_value_switch('item_details', $inventory['item_details']) ?></textarea>
											<?= form_error('item_details'); ?>
										</div>
									</div>
									<button class="btn btn-success">Add</button>
								</div>
								<?= form_close() ?>
							</div>
						</div>
					</div>
				</div>
			<!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content -->
