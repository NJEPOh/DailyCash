<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Services\DefaultCategoryService;
use Illuminate\Support\Facades\Hash;

test('1. pengguna yang belum login diarahkan ke halaman login ketika membuka aplikasi', function () {
    $this->get('/')
        ->assertRedirect(route('login'));

    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));

    $this->get(route('transactions.index'))
        ->assertRedirect(route('login'));

    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));

    $this->get(route('accounts.index'))
        ->assertRedirect(route('login'));

    $this->get(route('reports.index'))
        ->assertRedirect(route('login'));
});

test('2. pengguna yang sudah login diarahkan ke dashboard saat membuka url utama atau halaman login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)
        ->get(route('register'))
        ->assertRedirect(route('dashboard'));
});

test('3. registrasi pengguna baru otomatis membuat 7 kategori pemasukan dan 10 kategori pengeluaran default dengan rekening serta transaksi kosong', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Andi Pratama',
        'email' => 'andi@dailycash.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'andi@dailycash.test')->firstOrFail();
    expect($user->name)->toBe('Andi Pratama');
    expect(Hash::check('password123', $user->password))->toBeTrue();

    // Pastikan 7 kategori pemasukan default dan 10 kategori pengeluaran default dibuat untuk user ini
    $incomeCategories = $user->categories()->where('type', 'income')->pluck('name')->all();
    $expenseCategories = $user->categories()->where('type', 'expense')->pluck('name')->all();

    expect($incomeCategories)->toHaveCount(7);
    expect($incomeCategories)->toEqualCanonicalizing(DefaultCategoryService::INCOME_CATEGORIES);

    expect($expenseCategories)->toHaveCount(10);
    expect($expenseCategories)->toEqualCanonicalizing(DefaultCategoryService::EXPENSE_CATEGORIES);

    // Pastikan rekening, transaksi, dan anggaran tetap kosong
    expect($user->accounts()->count())->toBe(0);
    expect($user->transactions()->count())->toBe(0);
    expect($user->budgets()->count())->toBe(0);
});

