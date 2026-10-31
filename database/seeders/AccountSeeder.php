<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['name' => 'Rekening BCA', 'balance' => 0],
            ['name' => 'Dompet Utama', 'balance' => 0],
            ['name' => 'OVO', 'balance' => 0],
            ['name' => 'Tunai', 'balance' => 0],
        ];

        foreach ($accounts as $account) {
            Account::firstOrCreate(
                ['name' => $account['name']],
                ['balance' => $account['balance']]
            );
        }
    }
}
