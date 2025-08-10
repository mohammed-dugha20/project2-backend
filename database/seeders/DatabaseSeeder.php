<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::create([
        //     'username' => 'Zain Al-din Zidan' ,
        //     'email_verified_at' => now() ,
        //     'email' => 'zain.al-din@gmail.com',
        //     'password' => Hash::make('passwordSuper'),
        //     'remember_token' => Str::random(10),
        //     'user_type' => 'platform_admin'
        // ]);
        $this->call([
            UserSeeder::class,
            StatusSeeder::class,
            PropertyStatusSeeder::class,
            RolesAndPermissionsSeeder::class,
            AdminRolesAndPermissionsSeeder::class,
        ]);
        \App\Models\Location::factory(5)->create();
        \App\Models\FinishingCompany::factory(5)->create();
        \App\Models\FinishingRequest::factory(5)->create();
        \App\Models\FinishingCompanyPortfolio::factory(5)->create();
        \App\Models\FinishingCompanyService::factory(5)->create();
        \App\Models\FinishingCompanyWorkarea::factory(5)->create();
        \App\Models\RealEstateOffice::factory(5)->create();
        \App\Models\Property::factory(5)->create();
        \App\Models\PropertyRequest::factory(5)->create();
        \App\Models\PropertyImage::factory(5)->create();
        \App\Models\ServiceProvider::factory(5)->create();
        \App\Models\ServiceProviderPortfolio::factory(5)->create();
        \App\Models\ServiceProviderRating::factory(5)->create();
        \App\Models\ServiceProviderService::factory(5)->create();
        \App\Models\ServiceProviderWorkarea::factory(5)->create();
        \App\Models\ServiceRequest::factory(5)->create();
        \App\Models\ServiceRequestAttachement::factory(5)->create();
    }
}
