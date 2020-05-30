    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <a href="javascript:open_form();" class="btn btn-primary text-white my-2">
                        <i class="fa fa-store"></i> Sell Service
                    </a>

                    <?= $this->session->flashdata('message') ?? '' ?>
                </div>

                <!-- /.col-md-12 Important Shortcuts -->

                <div class="col-lg-12" style="display: none;" id="form">
                    <div class="card">
                        <div class="card-header">
                            <strong class="m-0 p-0">
                            <i class="fa fa-plus mx-2 text-gray"></i>
                            Sell Service
                            </strong>
                            <div class="float-right d-none d-sm-inline text-sm my-0 p-0">
                                <?//= $pagination ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?= form_open('services/sale') ?>
                            <div class="row">
                                <input type="hidden" id="last_item_id" value="">
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label for="customer">Customer</label>
                                        <select id="customer" name="customer" class="form-control" required>
                                            <option value="0">Generic Customer (Unregistered)</option>
                                            <?php foreach ($customers as $customer): ?>
                                            <option value="<?= $customer->customer_id ?>">
                                                <?= $customer->customer_firstname.' '.$customer->customer_lastname?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                        <?= form_error('customer'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label for="customer">Choose Service</label>
                                        <select id="service" name="service" class="form-control" onchange="fetch_stock()" required>
                                            <option>Choose a Service Point</option>
                                            <?php foreach ($services as $service): ?>
                                            <option value="<?= $service->service_name?>">
                                                <?= $service->service_name?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                        <?= form_error('service'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" id="date" name="date" class="form-control" value="<?= set_value('date') ?>" required>
                                        <?= form_error('date'); ?>
                                    </div>
                                </div>
 
                                <div class="col-md-4">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label for="payment">Amount Paid</label>
                                        <input type="text" id="payment" name="payment" class="form-control" value="<?= set_value('payment') ?>" required>
                                        <?= form_error('payment'); ?>
                                        <small class="text-info">
                                            Enter <span class="text-danger">c</span> if customer paid in full
                                            or <span class="text-danger">0</span> if customer paid nothing.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label for="price">Total Price</label>
                                        <input type="price" id="price" name="price" class="form-control" value="<?= set_value('price') ?>" readonly required>
                                        <?= form_error('price'); ?>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stockitems-modal">
                                    Choose Items
                                </button>
                                <?php
                                    $param = array(
                                        'modal_btn'     => TRUE,
                                        'modal_target'  => 'stockitems-modal', 
                                        'modal_title'   => 'Choose Items to Buy',
                                        'modal_content' => '
                                        <div class="col-md-12">
                                            <div class="row p-2" id="stock_item">
                                                <div class="col-12">'.alert_notice('This store has no items on stock', 'error', FALSE, FALSE).'</div>
                                            </div>
                                        </div>'
                                    );
                                    echo $this->load->view($this->h_theme.'/modal', $param, TRUE);
                                ?>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
 
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="m-0 p-0">
                            <i class="fa fa-list mx-2 text-gray"></i>
                            Sales Records
                            </strong>
                            <div class="float-right d-none d-sm-inline text-sm my-0 p-0">
                                <?//= $pagination ?>
                            </div>
                        </div>
                        <div class="card-body p-1">
                            <?php if ($inventory): ?>
                            <table class="table table-striped display" id="datatables_table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th> Order Items </th> 
                                        <th> Order Amount </th>
                                        <th> Customer  </th>
                                        <th> Sold At </th>
                                        <th> Sold By </th>
                                        <th> Paid </th>
                                        <th> Bal. </th>
                                        <th> Order Time </th>
                                        <th class="td-actions"> Actions </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <?php alert_notice('No sales records available', 'info', TRUE, FALSE) ?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
