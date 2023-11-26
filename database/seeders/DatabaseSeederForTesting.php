<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Country;
use Illuminate\Database\Seeder;

class DatabaseSeederForTesting extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedCountries();

        $this->seedCategories();
    }

    /**
     * @return void
     */
    public function seedCountries(): void
    {
        $countries =
            [
                [
                    "code" => "gb",
                    "name" => "United Kingdom",
                ],
                [
                    "code" => "us",
                    "name" => "United States",
                ],

            ];
        Country::insert($countries);
    }

    /**
     * @return void
     */
    public function seedCategories(): void
    {
        $categories = [
            [
                "name" => "business",
            ],
            [
                "name" => "entertainment",
            ],
            [
                "name" => "general",
            ],
            [
                "name" => "health",
            ],
            [
                "name" => "science",
            ],
            [
                "name" => "sports",
            ],
            [
                "name" => "technology",
            ],
        ];
        \App\Models\Category::insert($categories);
    }
}
