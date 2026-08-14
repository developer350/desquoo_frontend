<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\SuccessStoryCategory;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\SuccessStoryCategoryRequest;
use Yajra\DataTables\Facades\DataTables;

class SuccessStoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuccessStoryCategory::query()
                ->with(['state:id,name', 'city:id,name'])
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
                        class="form-control w-50 sort-order numeric-input" data-model="SuccessStoryCategory"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="SuccessStoryCategory"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('success-story-categories.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="' . route('success-story-categories.success-stories.index', base64_encode($row->id)) . '" class="btn btn-primary btn-sm position-relative mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Success Stories" style="margin-right: 3px;"><i class="fas fa-images label-icon"></i><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">' . $row->successStories->count() . '</span></a>';
                    $btn .= '<form action="' . route('success-story-categories.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemWithRelated" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['image', 'sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::success-story-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::select('id', 'name')->get();
        $sort_order = SuccessStoryCategory::max('sort_order') + 1;
        return view('admin::success-story-category.form', compact('states', 'sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuccessStoryCategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $successStoryCategory = SuccessStoryCategory::create($request->all());

            $successStoryCategory->uploadMedia($request, 'image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Success Story Category created successfully.']);
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
        $successStoryCategory = SuccessStoryCategory::findOrFail(base64_decode($id));
        $states = State::select('id', 'name')->get();
        $cities = City::where('state_id', $successStoryCategory->state_id)->get(['id', 'name']);
        return view('admin::success-story-category.form', compact('successStoryCategory', 'states', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuccessStoryCategoryRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $successStoryCategory = SuccessStoryCategory::findOrFail(base64_decode($id));
            $successStoryCategory->update($request->all());

            $successStoryCategory->uploadMedia($request, 'image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Success Story Category updated successfully.']);
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
            $successStoryCategory = SuccessStoryCategory::findOrFail(base64_decode($id));
            $successStoryCategory->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Success Story Category deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Success Story Category does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
