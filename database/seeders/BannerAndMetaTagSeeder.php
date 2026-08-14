<?php

namespace Database\Seeders;

use App\Models\BannerAndMetaTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BannerAndMetaTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing media collections
        BannerAndMetaTag::query()->each(function ($record) {
            $record->clearMediaCollection('banner');
            $record->clearMediaCollection('banner_mobile');
        });
        BannerAndMetaTag::truncate();

        $bannerAndMetaTags = [
            [
                'page' => 'home',
                'has_banner' => false,
                'meta_title' => 'Home',
            ],
            [
                'page' => 'products',
                'banner_title' => 'Products',
                'has_banner' => true,
                'banner' => 'backend/images/banner.webp',
                'banner_mobile' => 'backend/images/banner-mobile.webp',
                'banner_alt_text' => 'Products',
                'meta_title' => 'Products',
            ],
            [
                'page' => 'office-design',
                'has_banner' => false,
                'meta_title' => 'Office Design',
            ],
            [
                'page' => 'login',
                'has_banner' => false,
                'meta_title' => 'Login',
            ],
            [
                'page' => 'signup',
                'has_banner' => false,
                'meta_title' => 'Signup',
            ],
            [
                'page' => 'otp',
                'has_banner' => false,
                'meta_title' => 'OTP',
            ],
            [
                'page' => 'order-confirmation',
                'has_banner' => false,
                'meta_title' => 'Order Confirmation',
            ],
            [
                'page' => 'order-failed',
                'has_banner' => false,
                'meta_title' => 'Order Failed',
            ],
            [
                'page' => 'blogs',
                'has_banner' => false,
                'meta_title' => 'Blogs',
            ],
            [
                'page' => 'bulk-order',
                'has_banner' => false,
                'meta_title' => 'Bulk Order',
            ],
        ];

        foreach ($bannerAndMetaTags as $data) {
            $bannerAndMetaTag = BannerAndMetaTag::create([
                'page' => $data['page'],
                'has_banner' => $data['has_banner'],
                'banner_title' => $data['banner_title'] ?? null,
                'banner_alt_text' => $data['banner_alt_text'] ?? null,
                'meta_title' => $data['meta_title'],
            ]);

            if ($data['has_banner']) {
                $this->copyMediaToModel($bannerAndMetaTag, $data['banner'], 'banner');
                $this->copyMediaToModel($bannerAndMetaTag, $data['banner_mobile'], 'banner_mobile');
            }
        }
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
