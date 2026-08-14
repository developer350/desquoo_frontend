<?php

namespace Database\Seeders;

use App\Models\WhyChooseUs;
use Illuminate\Database\Seeder;

class WhyChooseUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhyChooseUs::truncate();

        $whyChooseUs = [
            [
                'title' => 'Lorem ipsum',
                'description' => '<p>Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Lorem ipsum',
                'description' => '<p>Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>',
                'sort_order' => 2,
            ],
            [
                'title' => 'Lorem ipsum',
                'description' => '<p>Lorem ipsum dolor sit amet consectetur adipiscing elit. Dolor sit amet consectetur adipiscing elit quisque faucibus.</p>',
                'sort_order' => 3,
            ],
        ];

        foreach ($whyChooseUs as $data) {
            WhyChooseUs::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'sort_order' => $data['sort_order'],
            ]);
        }
    }
}
