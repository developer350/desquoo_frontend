<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = City::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderBy('state_id')->orderBy('name')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('state_id', function ($row) {
                    return $row->state ? $row->state->name : '';
                })
                ->toJson();
        }
        return view('admin::cities.index');
    }

    /**
     * Fetch a paginated list of cities for a given state.
     */
    public function getCities(Request $request)
    {
        $perPage = 30;

        $cities = City::query()
            ->select('id', 'name')
            ->where('state_id', $request->state_id)
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate($perPage);

        return response()->json($cities);
    }
}
