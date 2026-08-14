<?php

namespace Database\Seeders;

use App\Models\BulkOrderCms;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BulkOrderCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($record = BulkOrderCms::first()) {
            $record->clearMediaCollection('section_five_image');
        }
        BulkOrderCms::truncate();

        $bulkOrderCms = BulkOrderCms::create([
            'section_one_title' => 'Products that teams love',
            'section_two_title' => 'Why are we the best for bulk buy?',
            'section_three_title' => 'Success Stories',
            'section_three_description' => '<p>Since numbers speak louder than words, here are a few highlights from the work we have done until now.</p>',
            'section_four_title' => 'Built for innovators, by innovators',
            'section_four_description' => '<p>The world\'s most innovative companies are powered by Desqoo.</p>',
            'section_five_title' => 'Need help designing your workspace?',
            'section_five_description' => '<p>Check out what we can do to improve your spatial design</p>',
            'section_five_button_title' => 'Create Your Space',
            'section_five_image_alt_text' => 'Need help designing your workspace?',
            'section_six_title' => 'Need more information',
            'section_six_description' => '<p>Tell us what you need and we\'ll get back to you within 24 hours.</p>',
        ]);

        // $this->copyMediaToModel($bulkOrderCms, 'backend/images/home-about.webp', 'section_five_image');
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
