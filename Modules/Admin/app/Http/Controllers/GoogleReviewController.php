<?php

namespace Modules\Admin\Http\Controllers;

use App\Models\GoogleReview;
use Illuminate\Http\Request;
use App\Helpers\BackendHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Modules\Admin\Http\Requests\GoogleReviewRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GoogleReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = GoogleReview::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderByDesc('id')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    return '<img src="' . $row->avatar_value . '" class="img-thumbnail" style="width: 50px; height: 50px;">';
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="GoogleReview"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="GoogleReview"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('google-reviews.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('google-reviews.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['avatar', 'sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::google-reviews.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = GoogleReview::max('sort_order') + 1;

        return view('admin::google-reviews.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GoogleReviewRequest $request) {
        DB::beginTransaction();

        try {
            $review = GoogleReview::create($request->all());
            $review->uploadMedia($request, 'avatar');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Google Review created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
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
        $review = GoogleReview::findOrFail(base64_decode($id));
        return view('admin::google-reviews.form', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GoogleReviewRequest $request, $id) {
        DB::beginTransaction();

        try {
            $review = GoogleReview::findOrFail(base64_decode($id));
            $review->update($request->all());
            $review->uploadMedia($request, 'avatar');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Google Review updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        DB::beginTransaction();

        try {
            $review = GoogleReview::findOrFail(base64_decode($id));
            $review->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Google Review deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Google Review does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
