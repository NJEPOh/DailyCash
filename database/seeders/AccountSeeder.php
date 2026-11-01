<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Pengguna Demo',
            'email' => 'demo@dailycash.test',
        ]);

        $accounts = [
            ['name' => 'Rekening BCA', 'balance' => 0],
            ['name' => 'Dompet Utama', 'balance' => 0],
            ['name' => 'OVO', 'balance' => 0],
            ['name' => 'Tunai', 'balance' => 0],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['user_id' => $user->id, 'name' => $account['name']],
                ['user_id' => $user->id, 'balance' => $account['balance']]
            );
        }
    }
}
