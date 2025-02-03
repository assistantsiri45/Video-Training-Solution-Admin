<div id="modal-choose-image" class="modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body"  id="image-card" >
                    <div id="upload-demo" ></div>
                    <div class="col-md-1" id="upload-demo-i" name="image_viewport"></div>
                    <input  class="form-control"  type="file" placeholder="Choose Image"  id="upload" name="file" @error('file') is-invalid @enderror required >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="crop-btn" class="btn btn-secondary" data-dismiss="modal" >Upload</button>
            </div>
        </div>
    </div>
</div>
