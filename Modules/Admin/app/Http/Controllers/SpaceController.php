<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Space;
use App\Models\SpaceCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\SpaceRequest;
use Yajra\DataTables\Facades\DataTables;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $spaceCategoryId)
    {
        $spaceCategory = SpaceCategory::findOrFail(base64_decode($spaceCategoryId));

        if ($request->ajax()) {
            $data = Space::query()
                ->where('space_category_id', $spaceCategory->id)
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . $row->image_value . '" alt="' . $row->image_alt_text_value . '" class="table-thumbnail">';
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="Space"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('is_home', function ($row) {
                    $fieldValue = $row->is_home ? 'checked' : '';
                    $fieldLabel = $row->is_home ? 'Yes' : 'No';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="is_home' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Show on Home Page" data-labels="Yes;No" data-column="is_home" data-model="Space"
                            value="' . $row->is_home . '"
                            data-id="' . base64_encode($row->id) . '" name="is_home"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="is_home' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="Space"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) use ($spaceCategory) {
                    $btn = '<a href="' . route('space-categories.spaces.edit', ['space_category' => base64_encode($spaceCategory->id), 'space' => base64_encode($row->id)]) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('space-categories.spaces.destroy', ['space_category' => base64_encode($spaceCategory->id), 'space' => base64_encode($row->id)])  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['image', 'sort_order', 'is_home', 'status', 'action'])
                ->toJson();
        }
        return view('admin::space-category.space.index', compact('spaceCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($spaceCategoryId)
    {
        $spaceCategory = SpaceCategory::findOrFail(base64_decode($spaceCategoryId));
        $sort_order = Space::where('space_category_id', $spaceCategory->id)->max('sort_order') + 1;
        return view('admin::space-category.space.form', compact('spaceCategory', 'sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpaceRequest $request, $spaceCategoryId)
    {
        DB::beginTransaction();

        try {
            $space = Space::create($request->merge(['space_category_id' => base64_decode($spaceCategoryId)])->all());

            $space->uploadMedia($request, 'image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Space created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($spaceCategoryId, $spaceId)
    {
        $spaceCategory = SpaceCategory::findOrFail(base64_decode($spaceCategoryId));
        $space = Space::findOrFail(base64_decode($spaceId));
        return view('admin::space-category.space.form', compact('spaceCategory', 'space'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SpaceRequest $request, $spaceCategoryId, $spaceId)
    {
        DB::beginTransaction();

        try {
            $space = Space::findOrFail(base64_decode($spaceId));
            $space->update($request->all());

            $space->uploadMedia($request, 'image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Space updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($spaceCategoryId, $spaceId)
    {
        DB::beginTransaction();

        try {
            $space = Space::findOrFail(base64_decode($spaceId));
            $space->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Space deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Space does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
