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
            SettingsSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
            HomeSectionSeeder::class,
            HowItWorksSeeder::class,
            BenefitSeeder::class,
            FaqSeeder::class,
            PageSeeder::class,
            UserSeeder::class,
        ]);
    }
}
