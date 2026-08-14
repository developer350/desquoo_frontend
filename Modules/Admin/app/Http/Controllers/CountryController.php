<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Country::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderBy('name')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->toJson();
        }
        return view('admin::country.index');
    }
}
