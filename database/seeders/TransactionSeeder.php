<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Transaction::count() > 0) {
            return;
        }

        $bca = Account::where('name', 'Rekening BCA')->firstOrFail();
        $dompet = Account::where('name', 'Dompet Utama')->firstOrFail();
        $ovo = Account::where('name', 'OVO')->firstOrFail();
        $tunai = Account::where('name', 'Tunai')->firstOrFail();

        $catGaji = Category::where('name', 'Gaji')->where('type', 'income')->firstOrFail();
        $catBonus = Category::where('name', 'Bonus')->where('type', 'income')->firstOrFail();
        $catPenjualan = Category::where('name', 'Penjualan')->where('type', 'income')->firstOrFail();
        $catFreelance = Category::where('name', 'Freelance')->where('type', 'income')->firstOrFail();

        $catMakan = Category::where('name', 'Makan & Minum')->where('type', 'expense')->firstOrFail();
        $catTransport = Category::where('name', 'Transportasi')->where('type', 'expense')->firstOrFail();
        $catListrik = Category::where('name', 'Listrik & Air')->where('type', 'expense')->firstOrFail();
        $catInternet = Category::where('name', 'Internet & Pulsa')->where('type', 'expense')->firstOrFail();
        $catBelanja = Category::where('name', 'Belanja Bulanan')->where('type', 'expense')->firstOrFail();
        $catHiburan = Category::where('name', 'Hiburan')->where('type', 'expense')->firstOrFail();

        $now = now();
        $lastMonth = $now->copy()->subMonth();

        $entries = [
            // Bulan lalu
            [
                'account' => $bca,
                'category' => $catGaji,
                'type' => 'income',
                'amount' => 7500000,
                'date' => $lastMonth->copy()->startOfMonth()->addDays(1)->format('Y-m-d'),
                'description' => 'Gaji bulanan periode bulan lalu',
            ],
            [
                'account' => $bca,
                'category' => $catBelanja,
                'type' => 'expense',
                'amount' => 1850000,
                'date' => $lastMonth->copy()->startOfMonth()->addDays(5)->format('Y-m-d'),
                'description' => 'Belanja kebutuhan pokok bulanan',
            ],
            [
                'account' => $dompet,
                'category' => $catPenjualan,
                'type' => 'income',
                'amount' => 2400000,
                'date' => $lastMonth->copy()->startOfMonth()->addDays(10)->format('Y-m-d'),
                'description' => 'Hasil penjualan produk harian',
            ],
            [
                'account' => $dompet,
                'category' => $catMakan,
                'type' => 'expense',
                'amount' => 650000,
                'date' => $lastMonth->copy()->startOfMonth()->addDays(14)->format('Y-m-d'),
                'description' => 'Konsumsi harian dan makan siang tim',
            ],
            // Bulan berjalan
            [
                'account' => $bca,
                'category' => $catGaji,
                'type' => 'income',
                'amount' => 8000000,
                'date' => $now->copy()->startOfMonth()->format('Y-m-d'),
                'description' => 'Gaji pokok bulan berjalan',
            ],
            [
                'account' => $dompet,
                'category' => $catPenjualan,
                'type' => 'income',
                'amount' => 1750000,
                'date' => $now->copy()->startOfMonth()->addDays(2)->format('Y-m-d'),
                'description' => 'Pemasukan penjualan toko minggu pertama',
            ],
            [
                'account' => $ovo,
                'category' => $catBonus,
                'type' => 'income',
                'amount' => 1200000,
                'date' => $now->copy()->startOfMonth()->addDays(3)->format('Y-m-d'),
                'description' => 'Insentif proyek dan cashback',
            ],
            [
                'account' => $tunai,
                'category' => $catFreelance,
                'type' => 'income',
                'amount' => 950000,
                'date' => $now->copy()->startOfMonth()->addDays(4)->format('Y-m-d'),
                'description' => 'Jasa desain dan konsultasi',
            ],
            [
                'account' => $bca,
                'category' => $catListrik,
                'type' => 'expense',
                'amount' => 520000,
                'date' => $now->copy()->startOfMonth()->addDays(5)->format('Y-m-d'),
                'description' => 'Tagihan token listrik dan PDAM',
            ],
            [
                'account' => $bca,
                'category' => $catInternet,
                'type' => 'expense',
                'amount' => 385000,
                'date' => $now->copy()->startOfMonth()->addDays(6)->format('Y-m-d'),
                'description' => 'Langganan WiFi fiber rumah',
            ],
            [
                'account' => $bca,
                'category' => $catBelanja,
                'type' => 'expense',
                'amount' => 1450000,
                'date' => $now->copy()->startOfMonth()->addDays(8)->format('Y-m-d'),
                'description' => 'Belanja supermarket bulanan',
            ],
            [
                'account' => $dompet,
                'category' => $catMakan,
                'type' => 'expense',
                'amount' => 420000,
                'date' => $now->copy()->startOfMonth()->addDays(9)->format('Y-m-d'),
                'description' => 'Makan harian dan kopi kerja',
            ],
            [
                'account' => $ovo,
                'category' => $catTransport,
                'type' => 'expense',
                'amount' => 275000,
                'date' => $now->copy()->startOfMonth()->addDays(11)->format('Y-m-d'),
                'description' => 'Transportasi ojek online dan parkir',
            ],
            [
                'account' => $tunai,
                'category' => $catHiburan,
                'type' => 'expense',
                'amount' => 180000,
                'date' => $now->copy()->startOfMonth()->addDays(12)->format('Y-m-d'),
                'description' => 'Nonton bioskop akhir pekan',
            ],
        ];

        DB::transaction(function () use ($entries): void {
            foreach ($entries as $entry) {
                /** @var Account $account */
                $account = $entry['account'];
                /** @var Category $category */
                $category = $entry['category'];

                Transaction::create([
                    'account_id' => $account->id,
                    'category_id' => $category->id,
                    'type' => $entry['type'],
                    'amount' => $entry['amount'],
                    'date' => $entry['date'],
                    'description' => $entry['description'],
                ]);

                if ($entry['type'] === 'income') {
                    $account->increment('balance', $entry['amount']);
                } else {
                    $account->decrement('balance', $entry['amount']);
                }
            }
        });

        Budget::updateOrCreate(
            ['month' => $now->format('Y-m')],
            ['amount' => 5000000]
        );
    }
}
