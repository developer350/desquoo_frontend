<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Policy::truncate();

        $policies = [
            [
                'page' => 'Privacy Policy',
                'title' => 'Privacy Policy',
                'meta_title' => 'Privacy Policy',
            ],
            [
                'page' => 'Terms and Conditions',
                'title' => 'Terms and Conditions',
                'meta_title' => 'Terms and Conditions',
            ],
            [
                'page' => 'Returns & Refunds',
                'title' => 'Returns & Refunds',
                'meta_title' => 'Returns & Refunds',
            ]
        ];

        foreach ($policies as $data) {
            Policy::create([
                'page' => $data['page'],
                'title' => $data['title'],
                'meta_title' => $data['meta_title'],
            ]);
        }
    }
}
