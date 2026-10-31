<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display the periodic financial report.
     */
    public function index(Request $request): View
    {
        [$startDate, $endDate, $periodType, $month, $accountId] = $this->resolvePeriodParameters($request);

        $baseQuery = Transaction::query()
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->when($accountId, fn ($q) => $q->where('account_id', $accountId));

        $totalIncome = (int) (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (int) (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;
        $transactionCount = (int) (clone $baseQuery)->count();

        $incomeByCategory = (clone $baseQuery)
            ->selectRaw('category_id, SUM(amount) as total_amount, COUNT(*) as tx_count')
            ->with('category')
            ->where('type', 'income')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->get();

        $expenseByCategory = (clone $baseQuery)
            ->selectRaw('category_id, SUM(amount) as total_amount, COUNT(*) as tx_count')
            ->with('category')
            ->where('type', 'expense')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->get();

        $dailySummary = (clone $baseQuery)
            ->selectRaw("date, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $transactions = (clone $baseQuery)
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $accounts = Account::orderBy('name')->get();
        $budget = Budget::where('month', substr($startDate, 0, 7))->first();

        return view('reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodType' => $periodType,
            'month' => $month,
            'accountId' => $accountId,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'transactionCount' => $transactionCount,
            'incomeByCategory' => $incomeByCategory,
            'expenseByCategory' => $expenseByCategory,
            'dailySummary' => $dailySummary,
            'transactions' => $transactions,
            'accounts' => $accounts,
            'budget' => $budget,
        ]);
    }

    /**
     * Export the periodic report as CSV (Optional Enrichment 9.4).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        [$startDate, $endDate, , , $accountId] = $this->resolvePeriodParameters($request);

        $transactions = Transaction::query()
            ->with(['account', 'category'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->when($accountId, fn ($q) => $q->where('account_id', $accountId))
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $filename = "laporan-dailycash-{$startDate}-sd-{$endDate}.csv";

        return response()->streamDownload(function () use ($transactions): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['ID', 'Tanggal', 'Tipe', 'Kategori', 'Rekening/Dompet', 'Nominal (Rp)', 'Deskripsi']);

            foreach ($transactions as $tx) {
                fputcsv($handle, [
                    $tx->id,
                    $tx->date->format('Y-m-d'),
                    $tx->type_label,
                    $tx->category?->name ?? '-',
                    $tx->account?->name ?? '-',
                    $tx->amount,
                    $tx->description ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Resolve period filter parameters from the request.
     *
     * @return array{0: string, 1: string, 2: string, 3: string, 4: int|string|null}
     */
    private function resolvePeriodParameters(Request $request): array
    {
        $now = Carbon::now();
        $periodType = (string) $request->query('period_type', 'monthly');
        $month = (string) $request->query('month', $now->format('Y-m'));
        $accountId = $request->query('account_id');

        if ($periodType === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse((string) $request->query('start_date'))->toDateString();
            $endDate = Carbon::parse((string) $request->query('end_date'))->toDateString();
        } elseif ($periodType === 'yearly') {
            $year = substr($month, 0, 4);
            $startDate = Carbon::createFromFormat('Y-m-d', "{$year}-01-01")->startOfYear()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m-d', "{$year}-12-31")->endOfYear()->toDateString();
        } else {
            try {
                $parsedMonth = Carbon::createFromFormat('Y-m', $month);
            } catch (\Throwable) {
                $parsedMonth = $now;
                $month = $now->format('Y-m');
            }
            $startDate = $parsedMonth->copy()->startOfMonth()->toDateString();
            $endDate = $parsedMonth->copy()->endOfMonth()->toDateString();
        }

        return [$startDate, $endDate, $periodType, $month, $accountId];
    }
}
