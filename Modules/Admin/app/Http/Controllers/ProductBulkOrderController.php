<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBulkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\ProductBulkOrderRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductBulkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $product)
    {
        $product = Product::select('id','name')->findOrFail(base64_decode($product));

        if ($request->ajax()) {
            $data = ProductBulkOrder::query()
                ->where('product_id', $product->id)
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="ProductBulkOrder"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="ProductBulkOrder"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) use ($product) {
                    $btn = '<a href="' . route('products.bulk-orders.edit', ['product' => base64_encode($product->id), 'bulk_order' => base64_encode($row->id)]) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('products.bulk-orders.destroy', ['product' => base64_encode($product->id), 'bulk_order' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::product.bulk-order.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($product)
    {
        $product = Product::select('id', 'name')->findOrFail(base64_decode($product));
        $sort_order = ProductBulkOrder::where('product_id', base64_decode($product))->max('sort_order') + 1;
        return view('admin::product.bulk-order.form', compact('sort_order', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductBulkOrderRequest $request, $product)
    {
        DB::beginTransaction();

        try {
            $request->merge(['product_id' => base64_decode($product)]);

            ProductBulkOrder::create($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product bulk order created successfully.']);
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
    public function edit($product, $bulk_order)
    {
        $bulkOrder = ProductBulkOrder::findOrFail(base64_decode($bulk_order));
        $product = Product::select('id', 'name')->findOrFail($bulkOrder->product_id);
        return view('admin::product.bulk-order.form', compact('bulkOrder', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductBulkOrderRequest $request, $product, $bulk_order)
    {
        DB::beginTransaction();

        try {
            $bulkOrder = ProductBulkOrder::findOrFail(base64_decode($bulk_order));
            $bulkOrder->update($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product bulk order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product, $bulk_order)
    {
        DB::beginTransaction();

        try {
            $bulkOrder = ProductBulkOrder::findOrFail(base64_decode($bulk_order));
            $bulkOrder->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product bulk order deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
