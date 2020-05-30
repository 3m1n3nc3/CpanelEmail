  <div class="modal fade" id="<?=$modal_target ?? 'primary-modal'?>">
    <div class="modal-dialog modal-dialog-scrollable <?=$modal_size ?? 'modal-xl'?>">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?=$modal_title?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?=$modal_content ?? ''?>
        </div>
        <?php if (isset($modal_btn) && !isset($hide_footer)):?>
        <div class="modal-footer justify-content-between">
          <?php if ($modal_btn !== TRUE):?>
            <?=$modal_btn?>
          <?php else:?>
            <span id="modal_btn_block">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
            </span>
          <?php endif;?>
          <div id="appendiv"></div>
        </div>
        <?php endif;?>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
