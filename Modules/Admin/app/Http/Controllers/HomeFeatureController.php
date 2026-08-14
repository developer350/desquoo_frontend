<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\HomeFeatureRequest;
use App\Models\HomeFeature;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;

class HomeFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = HomeFeature::when(
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
                        class="form-control w-50 sort-order numeric-input" data-model="HomeFeature"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="HomeFeature"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('home-features.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('home-features.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['image', 'sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::home-feature.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = HomeFeature::max('sort_order') + 1;
        return view('admin::home-feature.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HomeFeatureRequest $request)
    {
        DB::beginTransaction();

        try {
            $homeFeature = HomeFeature::create($request->all());

            $homeFeature->uploadMedia($request, 'image');
            $homeFeature->uploadMedia($request, 'image_mobile');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Home Feature created successfully.']);
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
        $homeFeature = HomeFeature::findOrFail(base64_decode($id));
        return view('admin::home-feature.form', compact('homeFeature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomeFeatureRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $homeFeature = HomeFeature::findOrFail(base64_decode($id));
            $homeFeature->update($request->all());

            $homeFeature->uploadMedia($request, 'image');
            $homeFeature->uploadMedia($request, 'image_mobile');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Home Feature updated successfully.']);
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
            $homeFeature = HomeFeature::findOrFail(base64_decode($id));
            $homeFeature->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Home Feature deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Home Feature does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
