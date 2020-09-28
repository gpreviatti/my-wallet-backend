<?php

namespace Database\Seeders;

use App\Models\WalletType;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CreateBaseWalletsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $walletsTypes = [
            ['name' => 'Checking Account'],
            ['name' => 'Credit'],
            ['name' => 'Saving'],
            ['name' => 'Investiments'],
            ['name' => 'Stocks'],
        ];
        foreach ($walletsTypes as $walletType) {
            WalletType::firstOrCreate([
                'uuid' => Str::uuid(),
                'name' => $walletType['name']
            ]);
        }
    }
}