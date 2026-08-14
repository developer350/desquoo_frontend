<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = State::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderBy('country_id')->orderBy('name')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->toJson();
        }
        return view('admin::state.index');
    }
}
