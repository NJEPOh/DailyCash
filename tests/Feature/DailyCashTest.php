<?php

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('1. kategori pemasukan berhasil dibuat', function () {
    $response = $this->post(route('categories.store'), [
        'name' => 'Gaji Bulanan',
        'type' => 'income',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Gaji Bulanan',
        'type' => 'income',
    ]);
});

test('2. kategori pengeluaran berhasil dibuat', function () {
    $response = $this->post(route('categories.store'), [
        'name' => 'Tagihan Listrik',
        'type' => 'expense',
    ]);

    $response->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Tagihan Listrik',
        'type' => 'expense',
    ]);
});

test('3. kategori berhasil diperbarui dan dihapus sesuai aturan relasi', function () {
    $category = Category::factory()->income()->create(['name' => 'Bonus Lama']);

    // Update kategori
    $updateResponse = $this->put(route('categories.update', $category), [
        'name' => 'Bonus Tahunan',
        'type' => 'income',
    ]);
    $updateResponse->assertRedirect(route('categories.index'));
    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Bonus Tahunan',
    ]);

    // Jika kategori digunakan transaksi, penghapusan harus ditolak
    $account = Account::factory()->withBalance(500000)->create();
    $transaction = Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $category->id,
        'type' => 'income',
        'amount' => 100000,
    ]);

    $blockedDelete = $this->delete(route('categories.destroy', $category));
    $blockedDelete->assertRedirect(route('categories.index'));
    $blockedDelete->assertSessionHas('error');
    $this->assertDatabaseHas('categories', ['id' => $category->id]);

    // Setelah transaksi dihapus, kategori dapat dihapus
    $transaction->delete();
    $allowedDelete = $this->delete(route('categories.destroy', $category));
    $allowedDelete->assertRedirect(route('categories.index'));
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('4. rekening atau dompet berhasil dibuat dengan saldo awal default 0', function () {
    $response = $this->post(route('accounts.store'), [
        'name' => 'Dompet Utama',
    ]);

    $response->assertRedirect(route('accounts.index'));
    $this->assertDatabaseHas('accounts', [
        'name' => 'Dompet Utama',
        'balance' => 0,
    ]);
});

test('5. transaksi pemasukan berhasil disimpan dan menambah saldo rekening', function () {
    $account = Account::factory()->withBalance(100000)->create(['name' => 'Rekening BCA']);
    $category = Category::factory()->income()->create(['name' => 'Gaji']);

    $response = $this->post(route('transactions.store'), [
        'date' => '2026-10-15',
        'type' => 'income',
        'amount' => 2500000,
        'category_id' => $category->id,
        'account_id' => $account->id,
        'description' => 'Gaji Oktober',
    ]);

    $response->assertRedirect(route('transactions.index'));
    expect($account->fresh()->balance)->toBe(2600000);
    $this->assertDatabaseHas('transactions', [
        'account_id' => $account->id,
        'category_id' => $category->id,
        'type' => 'income',
        'amount' => 2500000,
    ]);
});

test('6. transaksi pengeluaran berhasil disimpan dan mengurangi saldo rekening', function () {
    $account = Account::factory()->withBalance(500000)->create(['name' => 'Dompet Utama']);
    $category = Category::factory()->expense()->create(['name' => 'Makan']);

    $response = $this->post(route('transactions.store'), [
        'date' => '2026-10-16',
        'type' => 'expense',
        'amount' => 125000,
        'category_id' => $category->id,
        'account_id' => $account->id,
        'description' => 'Makan siang',
    ]);

    $response->assertRedirect(route('transactions.index'));
    expect($account->fresh()->balance)->toBe(375000);
});

test('7. pengeluaran melebihi saldo rekening ditolak', function () {
    $account = Account::factory()->withBalance(100000)->create(['name' => 'OVO']);
    $category = Category::factory()->expense()->create(['name' => 'Belanja']);

    $response = $this->from(route('transactions.create'))->post(route('transactions.store'), [
        'date' => '2026-10-16',
        'type' => 'expense',
        'amount' => 250000,
        'category_id' => $category->id,
        'account_id' => $account->id,
        'description' => 'Melebihi saldo',
    ]);

    $response->assertSessionHasErrors('amount');
    expect($account->fresh()->balance)->toBe(100000);
    expect(Transaction::count())->toBe(0);
});

