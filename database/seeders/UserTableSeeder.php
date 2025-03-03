<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'user1',
            'email' => 'user1@mail.com',
            'country_code' => '60',
            'mobile_no' => '123456789',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('123456abc'),
            'role' => 'user'
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'amount' => 100000000
        ]);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'country_code' => '60',
            'mobile_no' => '123456789',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('123456abc'),
            'role' => 'admin'
        ]);

        Wallet::create([
            'user_id' => $admin->id,
            'amount' => 100000000
        ]);
    }
}