test('4. email yang sudah digunakan tidak dapat didaftarkan kembali', function () {
    User::factory()->create([
        'email' => 'terdaftar@dailycash.test',
    ]);

    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => 'Pengguna Duplikat',
        'email' => 'terdaftar@dailycash.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('5. login dengan kredensial benar berhasil dan mengarahkan ke dashboard', function () {
    $user = User::factory()->create([
        'email' => 'budi@dailycash.test',
        'password' => 'rahasia123',
    ]);

    $response = $this->post(route('login.attempt'), [
        'email' => 'budi@dailycash.test',
        'password' => 'rahasia123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('6. login dengan kredensial salah ditolak dengan pesan kesalahan bahasa indonesia', function () {
    User::factory()->create([
        'email' => 'budi@dailycash.test',
        'password' => 'rahasia123',
    ]);

    $response = $this->from(route('login'))->post(route('login.attempt'), [
        'email' => 'budi@dailycash.test',
        'password' => 'salahpassword',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('7. logout mengakhiri session pengguna dan mengarahkan ke login', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('8. pengguna baru mendapatkan kondisi awal saldo Rp0, rekening kosong, transaksi kosong, dan 17 kategori default siap pakai', function () {
    // Pengguna lama dengan banyak data
    $existingUser = User::factory()->create();
    $existingAccount = Account::factory()->for($existingUser)->withBalance(15000000)->create(['name' => 'BCA Prioritas']);
    $existingCategory = Category::factory()->for($existingUser)->income()->create(['name' => 'Dividen Saham']);
    Transaction::factory()->for($existingUser)->create([
        'account_id' => $existingAccount->id,
        'category_id' => $existingCategory->id,
        'type' => 'income',
        'amount' => 15000000,
        'date' => now()->format('Y-m-d'),
    ]);

    // Pengguna baru registrasi
    $this->post(route('register.store'), [
        'name' => 'Siti Aminah',
        'email' => 'siti@dailycash.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertOk();
    $dashboardResponse->assertSee('Rp0');
    $dashboardResponse->assertSee('Belum ada rekening. Tambahkan rekening pertama Anda.');
    $dashboardResponse->assertSee('Belum ada transaksi. Mulai catat pemasukan atau pengeluaran.');
    $dashboardResponse->assertSee('17 kategori');
    $dashboardResponse->assertDontSee('BCA Prioritas');
    $dashboardResponse->assertDontSee('Rp15.000.000');
});

test('9. pengguna A tidak dapat melihat, mengubah, atau menghapus kategori milik pengguna B', function () {
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    $categoryA = Category::factory()->for($userA)->income()->create(['name' => 'Gaji User A']);
    $categoryB = Category::factory()->for($userB)->expense()->create(['name' => 'Rahasia User B']);

    $this->actingAs($userA);

    // Index hanya menampilkan milik User A
    $indexResponse = $this->get(route('categories.index'));
    $indexResponse->assertOk();
    $indexResponse->assertSee('Gaji User A');
    $indexResponse->assertDontSee('Rahasia User B');

    // Akses detail, edit, update, delete ke kategori milik User B harus ditolak (403)
    $this->get(route('categories.show', $categoryB))->assertForbidden();
    $this->get(route('categories.edit', $categoryB))->assertForbidden();
    $this->put(route('categories.update', $categoryB), [
        'name' => 'Diubah oleh User A',
        'type' => 'expense',
    ])->assertForbidden();
    $this->delete(route('categories.destroy', $categoryB))->assertForbidden();

    expect($categoryB->fresh()->name)->toBe('Rahasia User B');
});

test('10. pengguna A tidak dapat mengakses, mengubah, atau menghapus rekening milik pengguna B', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $accountA = Account::factory()->for($userA)->withBalance(250000)->create(['name' => 'Dompet User A']);
    $accountB = Account::factory()->for($userB)->withBalance(9900000)->create(['name' => 'Brankas User B']);

    $this->actingAs($userA);

    $indexResponse = $this->get(route('accounts.index'));
    $indexResponse->assertOk();
    $indexResponse->assertSee('Dompet User A');
    $indexResponse->assertDontSee('Brankas User B');

    $this->get(route('accounts.show', $accountB))->assertForbidden();
    $this->get(route('accounts.edit', $accountB))->assertForbidden();
    $this->put(route('accounts.update', $accountB), [
        'name' => 'Dibajak User A',
    ])->assertForbidden();
    $this->delete(route('accounts.destroy', $accountB))->assertForbidden();

    expect($accountB->fresh()->name)->toBe('Brankas User B');
});

test('11. pengguna A tidak dapat melihat, mengubah, atau menghapus transaksi maupun bukti milik pengguna B', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $accountB = Account::factory()->for($userB)->withBalance(1000000)->create(['name' => 'Rekening B']);
    $categoryB = Category::factory()->for($userB)->expense()->create(['name' => 'Belanja B']);
    $transactionB = Transaction::factory()->for($userB)->create([
        'account_id' => $accountB->id,
        'category_id' => $categoryB->id,
        'type' => 'expense',
        'amount' => 300000,
        'description' => 'Transaksi Pribadi User B',
    ]);

    $accountA = Account::factory()->for($userA)->withBalance(500000)->create(['name' => 'Rekening A']);
    $categoryA = Category::factory()->for($userA)->expense()->create(['name' => 'Belanja A']);

    $this->actingAs($userA);

    // Daftar transaksi User A tidak boleh menampilkan transaksi User B
    $listResponse = $this->get(route('transactions.index'));
    $listResponse->assertOk();
    $listResponse->assertDontSee('Transaksi Pribadi User B');

    // Akses URI dinamis transaksi User B ditolak 403
    $this->get(route('transactions.show', $transactionB))->assertForbidden();
    $this->get(route('transactions.edit', $transactionB))->assertForbidden();
    $this->get(route('transactions.proof', $transactionB))->assertForbidden();

    $this->put(route('transactions.update', $transactionB), [
        'date' => '2026-10-20',
        'type' => 'expense',
        'amount' => 50000,
        'category_id' => $categoryA->id,
        'account_id' => $accountA->id,
        'description' => 'Coba ubah transaksi B',
    ])->assertForbidden();

    $this->delete(route('transactions.destroy', $transactionB))->assertForbidden();

    // Pastikan transaksi dan saldo User B tetap utuh
    expect($transactionB->fresh()->amount)->toBe(300000);
    expect($accountB->fresh()->balance)->toBe(1000000);
});

test('12. transaksi tidak dapat dibuat menggunakan kategori atau rekening milik pengguna lain', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $accountA = Account::factory()->for($userA)->withBalance(1000000)->create();
    $categoryA = Category::factory()->for($userA)->expense()->create();

    $accountB = Account::factory()->for($userB)->withBalance(1000000)->create();
    $categoryB = Category::factory()->for($userB)->expense()->create();

    $this->actingAs($userA);

    // Mencoba memakai kategori milik User B
    $resForeignCategory = $this->post(route('transactions.store'), [
        'date' => '2026-10-20',
        'type' => 'expense',
        'amount' => 100000,
        'category_id' => $categoryB->id,
        'account_id' => $accountA->id,
        'description' => 'Pakai kategori B',
    ]);
    $resForeignCategory->assertSessionHasErrors('category_id');

    // Mencoba memakai rekening milik User B
    $resForeignAccount = $this->post(route('transactions.store'), [
        'date' => '2026-10-20',
        'type' => 'expense',
        'amount' => 100000,
        'category_id' => $categoryA->id,
        'account_id' => $accountB->id,
        'description' => 'Pakai rekening B',
    ]);
    $resForeignAccount->assertSessionHasErrors('account_id');

    expect(Transaction::count())->toBe(0);
    expect($accountA->fresh()->balance)->toBe(1000000);
    expect($accountB->fresh()->balance)->toBe(1000000);
});

test('13. perhitungan dashboard dan laporan hanya menggunakan data pengguna yang sedang login', function () {
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    // Data User A: Saldo Rp1.200.000, Pemasukan Rp1.500.000, Pengeluaran Rp300.000
    $accountA = Account::factory()->for($userA)->withBalance(1200000)->create(['name' => 'Dompet A']);
    $incCatA = Category::factory()->for($userA)->income()->create(['name' => 'Gaji A']);
    $expCatA = Category::factory()->for($userA)->expense()->create(['name' => 'Makan A']);

    Transaction::factory()->for($userA)->create([
        'account_id' => $accountA->id,
        'category_id' => $incCatA->id,
        'type' => 'income',
        'amount' => 1500000,
        'date' => now()->format('Y-m-d'),
    ]);
    Transaction::factory()->for($userA)->create([
        'account_id' => $accountA->id,
        'category_id' => $expCatA->id,
        'type' => 'expense',
        'amount' => 300000,
        'date' => now()->format('Y-m-d'),
    ]);

    // Data User B: Saldo Rp8.000.000, Pemasukan Rp9.000.000, Pengeluaran Rp1.000.000
    $accountB = Account::factory()->for($userB)->withBalance(8000000)->create(['name' => 'Dompet B']);
    $incCatB = Category::factory()->for($userB)->income()->create(['name' => 'Proyek B']);
    Transaction::factory()->for($userB)->create([
        'account_id' => $accountB->id,
        'category_id' => $incCatB->id,
        'type' => 'income',
        'amount' => 9000000,
        'date' => now()->format('Y-m-d'),
    ]);

    $this->actingAs($userA);

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertOk();
    $dashboardResponse->assertSee('Rp1.200.000');
    $dashboardResponse->assertSee('Rp1.500.000');
    $dashboardResponse->assertSee('Rp300.000');
    $dashboardResponse->assertDontSee('Rp8.000.000');
    $dashboardResponse->assertDontSee('Rp9.000.000');
    $dashboardResponse->assertDontSee('Dompet B');

    $reportResponse = $this->get(route('reports.index'));
    $reportResponse->assertOk();
    $reportResponse->assertSee('Rp1.500.000');
    $reportResponse->assertDontSee('Rp9.000.000');
});

test('14. seluruh proses CRUD kategori, rekening, dan transaksi berfungsi penuh dengan pembatasan kepemilikan data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // 1. Buat kategori baru (nama yang sama dengan milik user lain tetap diizinkan karena unique per user)
    $otherUser = User::factory()->create();
    Category::factory()->for($otherUser)->income()->create(['name' => 'Gaji Utama']);
    Account::factory()->for($otherUser)->withBalance(500000)->create(['name' => 'Dompet Harian']);

    $this->post(route('categories.store'), [
        'name' => 'Gaji Utama',
        'type' => 'income',
    ])->assertRedirect(route('categories.index'));

    $this->post(route('categories.store'), [
        'name' => 'Belanja Harian',
        'type' => 'expense',
    ])->assertRedirect(route('categories.index'));

    // 2. Buat rekening baru
    $this->post(route('accounts.store'), [
        'name' => 'Dompet Harian',
        'balance' => 0,
    ])->assertRedirect(route('accounts.index'));

    $incomeCategory = $user->categories()->where('type', 'income')->firstOrFail();
    $expenseCategory = $user->categories()->where('type', 'expense')->firstOrFail();
    $account = $user->accounts()->firstOrFail();

    // 3. Buat transaksi pemasukan Rp1.000.000
    $this->post(route('transactions.store'), [
        'date' => now()->format('Y-m-d'),
        'type' => 'income',
        'amount' => 1000000,
        'category_id' => $incomeCategory->id,
        'account_id' => $account->id,
        'description' => 'Gaji Masuk',
    ])->assertRedirect(route('transactions.index'));

    expect($account->fresh()->balance)->toBe(1000000);

    // 4. Buat transaksi pengeluaran Rp250.000
    $this->post(route('transactions.store'), [
        'date' => now()->format('Y-m-d'),
        'type' => 'expense',
        'amount' => 250000,
        'category_id' => $expenseCategory->id,
        'account_id' => $account->id,
        'description' => 'Belanja dapur',
    ])->assertRedirect(route('transactions.index'));

    expect($account->fresh()->balance)->toBe(750000);

    // 5. Update transaksi pengeluaran menjadi Rp350.000
    $expenseTx = $user->transactions()->where('type', 'expense')->firstOrFail();
    $this->put(route('transactions.update', $expenseTx), [
        'date' => now()->format('Y-m-d'),
        'type' => 'expense',
        'amount' => 350000,
        'category_id' => $expenseCategory->id,
        'account_id' => $account->id,
        'description' => 'Belanja dapur revisi',
    ])->assertRedirect(route('transactions.index'));

    expect($account->fresh()->balance)->toBe(650000);

    // 6. Hapus transaksi pengeluaran, saldo kembali ke Rp1.000.000
    $this->delete(route('transactions.destroy', $expenseTx))
        ->assertRedirect(route('transactions.index'));

    expect($account->fresh()->balance)->toBe(1000000);
});

test('15. login, logout, dan membuka dashboard berulang kali tidak menduplikasi kategori default serta kategori default dapat diubah, dihapus, dan ditambah', function () {
    $this->post(route('register.store'), [
        'name' => 'Rina Kusuma',
        'email' => 'rina@dailycash.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $user = User::query()->where('email', 'rina@dailycash.test')->firstOrFail();
    expect($user->categories()->count())->toBe(17);

    // Buka dashboard beberapa kali
    $this->get(route('dashboard'))->assertOk();
    $this->get(route('dashboard'))->assertOk();
    expect($user->categories()->count())->toBe(17);

    // Logout dan login kembali
    $this->post(route('logout'))->assertRedirect(route('login'));
    $this->post(route('login.attempt'), [
        'email' => 'rina@dailycash.test',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));
    $this->get(route('dashboard'))->assertOk();

    // Jumlah kategori tetap 17 (tidak bertambah saat login/dashboard)
    expect($user->categories()->count())->toBe(17);

    // Pengguna dapat mengubah nama kategori default
    $hiburanCat = $user->categories()->where('name', 'Hiburan')->firstOrFail();
    $this->put(route('categories.update', $hiburanCat), [
        'name' => 'Hiburan & Rekreasi',
        'type' => 'expense',
    ])->assertRedirect(route('categories.index'));
    expect($hiburanCat->fresh()->name)->toBe('Hiburan & Rekreasi');

    // Pengguna dapat menghapus kategori default yang belum dipakai transaksi
    $this->delete(route('categories.destroy', $hiburanCat))
        ->assertRedirect(route('categories.index'));
    expect($user->categories()->count())->toBe(16);

    // Pengguna tetap dapat membuat kategori tambahan baru
    $this->post(route('categories.store'), [
        'name' => 'Royalti Buku',
        'type' => 'income',
    ])->assertRedirect(route('categories.index'));
    expect($user->categories()->count())->toBe(17);
});

test('16. kegagalan pembuatan kategori default saat registrasi membatalkan transaksi pembuatan akun (atomic rollback)', function () {
    $this->mock(DefaultCategoryService::class, function ($mock) {
        $mock->shouldReceive('createForUser')
            ->once()
            ->andThrow(new RuntimeException('Simulasi gagal membuat kategori default'));
    });

    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => 'Gagal Registrasi',
        'email' => 'gagal@dailycash.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHas('error');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'gagal@dailycash.test']);
});
