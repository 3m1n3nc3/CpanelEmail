                    <div class="container-fluid">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Photo</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body container-fluid">

                            <div class="row">
                                <div class="col mx-auto mb-3">
                                    <div id="upload-status"></div>
                                </div>
                            </div> 
 
                            <div class="row">
                                <div class="col mx-auto"> 
                                    <div id="uploaded-image-preview"></div>
                                    <div id="croppie-image-preview" style="display: none;"></div>
                                    <div id="croppie-wide-preview" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="container-fluid" id="action-buttons">
                                <div class="row">
                                    <div class="col-md-12"> 
                                    <?php if (($type ?? null) === 'avatar'): ?>

                                        <label for="image-input" class="image-selection-label btn btn-block btn-light font-weight-bold py-5 border border-secondary shadow-sm" id="image-input-label">
                                            <h3 class="font-weight-bold">Choose Photo</h3> 
                                            <i class="fas fa-image fa-fw fa-4x"></i>
                                        </label>
                                        <input type="file" id="image-input" class="image-selection" style="display: none;">

                                    <?php else:?>

                                        <label for="image-input-wide" class="image-selection-label btn btn-block btn-light font-weight-bold py-5 border border-secondary shadow-sm" id="image-input-label">
                                            <h3 class="font-weight-bold">Choose Image </h3>
                                            <i class="fas fa-image fa-fw fa-4x"></i>
                                        </label>
                                        <input type="file" id="image-input-wide" class="image-selection" style="display: none;">

                                    <?php endif;?>

                                        <button class="btn btn-block btn-success btn-upload-image my-1" style="display: none;" onclick="upload_action(0<?= ($endpoint_id ? ', \''.$endpoint_id.'\'' : '').($endpoint ? ', \''.$endpoint.'\'' : '')?>)">
                                            <i class="fas fa-upload"></i>
                                            Upload Photo
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>  
                    <input type="hidden" id="button_identifiers" value=""> 
