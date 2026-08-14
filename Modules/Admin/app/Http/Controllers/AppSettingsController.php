<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\AppSettingsRequest;

class AppSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $appSettings = AppSettings::query()
            ->select('id', 'key', 'value', 'type', 'group', 'description')
            ->get()
            ->keyBy('key');

        return view('admin::app-settings.edit', compact('appSettings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppSettingsRequest $request)
    {
        DB::beginTransaction();

        try {
            $payload = $this->flatten($request->validated());

            foreach ($payload as $key => $value) {
                AppSettings::updateOrCreate(
                    ['key' => $key],
                    ['value' => $this->normalizeValueForKey($key, $value)]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'App settings updated successfully.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    /**
     * Flatten nested request array into dot notation.
     */
    private function flatten(array $arr, string $prefix = ''): array
    {
        $out = [];
        foreach ($arr as $k => $v) {
            $key = $prefix ? "{$prefix}.{$k}" : $k;
            if (is_array($v)) {
                $out += $this->flatten($v, $key);
            } else {
                $out[$key] = $v;
            }
        }
        return $out;
    }

    /**
     * Normalize values depending on key/type.
     */
    private function normalizeValueForKey(string $key, $value): string
    {
        if (collect(['tax.percentage', 'shipping.flat_rate'])->contains($key)) {
            return is_numeric($value) ? (string)$value : '0';
        }

        return (string) $value;
    }
}
