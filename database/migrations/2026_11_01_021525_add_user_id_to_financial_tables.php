<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations safely without wiping existing data.
     */
    public function up(): void
    {
        $needsMigration = ! Schema::hasColumn('categories', 'user_id')
            || ! Schema::hasColumn('accounts', 'user_id')
            || ! Schema::hasColumn('transactions', 'user_id')
            || ! Schema::hasColumn('budgets', 'user_id');

        if (! $needsMigration) {
            return;
        }

        $defaultUserId = DB::table('users')->value('id');

        if (! $defaultUserId) {
            $hasExistingData = DB::table('categories')->exists()
                || DB::table('accounts')->exists()
                || DB::table('transactions')->exists();

            if ($hasExistingData) {
                $defaultUserId = DB::table('users')->insertGetId([
                    'name' => 'Pengguna Demo',
                    'email' => 'demo@dailycash.test',
                    'password' => Hash::make('password123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (! Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) use ($defaultUserId) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->default($defaultUserId)
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->index(['user_id', 'type']);
            });

            if ($defaultUserId) {
                DB::table('categories')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            }
        }

        if (! Schema::hasColumn('accounts', 'user_id')) {
            Schema::table('accounts', function (Blueprint $table) use ($defaultUserId) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->default($defaultUserId)
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->index('user_id');
            });

            if ($defaultUserId) {
                DB::table('accounts')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            }
        }

        if (! Schema::hasColumn('transactions', 'user_id')) {
            Schema::table('transactions', function (Blueprint $table) use ($defaultUserId) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->default($defaultUserId)
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->index(['user_id', 'date', 'type']);
            });

            if ($defaultUserId) {
                DB::table('transactions')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            }
        }

        if (! Schema::hasColumn('budgets', 'user_id')) {
            Schema::table('budgets', function (Blueprint $table) use ($defaultUserId) {
                $table->dropUnique(['month']);
                $table->foreignId('user_id')
                    ->nullable()
                    ->default($defaultUserId)
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
                $table->unique(['user_id', 'month']);
            });

            if ($defaultUserId) {
                DB::table('budgets')->whereNull('user_id')->update(['user_id' => $defaultUserId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