test('8. transaksi dengan kategori yang tidak sesuai tipe ditolak', function () {
    $account = Account::factory()->withBalance(500000)->create();
    $incomeCategory = Category::factory()->income()->create(['name' => 'Gaji']);

    $response = $this->post(route('transactions.store'), [
        'date' => '2026-10-16',
        'type' => 'expense',
        'amount' => 50000,
        'category_id' => $incomeCategory->id,
        'account_id' => $account->id,
        'description' => 'Salah kategori',
    ]);

    $response->assertSessionHasErrors('category_id');
    expect($account->fresh()->balance)->toBe(500000);
    expect(Transaction::count())->toBe(0);
});

test('9. mengubah transaksi memperbarui saldo lama dan saldo baru dengan benar termasuk pindah rekening', function () {
    $accountA = Account::factory()->withBalance(500000)->create(['name' => 'Rekening A']);
    $accountB = Account::factory()->withBalance(300000)->create(['name' => 'Rekening B']);
    $expenseCategory = Category::factory()->expense()->create(['name' => 'Transportasi']);

    // Buat transaksi pengeluaran Rp100.000 di Rekening A (Saldo A menjadi Rp400.000)
    $this->post(route('transactions.store'), [
        'date' => '2026-10-10',
        'type' => 'expense',
        'amount' => 100000,
        'category_id' => $expenseCategory->id,
        'account_id' => $accountA->id,
        'description' => 'Awal di A',
    ]);

    $transaction = Transaction::firstOrFail();
    expect($accountA->fresh()->balance)->toBe(400000);

    // Edit transaksi: pindah ke Rekening B dengan nominal Rp150.000
    // Saldo A harus kembali ke Rp500.000, Saldo B berkurang menjadi Rp150.000
    $updateResponse = $this->put(route('transactions.update', $transaction), [
        'date' => '2026-10-11',
        'type' => 'expense',
        'amount' => 150000,
        'category_id' => $expenseCategory->id,
        'account_id' => $accountB->id,
        'description' => 'Pindah ke B',
    ]);

    $updateResponse->assertRedirect(route('transactions.index'));
    expect($accountA->fresh()->balance)->toBe(500000);
    expect($accountB->fresh()->balance)->toBe(150000);
});

test('10. menghapus transaksi mengembalikan saldo dengan benar', function () {
    $account = Account::factory()->withBalance(500000)->create();
    $expenseCategory = Category::factory()->expense()->create();

    $this->post(route('transactions.store'), [
        'date' => '2026-10-12',
        'type' => 'expense',
        'amount' => 200000,
        'category_id' => $expenseCategory->id,
        'account_id' => $account->id,
    ]);

    $transaction = Transaction::firstOrFail();
    expect($account->fresh()->balance)->toBe(300000);

    $deleteResponse = $this->delete(route('transactions.destroy', $transaction));
    $deleteResponse->assertRedirect(route('transactions.index'));

    expect($account->fresh()->balance)->toBe(500000);
    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
});

test('11. filter transaksi berdasarkan tanggal dan kategori menghasilkan data yang sesuai', function () {
    $account = Account::factory()->withBalance(1000000)->create();
    $catMakan = Category::factory()->expense()->create(['name' => 'Makan Siang']);
    $catListrik = Category::factory()->expense()->create(['name' => 'Token Listrik']);

    Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $catMakan->id,
        'type' => 'expense',
        'amount' => 50000,
        'date' => '2026-10-05',
        'description' => 'Nasi Padang Oktober',
    ]);

    Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $catListrik->id,
        'type' => 'expense',
        'amount' => 200000,
        'date' => '2026-09-01',
        'description' => 'Listrik September',
    ]);

    $response = $this->get(route('transactions.index', [
        'start_date' => '2026-10-01',
        'end_date' => '2026-10-31',
        'category_id' => $catMakan->id,
    ]));

    $response->assertOk();
    $response->assertSee('Nasi Padang Oktober');
    $response->assertDontSee('Listrik September');
});

