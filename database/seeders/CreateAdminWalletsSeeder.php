<?php

namespace Database\Seeders;

use App\Models\Entities\UsersHaveWallets;
use App\Models\Entities\Wallet;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CreateAdminWalletsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wallets = [
            [
                'uuid' => Str::uuid(),
                'wallet_types_id' => '1',
                'name' => 'Itau',
                'description' => 'Conta Principal',
                'current_value' => 300.00,
            ],
            [
                'uuid' => Str::uuid(),
                'wallet_types_id' => '2',
                'name' => 'Nu Credito',
                'description' => 'Meu cartão de crédito',
                'close_date' => 15,
                'due_date' => 24
            ],
            [
                'uuid' => Str::uuid(),
                'wallet_types_id' => '3',
                'name' => 'Poupança',
                'description' => 'Conta poupança',
                'current_value' => 500.00,
            ],
        ];
        foreach ($wallets as $wallet) {
            if (!Wallet::where('name', $wallet['name'])->first()) {
                $newWallet = Wallet::firstOrCreate($wallet);
                UsersHaveWallets::firstOrCreate([
                    'user_id' => 1,
                    'wallet_id' => $newWallet->id
                ]);
            }
        }
    }
}
