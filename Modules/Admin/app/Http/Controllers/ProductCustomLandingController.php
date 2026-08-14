<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\ProductCustomLanding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Requests\ProductCustomLandingRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductCustomLandingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductCustomLanding::query()->select('product_custom_landings.id', 'product_custom_landings.title', 'product_custom_landings.slug', 'product_custom_landings.status', 'product_custom_landings.product_id')
                ->with('product:id,name')
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="ProductCustomLanding"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('product-custom-landings.edit', base64_encode($row->id));
                    $deleteUrl = route('product-custom-landings.destroy', base64_encode($row->id));
                    $productivityUrl = route('product-custom-landings.productivity.index', base64_encode($row->id));
                    $mindfulEngineeringUrl = route('product-custom-landings.mindful-engineering.index', base64_encode($row->id));
                    $modelUrl = route('product-custom-landings.model.index', base64_encode($row->id));
                    $faqUrl = route('product-custom-landings.faqs.index', base64_encode($row->id));
                    return '<div class="dropdown">
                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="' . $editUrl . '" class="dropdown-item">
                                            <i class="fas fa-edit me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . $productivityUrl . '" class="dropdown-item">
                                            <i class="fas fa-star me-2"></i> Productivity
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . $mindfulEngineeringUrl . '" class="dropdown-item">
                                            <i class="fas fa-broadcast-tower me-2"></i> Mindful Engineering
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . $modelUrl . '" class="dropdown-item">
                                            <i class="fas fa-square me-2"></i> Models
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . $faqUrl . '" class="dropdown-item">
                                            <i class="fas fa-question-circle me-2"></i> Faqs
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="' . $deleteUrl . '" method="POST" class="m-0">
                                            ' . csrf_field() . method_field('DELETE') . '
                                            <button type="button"
                                                    class="dropdown-item text-danger delete-btn"
                                                    data-delete-message-type="itemWithRelated">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }
        return view('admin::product-custom-landing.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::product-custom-landing.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCustomLandingRequest $request)
    {
        DB::beginTransaction();
        try {
            $productCustomLanding = ProductCustomLanding::create($request->all());
            $productCustomLanding->uploadMedia($request, 'banner_image');
            $productCustomLanding->uploadMedia($request, 'banner_mob_image');
            $productCustomLanding->uploadMedia($request, 'banner_video');
            $productCustomLanding->uploadMedia($request, 'video_thumbnail_image');
            $productCustomLanding->uploadMedia($request, 'video_mobile');
            $productCustomLanding->uploadMedia($request, 'video_thumbnail_image_mobile');
            $productCustomLanding->uploadMedia($request, 'overview_image');
            $productCustomLanding->uploadMedia($request, 'sitting_desk_image');
            $productCustomLanding->uploadMedia($request, 'standing_desk_image');
            $productCustomLanding->uploadMedia($request, 'assembly_image');

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product custom landing created successfully.']);
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
    public function edit($id)
    {
        $productCustomLanding = ProductCustomLanding::findOrFail(base64_decode($id));
        return view('admin::product-custom-landing.form', compact('productCustomLanding'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCustomLandingRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $productCustomLanding = ProductCustomLanding::findOrFail(base64_decode($id));
            $productCustomLanding->update($request->all());

            $productCustomLanding->uploadMedia($request, 'banner_image');
            $productCustomLanding->uploadMedia($request, 'banner_mob_image');
            $productCustomLanding->uploadMedia($request, 'banner_video');
            $productCustomLanding->uploadMedia($request, 'video_thumbnail_image');
            $productCustomLanding->uploadMedia($request, 'video_mobile');
            $productCustomLanding->uploadMedia($request, 'video_thumbnail_image_mobile');
            $productCustomLanding->uploadMedia($request, 'overview_image');
            $productCustomLanding->uploadMedia($request, 'sitting_desk_image');
            $productCustomLanding->uploadMedia($request, 'standing_desk_image');
            $productCustomLanding->uploadMedia($request, 'assembly_image');

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Product custom landing updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productCustomLanding = ProductCustomLanding::findOrFail(base64_decode($id));
        $productCustomLanding->delete();
        return response()->json(['success' => true, 'message' => 'Product custom landing deleted successfully.']);
    }
}
