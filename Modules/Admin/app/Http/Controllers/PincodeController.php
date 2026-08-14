<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\PincodeRequest;
use Yajra\DataTables\Facades\DataTables;

class PincodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pincode::when(
                BackendHelpers::isOrderColumnZero($request),
                fn ($query) => $query->orderBy('id', 'desc')
            );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';

                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="PclProductivity"
                            value="'.$row->status.'"
                            data-id="'.base64_encode($row->id).'" name="status"
                            '.$fieldValue.'>
                        <label class="custom-control-label" for="status'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('pincodes.edit', base64_encode($row->id)).'" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="'.route('pincodes.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('admin::pincode.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::pincode.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PincodeRequest $request)
    {
        DB::beginTransaction();
        try {
            $pincode = Pincode::create($request->validated());
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pincode created successfully.']);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'An error occurred while creating the pincode. Please try again.']);
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
        $pincode = Pincode::findOrFail(base64_decode($id));

        return view('admin::pincode.form', compact('pincode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PincodeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $pincode = Pincode::findOrFail(base64_decode($id));
            $pincode->update($request->validated());
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pincode updated successfully.']);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'An error occurred while updating the pincode. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pincode = Pincode::findOrFail(base64_decode($id));
            $pincode->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pincode deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the pincode. Please try again.']);
        }
    }
}
