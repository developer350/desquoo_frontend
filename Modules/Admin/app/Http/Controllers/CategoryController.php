<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\CategoryRequest;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::query()
                ->with('parent:id,name')
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn ($query) => $query->orderByDesc('id')
                );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    $image = '<img src="'.$row->image_value.'" alt="'.$row->image_alt_text_value.'" class="table-thumbnail me-2" style="width:40px;height:40px;object-fit:cover;">';

                    $name = '<span>'.e($row->name).'</span>';

                    return '<div class="d-flex align-items-center">'.$image.$name.'</div>';
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                })
                ->orderColumn('category', function ($query, $order) {
                    $query->orderBy('name', $order);
                })
                ->addColumn('parent', function ($row) {
                    return $row->parent?->name ?? '—';
                })
                ->filterColumn('parent', function ($query, $keyword) {
                    $query->whereHas('parent', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="'.$row->sort_order.'"
                        class="form-control w-50 sort-order numeric-input" data-model="Category"
                        data-id="'.base64_encode($row->id).'" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';

                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="Category"
                            value="'.$row->status.'"
                            data-id="'.base64_encode($row->id).'" name="status"
                            '.$fieldValue.'>
                        <label class="custom-control-label" for="status'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('categories.edit', base64_encode($row->id)).'" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="'.route('categories.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['category', 'sort_order', 'status', 'action'])
                ->toJson();
        }

        return view('admin::category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = Category::max('sort_order') + 1;

        return view('admin::category.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'show_in_menu' => $request->parent_id ? 0 : $request->show_in_menu,
                'show_in_homepage' => $request->parent_id ? 0 : $request->show_in_homepage,
                'is_new' => $request->parent_id ? $request->is_new : 0,
            ]);

            $category = new Category;
            $category->fill($request->all());
            if ($request->parent_id != null) {
                // Check for cycles before saving
                if ($category->wouldCreateCycle($request->parent_id)) {
                    return response()->json([
                        'errors' => [
                            'parent_id' => [
                                0 => 'This parent assignment would create a circular reference or max depth reached',
                            ],
                        ],
                    ], 422);
                }
            }
            $category->save();

            $category->uploadMedia($request, 'image');
            $category->uploadMedia($request, 'home_image');
            $category->uploadMedia($request, 'banner');
            $category->uploadMedia($request, 'banner_mobile');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Category created successfully.']);
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
        $category = Category::findOrFail(base64_decode($id));

        return view('admin::category.form', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'show_in_menu' => $request->parent_id ? 0 : $request->show_in_menu,
                'show_in_homepage' => $request->parent_id ? 0 : $request->show_in_homepage,
                'is_new' => $request->parent_id ? $request->is_new : 0,
            ]);

            $category = Category::findOrFail(base64_decode($id));
            $category->fill($request->all());
            if ($request->parent_id != null) {
                // Check for cycles before saving
                if ($category->wouldCreateCycle($request->parent_id)) {
                    return response()->json([
                        'errors' => [
                            'parent_id' => [
                                0 => 'This parent assignment would create a circular reference or max depth reached',
                            ],
                        ],
                    ], 422);
                }
            }
            $category->save();

            $category->uploadMedia($request, 'image');
            $category->uploadMedia($request, 'home_image');
            $category->uploadMedia($request, 'banner');
            $category->uploadMedia($request, 'banner_mobile');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();

        try {
            $category = Category::findOrFail(base64_decode($id));

            // check if the  category has any children and products
            if (!$request->has('force_delete') && ($category->children()->count() > 0 || $category->products()->count() > 0)) {
                return response()->json([
                    'success' => false,
                    'is_depended' => true,
                    'data' => [
                        'message' => 'Category has children or products. confirm action will delete its children and products.',
                        'force_delete' => false,
                    ],
                ]);
            }

            if ($category->children()->count() == 0 || $category->products()->count() == 0) {
                $category->delete();
            } elseif ($request->has('force_delete')) {
                $category->delete();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Category does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Fetch a paginated list of top-level categories.
     */
    public function getCategories(Request $request)
    {
        $perPage = 30;

        $categories = Category::query()
            ->select('id', 'name')
            ->whereNull('parent_id')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->when($request->filled('exclude_id'), fn ($q) => $q->whereKeyNot($request->exclude_id))
            ->paginate($perPage);

        return response()->json($categories);
    }

    /**
     * Fetch a paginated list of product categories.
     */
    public function getProductCategories(Request $request)
    {
        $perPage = 30;

        $categories = Category::query()
            ->select('id', 'name')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->when($request->filled('exclude_id'), fn ($q) => $q->whereKeyNot($request->exclude_id))
            ->paginate($perPage);

        return response()->json($categories);
    }
}
