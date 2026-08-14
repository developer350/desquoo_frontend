<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BulkOrderCms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\BulkOrderCmsRequest;

class BulkOrderCmsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $bulkOrderCms = BulkOrderCms::first();
        return view('admin::bulk-order-cms.edit', compact('bulkOrderCms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BulkOrderCmsRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $bulkOrderCms = BulkOrderCms::findOrFail(base64_decode($id));
            $bulkOrderCms->update($request->all());

            $bulkOrderCms->uploadMedia($request, 'section_five_image');
            $bulkOrderCms->uploadMedia($request, 'banner');
            $bulkOrderCms->uploadMedia($request, 'banner_mobile');
            $bulkOrderCms->uploadMedia($request, 'expert_image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Bulk Order Cms updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