test('12. detail transaksi menampilkan data berdasarkan ID dan mengembalikan 404 jika tidak ditemukan', function () {
    $account = Account::factory()->withBalance(1000000)->create(['name' => 'Rekening Mandiri']);
    $category = Category::factory()->income()->create(['name' => 'Penjualan Toko']);

    $transaction = Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $category->id,
        'type' => 'income',
        'amount' => 750000,
        'date' => '2026-10-20',
        'description' => 'Order pelanggan #88',
    ]);

    $showResponse = $this->get(route('transactions.show', $transaction));
    $showResponse->assertOk();
    $showResponse->assertSee('Rp750.000');
    $showResponse->assertSee('Penjualan Toko');
    $showResponse->assertSee('Rekening Mandiri');
    $showResponse->assertSee('Order pelanggan #88');

    $notFoundResponse = $this->get('/transactions/999999');
    $notFoundResponse->assertNotFound();
});

test('13. transaksi tanpa bukti gambar berhasil disimpan', function () {
    $account = Account::factory()->withBalance(300000)->create();
    $category = Category::factory()->expense()->create();

    $response = $this->post(route('transactions.store'), [
        'date' => '2026-10-21',
        'type' => 'expense',
        'amount' => 45000,
        'category_id' => $category->id,
        'account_id' => $account->id,
        'description' => 'Parkir dan tol tanpa struk',
    ]);

    $response->assertRedirect(route('transactions.index'));
    $transaction = Transaction::firstOrFail();
    expect($transaction->proof_image)->toBeNull();
});

test('14. transaksi dengan bukti gambar valid berhasil disimpan dan dibersihkan saat dihapus', function () {
    Storage::fake('public');

    $account = Account::factory()->withBalance(500000)->create();
    $category = Category::factory()->expense()->create();
    $file = UploadedFile::fake()->image('struk-belanja.jpg', 600, 800);

    $response = $this->post(route('transactions.store'), [
        'date' => '2026-10-22',
        'type' => 'expense',
        'amount' => 150000,
        'category_id' => $category->id,
        'account_id' => $account->id,
        'description' => 'Belanja dengan struk',
        'proof_image' => $file,
    ]);

    $response->assertRedirect(route('transactions.index'));

    $transaction = Transaction::firstOrFail();
    expect($transaction->proof_image)->not->toBeNull();
    Storage::disk('public')->assertExists($transaction->proof_image);

    $savedPath = $transaction->proof_image;
    $this->delete(route('transactions.destroy', $transaction));
    Storage::disk('public')->assertMissing($savedPath);
});

test('15. dashboard menampilkan metrik saldo, pemasukan, pengeluaran, dan peringatan anggaran', function () {
    $account = Account::factory()->withBalance(2000000)->create(['name' => 'Dompet Kas']);
    $incCat = Category::factory()->income()->create(['name' => 'Gaji']);
    $expCat = Category::factory()->expense()->create(['name' => 'Konsumsi']);

    Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $incCat->id,
        'type' => 'income',
        'amount' => 3000000,
        'date' => now()->format('Y-m-d'),
    ]);

    Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $expCat->id,
        'type' => 'expense',
        'amount' => 1200000,
        'date' => now()->format('Y-m-d'),
    ]);

    Budget::create([
        'month' => now()->format('Y-m'),
        'amount' => 1000000,
    ]);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Rp2.000.000');
    $response->assertSee('Rp3.000.000');
    $response->assertSee('Rp1.200.000');
    $response->assertSee('Peringatan Anggaran Terlampaui!');
});

test('16. halaman laporan periodik dan ekspor csv berfungsi dengan benar', function () {
    $account = Account::factory()->withBalance(1000000)->create(['name' => 'Rekening BCA']);
    $category = Category::factory()->income()->create(['name' => 'Penjualan']);

    Transaction::factory()->create([
        'account_id' => $account->id,
        'category_id' => $category->id,
        'type' => 'income',
        'amount' => 450000,
        'date' => now()->format('Y-m-d'),
        'description' => 'Penjualan tunai',
    ]);

    $reportResponse = $this->get(route('reports.index'));
    $reportResponse->assertOk();
    $reportResponse->assertSee('Rp450.000');

    $csvResponse = $this->get(route('reports.export'));
    $csvResponse->assertOk();
    $csvResponse->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});
