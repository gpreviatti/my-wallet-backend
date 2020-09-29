<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CreateBaseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Salary'],
            ['name' => 'Loans'],
            ['name' => 'Other Earnings'],
            ['name' => 'Investiments'],

            ['name' => 'Food'],
            ['name' => 'Transport'],
            ['name' => 'Services'],
            ['name' => 'Pets'],
            ['name' => 'Health'],
            ['name' => 'Food'],
            ['name' => 'Education'],
            ['name' => 'Travel'],
            ['name' => 'Work'],
            ['name' => 'Gifts'],

            ['name' => 'Industrials'],
            ['name' => 'Financials'],
            ['name' => 'Energy'],
            ['name' => 'Consumer discretionary'],
            ['name' => 'Information technology'],
            ['name' => 'Communication services'],
            ['name' => 'Real estate'],
            ['name' => 'Health care'],
            ['name' => 'Consumer staples'],
            ['name' => 'Utilities'],
        ];
        foreach ($categories as $category) {
            if (!Category::where('name', $category['name'])->first()) {
                Category::firstOrCreate([
                    'uuid' => Str::uuid(),
                    'name' => $category['name']
                ]);
            }
        }
    }
}
