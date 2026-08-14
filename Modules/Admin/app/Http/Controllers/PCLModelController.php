<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\PclModel;
use App\Models\ProductCustomLanding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\PCLModelRequest;
use Yajra\DataTables\Facades\DataTables;

class PCLModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $product_custom_landing)
    {
        if ($request->ajax()) {
            $data = PclModel::where('product_custom_landing_id', base64_decode($product_custom_landing))
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="PclModel"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="PclModel"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('product-custom-landings.model.edit', ['product_custom_landing' => base64_encode($row->product_custom_landing_id), 'model' => base64_encode($row->id)]) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('product-custom-landings.model.destroy', ['product_custom_landing' => base64_encode($row->product_custom_landing_id), 'model' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action', 'sort_order', 'status'])
                ->toJson();
        }
        $productCustomLanding = ProductCustomLanding::select('id', 'title')->findOrFail(base64_decode($product_custom_landing));
        return view('admin::product-custom-landing.model.index', compact('productCustomLanding'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($product_custom_landing)
    {
        $productCustomLanding = ProductCustomLanding::select('id', 'title')->findOrFail(base64_decode($product_custom_landing));
        $sort_order = PclModel::where('product_custom_landing_id', base64_decode($product_custom_landing))->max('sort_order') + 1;
        return view('admin::product-custom-landing.model.form', compact('sort_order', 'productCustomLanding'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PclModelRequest $request, $product_custom_landing)
    {
        DB::beginTransaction();
        try {
            $request->merge(['product_custom_landing_id' => base64_decode($product_custom_landing)]);
            $model = PclModel::create($request->all());
            $model->uploadMedia($request, 'image');
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Model created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product_custom_landing, $model)
    {
        $productCustomLanding = ProductCustomLanding::select('id', 'title')->findOrFail(base64_decode($product_custom_landing));
        $model = PclModel::findOrFail(base64_decode($model));
        return view('admin::product-custom-landing.model.form', compact('model', 'productCustomLanding'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PCLModelRequest $request, $product_custom_landing, $model)
    {
        DB::beginTransaction();
        try {
            $model = PclModel::findOrFail(base64_decode($model));
            $model->update($request->all());
            $model->uploadMedia($request, 'image');
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Model updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_custom_landing, $model)
    {
        $model = PclModel::findOrFail(base64_decode($model));
        $model->delete();
        return response()->json(['success' => true, 'message' => 'Model deleted successfully.']);
    }
}
