<?php

namespace Database\Seeders;

use App\Models\HomeCms;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomeCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($record = HomeCms::first()) {
            $record->clearMediaCollection('section_one_image');
            $record->clearMediaCollection('section_six_image');
        }
        HomeCms::truncate();

        $homeCms = HomeCms::create([
            'section_one_title' => 'Great ideas don\'t live in boxes.your workspace should be as open as your mind.',
            'section_one_image_alt_text' => 'Great ideas don\'t live in boxes.your workspace should be as open as your mind.',
            'section_two_title' => 'Explore customer favourites',
            'section_three_title' => 'Chosen by Creators',
            'section_three_description' => 'Trusted by professionals at top companies. Desqoo works wherever you do, at home or in the office.',
            'section_four_title' => 'OUR COMMUNITY',
            'section_four_description' => 'Top quality store. 4.9 stars <br>with over 200 reviews',
            'section_five_title' => 'We are business ready. Exclusive savings for businesses, and turnkey execution. <span>Let\'s design your office</span>',
            'section_six_title' => 'We design the future of work',
            'section_six_description' => '<p>At Desqoo, we design intelligent workspaces that grow with you, placing people at the centre of every detail. When design, technology, and purpose come together, the workplace becomes more than a backdrop. It becomes a space where ideas grow, energy flows, and progress begins.</p><p>From height-adjustable desks to chairs that move with your rhythm, Desqoo is made for the thinkers, dreamers, and doers shaping tomorrow.</p><p><strong>We don\'t just make furniture. We create spaces where the future takes shape.</strong></p>',
            'section_six_image_alt_text' => 'We design the future of work',
        ]);

        $this->copyMediaToModel($homeCms, 'backend/images/q1.webp', 'section_one_image');
        $this->copyMediaToModel($homeCms, 'backend/images/future.webp', 'section_six_image');
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
