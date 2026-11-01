<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class DefaultCategoryService
{
    /**
     * Default income categories for a new user.
     *
     * @var list<string>
     */
    public const array INCOME_CATEGORIES = [
        'Gaji',
        'Bonus',
        'Penjualan',
        'Bisnis',
        'Investasi',
        'Hadiah',
        'Pemasukan Lainnya',
    ];

    /**
     * Default expense categories for a new user.
     *
     * @var list<string>
     */
    public const array EXPENSE_CATEGORIES = [
        'Makanan dan Minuman',
        'Transportasi',
        'Belanja',
        'Tagihan',
        'Pendidikan',
        'Kesehatan',
        'Hiburan',
        'Kebutuhan Rumah Tangga',
        'Cicilan',
        'Pengeluaran Lainnya',
    ];

    /**
     * Get the complete default category definitions.
     *
     * @return list<array{name: string, type: 'income'|'expense'}>
     */
    public static function definitions(): array
    {
        $definitions = [];

        foreach (self::INCOME_CATEGORIES as $name) {
            $definitions[] = [
                'name' => $name,
                'type' => 'income',
            ];
        }

        foreach (self::EXPENSE_CATEGORIES as $name) {
            $definitions[] = [
                'name' => $name,
                'type' => 'expense',
            ];
        }

        return $definitions;
    }

    /**
     * Create the default income and expense categories for the given user.
     */
    public function createForUser(User $user): void
    {
        foreach (self::definitions() as $definition) {
            Category::query()->firstOrCreate([
                'user_id' => $user->id,
                'name' => $definition['name'],
                'type' => $definition['type'],
            ]);
        }
    }
}
