<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Banner</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="banner_title" class="form-label">Title*</label>
                        <input type="text" class="form-control" id="banner_title" name="banner_title"
                            value="{{ $bannerData->banner_title ?? '' }}" data-rule-maxlength="191" required>
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="banner_alt_text" class="form-label">Banner Alt Text</label>
                        <input type="text" class="form-control" id="banner_alt_text" name="banner_alt_text"
                            value="{{ $bannerData->banner_alt_text ?? '' }}" data-rule-maxlength="191">
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="banner" class="form-label">Banner*</label>
                        <input type="file" class="form-control filepond-input-crop" name="banner" id="banner"
                            data-accept="image/jpeg, image/png, image/jpg, image/webp" data-width="1920"
                            data-height="1080" required>
                        <div class="text-muted">Dimensions: 1920 x 1080 px</div>
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="banner_mobile" class="form-label">Banner Mobile*</label>
                        <input type="file" class="form-control filepond-input-crop" name="banner_mobile"
                            id="banner_mobile" data-accept="image/jpeg, image/png, image/jpg, image/webp"
                            data-width="390" data-height="576" required>
                        <div class="text-muted">Dimensions: 390 x 576 px</div>
                        <span class="error-block"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
