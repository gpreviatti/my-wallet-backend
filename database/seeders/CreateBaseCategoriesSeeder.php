<?php

namespace Database\Seeders;

use App\Models\Category;
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
        ];
        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
