<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Helpers\BackendHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ProductCustomLanding;
use App\Models\ProductVariantAttribute;
use Yajra\DataTables\Facades\DataTables;
use Modules\Admin\Http\Requests\AttributeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Attribute::query()
                ->with('values')
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('values', function ($row) {
                    return $row->values->pluck('value')->implode(', ') ?: '—';
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="Attribute"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="Attribute"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('attributes.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="' . route('attributes.values.index', base64_encode($row->id)) . '" class="btn btn-primary btn-sm position-relative mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Values" style="margin-right: 3px;"><i class="fas fa-list label-icon"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">' . $row->values->count() . '</span></a>';
                    $btn .= '<form action="' . route('attributes.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::attribute.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = Attribute::max('sort_order') + 1;
        return view('admin::attribute.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'default_listing_attribute' => $request->has('default_listing_attribute') ? 1 : 0,
                'is_main_attribute' => $request->has('is_main_attribute') ? 1 : 0,
            ]);

            if ($request->is_main_attribute) {
                //check if another attribute is_main_attribute
                $current = Attribute::where('is_main_attribute', 1)->first();
                if ($current) {
                    $productCustomLandings = ProductCustomLanding::count();
                    if ($productCustomLandings > 0) {
                        return response()->json(['success' => false, 'message' => 'Cannot set this attribute as main attribute because there are existing product custom landings. Please remove them first.']);
                    }
                }
            }

            $attribute = Attribute::create($request->except('values'));


            //disable other attribute default_listing_attribute
            if ($attribute->default_listing_attribute) {
                Attribute::where('id', '!=', $attribute->id)
                    ->where('default_listing_attribute', 1)
                    ->update(['default_listing_attribute' => 0]);
            }

            if ($attribute->is_main_attribute) {
                Attribute::where('id', '!=', $attribute->id)
                    ->where('is_main_attribute', 1)
                    ->update(['is_main_attribute' => 0]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attribute = Attribute::findOrFail(base64_decode($id));
        return view('admin::attribute.form', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'default_listing_attribute' => $request->has('default_listing_attribute') ? 1 : 0,
                'is_main_attribute' => $request->has('is_main_attribute') ? 1 : 0,
            ]);

            if ($request->is_main_attribute) {
                //check if another attribute is_main_attribute
                $current = Attribute::where('is_main_attribute', 1)->first();
                if ($current && base64_decode($id) != $current->id) {
                    $productCustomLandings = ProductCustomLanding::count();
                    if ($productCustomLandings > 0) {
                        return response()->json(['success' => false, 'message' => 'Cannot set this attribute as main attribute because there are existing product custom landings. Please remove them first.']);
                    }
                }
            }

            $attribute = Attribute::findOrFail(base64_decode($id));
            $attribute->update($request->all());

            //disable other attribute default_listing_attribute
            if ($attribute->default_listing_attribute) {
                Attribute::where('id', '!=', $attribute->id)
                    ->where('default_listing_attribute', 1)
                    ->update(['default_listing_attribute' => 0]);
            }

            if ($attribute->is_main_attribute) {
                Attribute::where('id', '!=', $attribute->id)
                    ->where('is_main_attribute', 1)
                    ->update(['is_main_attribute' => 0]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $attribute = Attribute::findOrFail(base64_decode($id));
            if (ProductVariantAttribute::where('attribute_id', $attribute->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'This attribute is linked to products and cannot be deleted.']);
            }
            $attribute->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Attribute deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Attribute does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Fetch a paginated list of attributes.
     */
    public function getAttributes(Request $request)
    {
        $perPage = 30;

        $attributes = Attribute::query()
            ->select('id', 'name')
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->whereHas('values')
            ->paginate($perPage);

        return response()->json($attributes);
    }
}
