<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSettings::truncate();

        $adminSettings = [
            [
                'key' => 'app.name',
                'value' => 'DESQOO',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Public application name.'
            ],
            [
                'key' => 'currency.code',
                'value' => 'INR',
                'type' => 'string',
                'group' => 'currency',
                'description' => 'ISO 4217 currency code.'
            ],
            [
                'key' => 'currency.symbol',
                'value' => '₹',
                'type' => 'string',
                'group' => 'currency',
                'description' => 'Currency symbol.'
            ],
            [
                'key' => 'catalog.sku_prefix',
                'value' => 'SKU',
                'type' => 'string',
                'group' => 'catalog',
                'description' => 'Prefix for auto-generated SKUs.'
            ],
            [
                'key' => 'order.prefix',
                'value' => 'ORD',
                'type' => 'string',
                'group' => 'order',
                'description' => 'Prefix for order numbers.'
            ],
            [
                'key' => 'tax.percentage',
                'value' => '18',
                'type' => 'decimal',
                'group' => 'tax',
                'description' => 'Default tax percentage.'
            ],
            [
                'key' => 'shipping.flat_rate',
                'value' => '0',
                'type' => 'decimal',
                'group' => 'shipping',
                'description' => 'Default flat shipping charge.'
            ],
            [
                'key' => 'contact.support_email',
                'value' => 'support@example.com',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Support contact email.'
            ],
        ];

        foreach ($adminSettings as $data) {
            AppSettings::updateOrCreate(
                ['key' => $data['key']],
                [
                    'value'       => $data['value'],
                    'type'        => $data['type'] ?? 'string',
                    'group'       => $data['group'] ?? null,
                    'description' => $data['description'] ?? null,
                ]
            );
        }
    }
}
