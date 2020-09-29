<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CreateDefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'uuid' => Str::uuid(),
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('@5XyGDUdBjB(km[d')
            ],
            [
                'id' => 2,
                'uuid' => Str::uuid(),
                'name' => 'test-user-01',
                'email' => 'test01@email.com',
                'password' => bcrypt('=3ez+wnT"W.VuJXS')
            ]
        ];

        foreach ($users as $user) {
            if (!User::find($user['id'])) {
                User::firstOrCreate($user);
            }
        }
    }
}
