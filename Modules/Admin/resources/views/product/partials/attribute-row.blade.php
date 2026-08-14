<tr id="attribute-row-{{ $index }}">
    <td>
        <select class="form-control attribute-id" name="attribute_id[{{ $index }}]" data-index="{{ $index }}"
            data-placeholder="Choose Attribute">
            <option></option>
        </select>
    </td>
    <td>
        <select class="form-control attribute-values-select" name="attribute_values[{{ $index }}][]"
            data-placeholder="Select Options" multiple>
            <option></option>
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-danger btn-sm remove-attribute-row" data-bs-toggle="tooltip"
            data-bs-placement="top" title="Remove">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>
