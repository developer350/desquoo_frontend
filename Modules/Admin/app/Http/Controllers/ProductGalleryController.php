<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\ProductGalleryRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $productId)
    {
        $product = Product::findOrFail(base64_decode($productId));

        if ($request->ajax()) {
            $data = ProductGallery::query()
                ->where('product_id', $product->id)
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('media_type', function ($row) {
                    return $row->media_type_value;
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="ProductGallery"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="ProductGallery"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) use ($product) {
                    $btn = '<a href="' . route('products.galleries.edit', ['product' => base64_encode($product->id), 'gallery' => base64_encode($row->id)]) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('products.galleries.destroy', ['product' => base64_encode($product->id), 'gallery' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::product.gallery.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail(base64_decode($productId));
        $sort_order = ProductGallery::where('product_id', $product->id)->max('sort_order') + 1;
        return view('admin::product.gallery.form', compact('product', 'sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductGalleryRequest $request, $productId)
    {
        DB::beginTransaction();

        try {
            $productGallery = ProductGallery::create($request->merge(['product_id' => base64_decode($productId)])->all());

            if ($request->input('media_type') === 'image') {
                $productGallery->uploadMedia($request, 'image');
            } elseif ($request->input('media_type') === 'video') {
                $productGallery->uploadMedia($request, 'video_thumbnail_image');
                $productGallery->uploadMedia($request, 'video');
            } elseif ($request->input('media_type') === 'video_url') {
                $productGallery->uploadMedia($request, 'video_url_thumbnail_image');
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Gallery created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($productId, $galleryId)
    {
        $product = Product::findOrFail(base64_decode($productId));
        $productGallery = ProductGallery::findOrFail(base64_decode($galleryId));
        return view('admin::product.gallery.form', compact('product', 'productGallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductGalleryRequest $request, $productId, $galleryId)
    {
        DB::beginTransaction();

        try {
            $productGallery = ProductGallery::findOrFail(base64_decode($galleryId));
            $productGallery->handleMediaChange($request);
            $productGallery->update($request->all());

            if ($request->input('media_type') === 'image') {
                $productGallery->uploadMedia($request, 'image');
            } elseif ($request->input('media_type') === 'video') {
                $productGallery->uploadMedia($request, 'video_thumbnail_image');
                $productGallery->uploadMedia($request, 'video');
            } elseif ($request->input('media_type') === 'video_url') {
                $productGallery->uploadMedia($request, 'video_url_thumbnail_image');
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Gallery updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($productId, $galleryId)
    {
        DB::beginTransaction();

        try {
            $productGallery = ProductGallery::findOrFail(base64_decode($galleryId));
            $productGallery->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Gallery deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Product Gallery does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
