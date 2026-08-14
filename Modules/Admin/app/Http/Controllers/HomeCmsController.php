<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HomeCms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\HomeCmsRequest;

class HomeCmsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $homeCms = HomeCms::first();
        return view('admin::home-cms.edit', compact('homeCms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomeCmsRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $homeCms = HomeCms::findOrFail(base64_decode($id));
            $homeCms->update($request->all());

            $homeCms->uploadMedia($request, 'section_one_image');
            $homeCms->uploadMedia($request, 'section_six_image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Home Cms updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
