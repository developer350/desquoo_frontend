<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function users(Request $request)
    {
        $users = User::select('id', 'name')->when($request->q, function ($query) {
            $query->where('name', 'like', '%'.request('q').'%')
                ->orWhere('email', 'like', '%'.request('q').'%');
        })->paginate(20, ['*'], 'page', $request->page);

        return response()->json($users);
    }
}
