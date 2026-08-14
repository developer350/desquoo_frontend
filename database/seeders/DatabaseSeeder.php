<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PermissionSeeder::class,
            AdminSettingsSeeder::class,
            AppSettingsSeeder::class,
            BannerAndMetaTagSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            HomeCmsSeeder::class,
            BulkOrderCmsSeeder::class,
            OfficeCmsSeeder::class,
            WhyChooseUsSeeder::class,
            PolicySeeder::class,
            SiteSettingsSeeder::class
        ]);
    }
}
