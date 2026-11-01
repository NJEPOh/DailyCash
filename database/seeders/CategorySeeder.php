<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DefaultCategoryService $defaultCategoryService): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Pengguna Demo',
            'email' => 'demo@dailycash.test',
        ]);

        $defaultCategoryService->createForUser($user);
    }
}
