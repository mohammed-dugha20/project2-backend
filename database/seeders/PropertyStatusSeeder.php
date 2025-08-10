<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'status_name' => 'Under Review',
                'entity_type' => 'property',
                'description' => 'Property is currently under review by the platform administrators',
            ],
            [
                'status_name' => 'Displayed',
                'entity_type' => 'property',
                'description' => 'Property is active and visible to users',
            ],
            [
                'status_name' => 'Suspended',
                'entity_type' => 'property',
                'description' => 'Property listing has been temporarily suspended',
            ],
            [
                'status_name' => 'Archived',
                'entity_type' => 'property',
                'description' => 'Property listing has been archived and is no longer visible',
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insert([
                ...$status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 