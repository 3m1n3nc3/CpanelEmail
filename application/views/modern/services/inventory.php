    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <a href="<?= site_url('services/add_inventory')?>" class="btn btn-success text-white my-2">
              <i class="fas fa-plus"></i> Add Item
            </a> 

            <?= $this->session->flashdata('message') ?? '' ?>
		      </div>

          <!-- /.col-md-12 Important Shortcuts -->
 
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <strong class="m-0 p-0">
                  <i class="fa fa-store mx-2 text-gray"></i>
                  Inventory
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
        					    <th> Item </th>
        					    <th> Available Quantity </th>
        					    <th> Price (Per Item) </th>
                      <th> Sales Service </th>
        					    <th> Added By </th>
        					    <th> Date Added </th>
                      <th class="td-actions"> Actions </th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
                <?php else: ?>
                    <?php alert_notice('No inventory items available', 'info', TRUE, FALSE) ?>
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
