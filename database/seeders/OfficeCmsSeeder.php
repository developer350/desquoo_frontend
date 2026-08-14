<?php

namespace Database\Seeders;

use App\Models\OfficeCms;
use Illuminate\Database\Seeder;

class OfficeCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfficeCms::truncate();

        OfficeCms::create([
            'section_one_title' => 'Why Choose Desqoo?',
            'section_one_description' => '<p>At Desqoo, we understand that a well-designed workspace is more than just furniture, it\'s the foundation of creativity, comfort, and productivity. As a leader in workspace design, we provide:</p>',
            'section_two_title' => 'Your Partners in Progress',
            'section_two_description' => '<p>From emerging startups to global giants, these brands have trusted Desqoo to shape environments that inspire action and creativity.</p>',
            'section_three_title' => 'Get the pro experience',
            'section_four_title' => 'Our Spaces, Your Inspiration',
            'section_five_title' => 'Do you have a question about Desqoo or a specific project?',
            'section_five_description' => '<p>Leave your details for a free consulting session.</p>',
        ]);
    }
}
