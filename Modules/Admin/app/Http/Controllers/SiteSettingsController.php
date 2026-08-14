<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\SiteSettingsRequest;
use App\Models\SiteSettings;

class  SiteSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $siteSettings = SiteSettings::firstOrFail();
        return view('admin::site-settings.edit', compact('siteSettings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiteSettingsRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $siteSettings = SiteSettings::findOrFail(base64_decode($id));
            $siteSettings->update($request->all());

            $siteSettings->uploadMedia($request, 'header_logo');
            $siteSettings->uploadMedia($request, 'header_mobile_logo');
            $siteSettings->uploadMedia($request, 'footer_logo');
            $siteSettings->uploadMedia($request, 'auth_image');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Site Settings updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
