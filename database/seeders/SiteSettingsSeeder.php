<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($record = SiteSettings::first()) {
            $record->clearMediaCollection('header_logo');
            $record->clearMediaCollection('footer_logo');
        }
        SiteSettings::truncate();

        $siteSettings = SiteSettings::create([
            'address' => 'Desqoo Enterprises Pvt Ltd, Hub Tower, 1st Floor, Seaport-Airport Road, Thrikakkara, Kakkanad, Kochi, Kerala 682021',
            'email' => 'info@desqoo.com',
            'phone_number' => '+91 81139 90066',
            'whatsapp_number' => null,
            'map_link' => 'https://goo.gl/maps/yourbusinesslocation',
            'working_hours' => 'Mon - Fri | 6am - 3pm',
            'header_logo_alt_text' => null,
            'footer_logo_alt_text' => null,
        ]);

        // $this->copyMediaToModel($siteSettings, 'backend/images/home-about.webp', 'header_logo');
        // $this->copyMediaToModel($siteSettings, 'backend/images/home-about.webp', 'footer_logo');
    }

    /**
     * Copy the media file to the specified collection if the file exists.
     */
    private function copyMediaToModel($model, string $filePath, string $collection): void
    {
        if (File::exists($fullPath = public_path($filePath))) {
            $model->copyMedia($fullPath)->toMediaCollection($collection);
        }
    }
}
