<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportSectionCms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\SupportSectionCmsRequest;

class SupportSectionCmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cms = SupportSectionCms::first();

        return view('admin::support-section-cms.edit', compact('cms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupportSectionCmsRequest $request)
    {
        DB::beginTransaction();
        try {
            $cms = SupportSectionCms::first();
            if ($cms) {
                $cms->update($request->all());
            } else {
                $cms = SupportSectionCms::create($request->all());
            }

            $cms->uploadMedia($request, 'image');
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Support Section Updated Successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);

            return response()->json(['success' => false, 'message' => 'An error occurred while updating the Support Section. Please try again later.']);
        }
    }
}
