<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Meta Tags</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="{{ $titleCol }}">
                    <div class="form-group mb-3">
                        <label for="meta_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="{{ $metaData->meta_title ?? '' }}" data-rule-maxlength="191">
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="{{ $keywordsCol }}">
                    <div class="form-group mb-3">
                        <label for="meta_keywords" class="form-label">Keywords</label>
                        <input id="meta_keywords" name="meta_keywords[]" class="form-control"
                            value="{{ $metaData->meta_keywords ?? '' }}">
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="{{ $descriptionCol }}">
                    <div class="form-group mb-3">
                        <label for="meta_description" class="form-label">Description</label>
                        <textarea name="meta_description" id="meta_description" class="form-control" data-rule-maxlength="3000">{{ $metaData->meta_description ?? '' }}</textarea>
                        <span class="error-block"></span>
                    </div>
                </div>
                <div class="{{ $otherCol }}">
                    <div class="form-group mb-3">
                        <label for="other_meta_tags" class="form-label">Other Meta Tags</label>
                        <textarea name="other_meta_tags" id="other_meta_tags" class="form-control" data-rule-maxlength="5000">{{ $metaData->other_meta_tags ?? '' }}</textarea>
                        <div class="text-muted">
                            e.g., &lt;meta name="author" content="John Doe"&gt; or
                            &lt;script&gt;...&lt;/script&gt;
                        </div>
                        <span class="error-block"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            initializeChoices("#meta_keywords", {
                delimiter: ',',
                editItems: true,
                maxItemCount: 20,
                removeItemButton: true,
                duplicateItemsAllowed: false,
                placeholder: true,
                placeholderValue: 'Enter relevant keywords',
                addItemFilter: (value) => {
                    const text = value.trim();
                    return text.length <= 50;
                },
                customAddItemText: (value) => {
                    const text = value.trim();
                    if (text.length > 50) {
                        return 'Please enter no more than 50 characters.';
                    }
                },
            });
        });
    </script>
@endpush
