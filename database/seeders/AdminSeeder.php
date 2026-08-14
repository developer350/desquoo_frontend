<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@desqoo.com'],
            [
                'name' => 'Admin',
                'password' => 'xC:YE275@-JW',
                'email_verified_at' => now(),
            ]
        );
    }
}
