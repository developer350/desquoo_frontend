 <form method="POST"
     action="{{ isset($attributeValue) ? route('attributes.values.update', ['attribute' => base64_encode($attribute->id), 'value' => base64_encode($attributeValue->id)]) : route('attributes.values.store', base64_encode($attribute->id)) }}">
     @csrf
     @isset($attributeValue)
         @method('PUT')
     @endisset
     <div class="modal-header">
         <h5 class="modal-title">{{ isset($attributeValue) ? 'Edit' : 'Create' }} Attribute Value</h5>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body">
         <div class="row">
             <div class="col-md-12">
                 <div class="form-group mb-3">
                     <label for="value" class="form-label">Value*</label>
                     <input type="text" class="form-control" id="value" name="value"
                         value="{{ $attributeValue->value ?? '' }}" data-rule-maxlength="191" required>
                     <span class="error-block"></span>
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-md-12">
                 <div class="form-group mb-3">
                     <label for="icon" class="form-label">Icon</label>
                     <input type="file" class="form-control filepond-input-crop" name="icon" id="icon"
                         data-src="{{ $attributeValue?->icon_value ?? '' }}"
                         data-accept="image/jpeg, image/png, image/jpg, image/webp, image/svg+xml" data-width="53" data-height="53">
                     <div class="text-muted">Dimensions: 53 x 53</div>
                     <span class="error-block"></span>
                 </div>
             </div>
         </div>
     </div>
     <div class="modal-footer">
         <button type="submit" class="btn btn-primary">
             <span>{{ isset($attributeValue) ? 'Update' : 'Create' }}</span>
         </button>
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
             <span>Cancel</span>
         </button>
     </div>
 </form>
