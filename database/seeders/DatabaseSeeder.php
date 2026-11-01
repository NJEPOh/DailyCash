<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'demo@dailycash.test'],
            [
                'name' => 'Pengguna Demo',
                'password' => Hash::make('password123'),
            ]
        );

        $this->call([
            CategorySeeder::class,
            AccountSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
