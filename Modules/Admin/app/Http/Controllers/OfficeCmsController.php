<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OfficeCms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\OfficeCmsRequest;

class OfficeCmsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $officeCms = OfficeCms::first();
        return view('admin::office-cms.edit', compact('officeCms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfficeCmsRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $officeCms = OfficeCms::findOrFail(base64_decode($id));
            $officeCms->update($request->all());

            $officeCms->uploadMedia($request, 'banner');
            $officeCms->uploadMedia($request, 'banner_mobile');
            $officeCms->uploadMedia($request, 'expert_image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Office Cms updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
