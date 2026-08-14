<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::when(
                BackendHelpers::isOrderColumnZero($request),
                fn ($query) => $query->orderByDesc('id')
            );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    $encodedId = base64_encode($row->id);

                    return '<div class="form-check form-switch">
                    <input type="checkbox" id="status'.$encodedId.'"
                        class="form-check-input toggle-switch"
                        data-name="Status"
                        data-labels="Enabled;Disabled"
                        data-column="status"
                        data-model="User"
                        value="'.$row->status.'"
                        data-id="'.$encodedId.'"
                        name="status" '.$fieldValue.'>
                    <label class="custom-control-label" for="status'.$encodedId.'">'.$fieldLabel.'</label>
                </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('users.show', base64_encode($row->id)).'" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show" style="margin-right: 3px;"><i class="fas fa-eye"></i></a>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('admin::user.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $id = base64_decode($id);
        $user = User::with(['orders' => function ($q) {
            $q->latest();
        }])->findOrFail($id);
        $totalRevenue = $user->orders()->where('status', 'delivered')->sum('grand_total');

        return view('admin::user.show', compact('user', 'totalRevenue'));
    }
}
