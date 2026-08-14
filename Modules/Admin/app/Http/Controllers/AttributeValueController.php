<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductVariantAttribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\AttributeValueRequest;
use Yajra\DataTables\Facades\DataTables;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $attributeId)
    {
        $attribute = Attribute::findOrFail(base64_decode($attributeId));

        if ($request->ajax()) {
            $data = AttributeValue::query()
                ->where('attribute_id', $attribute->id)
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($attribute) {
                    $btn = '<button type="button" class="btn btn-primary btn-sm mr-1 form-modal-btn" data-form-url="' . route('attributes.values.edit', ['attribute' => base64_encode($attribute->id), 'value' => base64_encode($row->id)]) . '" data-redirect-url="' . route('attributes.values.index', base64_encode($attribute->id)) . '" data-target=".attribute-value-modal" data-after-modal-show="initializeCropModal" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></button>';
                    $btn .= '<form action="' . route('attributes.values.destroy', ['attribute' => base64_encode($attribute->id), 'value' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::attribute.value.index', compact('attribute'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($attributeId)
    {
        $attribute = Attribute::findOrFail(base64_decode($attributeId));
        return view('admin::attribute.value.form', compact('attribute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeValueRequest $request, $attributeId)
    {
        DB::beginTransaction();

        try {
            $attributeValue = AttributeValue::create($request->merge(['attribute_id' => base64_decode($attributeId)])->all());

            $attributeValue->uploadMedia($request, 'icon');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute Value created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($attributeId, $valueId)
    {
        $attribute = Attribute::findOrFail(base64_decode($attributeId));
        $attributeValue = AttributeValue::findOrFail(base64_decode($valueId));
        return view('admin::attribute.value.form', compact('attribute', 'attributeValue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeValueRequest $request, $attributeId, $valueId)
    {
        DB::beginTransaction();

        try {
            $attributeValue = AttributeValue::findOrFail(base64_decode($valueId));
            $attributeValue->update($request->all());

            $attributeValue->uploadMedia($request, 'icon');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute Value updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($attributeId, $valueId)
    {
        DB::beginTransaction();

        try {
            $attributeValue = AttributeValue::findOrFail(base64_decode($valueId));
            if (ProductVariantAttribute::where('attribute_value_id', $attributeValue->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'This attribute value is linked to products and cannot be deleted.']);
            }
            $attributeValue->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute Value deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Attribute Value does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Fetch a paginated list of attribute values.
     */
    public function getAttributeValues(Request $request)
    {
        $perPage = 30;

        $attributeValues = AttributeValue::query()
            ->select('id', 'value')
            ->where('attribute_id', $request->attribute_id)
            ->when($request->filled('search'), fn($q) => $q->where('value', 'like', '%' . $request->search . '%'))
            ->paginate($perPage);

        return response()->json($attributeValues);
    }
}
