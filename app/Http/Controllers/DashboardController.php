<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the financial summary dashboard.
     */
    public function index(): View
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();
        $currentMonthKey = $now->format('Y-m');

        $totalBalance = (int) Account::sum('balance');

        $monthlyIncome = (int) Transaction::query()
            ->where('type', 'income')
            ->whereDate('date', '>=', $startOfMonth)
            ->whereDate('date', '<=', $endOfMonth)
            ->sum('amount');

        $monthlyExpense = (int) Transaction::query()
            ->where('type', 'expense')
            ->whereDate('date', '>=', $startOfMonth)
            ->whereDate('date', '<=', $endOfMonth)
            ->sum('amount');

        $netCashflow = $monthlyIncome - $monthlyExpense;

        $accounts = Account::query()
            ->withCount('transactions')
            ->orderByDesc('balance')
            ->get();

        $recentTransactions = Transaction::query()
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->limit(7)
            ->get();

        $topExpenseCategories = Transaction::query()
            ->selectRaw('category_id, SUM(amount) as total_amount, COUNT(*) as tx_count')
            ->with('category')
            ->where('type', 'expense')
            ->whereDate('date', '>=', $startOfMonth)
            ->whereDate('date', '<=', $endOfMonth)
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        $currentBudget = Budget::where('month', $currentMonthKey)->first();

        // Data grafik arus kas 6 bulan terakhir (Pengayaan Opsional 9.1)
        $cashflowChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $mStart = $monthDate->copy()->startOfMonth()->toDateString();
            $mEnd = $monthDate->copy()->endOfMonth()->toDateString();

            $inc = (int) Transaction::where('type', 'income')
                ->whereDate('date', '>=', $mStart)
                ->whereDate('date', '<=', $mEnd)
                ->sum('amount');

            $exp = (int) Transaction::where('type', 'expense')
                ->whereDate('date', '>=', $mStart)
                ->whereDate('date', '<=', $mEnd)
                ->sum('amount');

            $cashflowChart[] = [
                'label' => $monthDate->translatedFormat('M Y'),
                'income' => $inc,
                'expense' => $exp,
            ];
        }

        return view('dashboard', [
            'totalBalance' => $totalBalance,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'netCashflow' => $netCashflow,
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'topExpenseCategories' => $topExpenseCategories,
            'currentBudget' => $currentBudget,
            'currentMonthKey' => $currentMonthKey,
            'currentMonthLabel' => $now->translatedFormat('F Y'),
            'cashflowChart' => $cashflowChart,
        ]);
    }
}
