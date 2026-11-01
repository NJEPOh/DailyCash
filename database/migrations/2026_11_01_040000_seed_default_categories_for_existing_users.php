<?php

use App\Services\DefaultCategoryService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to ensure existing users have the default categories safely.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('categories')) {
            return;
        }

        $userIds = DB::table('users')->pluck('id');
        if ($userIds->isEmpty()) {
            return;
        }

        $now = now();
        $definitions = DefaultCategoryService::definitions();

        foreach ($userIds as $userId) {
            foreach ($definitions as $definition) {
                $exists = DB::table('categories')
                    ->where('user_id', $userId)
                    ->where('name', $definition['name'])
                    ->where('type', $definition['type'])
                    ->exists();

                if (! $exists) {
                    DB::table('categories')->insert([
                        'user_id' => $userId,
                        'name' => $definition['name'],
                        'type' => $definition['type'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Preserves user categories on rollback to avoid accidental data loss.
    }
};
