<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\BannerAndMetaTagRequest;
use App\Models\BannerAndMetaTag;
use Yajra\DataTables\Facades\DataTables;

class BannerAndMetaTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BannerAndMetaTag::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderBy('id')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('page', function ($row) {
                    return $row->page_value;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('banner-and-meta-tags.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['banner', 'action'])
                ->toJson();
        }
        return view('admin::banner-and-meta-tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bannerAndMetaTag = BannerAndMetaTag::findOrFail(base64_decode($id));
        return view('admin::banner-and-meta-tags.edit', compact('bannerAndMetaTag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerAndMetaTagRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $bannerAndMetaTag = bannerAndMetaTag::findOrFail(base64_decode($id));
            $bannerAndMetaTag->update($request->all());

            $bannerAndMetaTag->uploadMedia($request, 'banner');
            $bannerAndMetaTag->uploadMedia($request, 'banner_mobile');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Banner And Meta Tags updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
