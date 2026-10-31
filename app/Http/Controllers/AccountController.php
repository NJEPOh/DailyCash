<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $accounts = Account::query()
            ->withCount('transactions')
            ->withSum(['transactions as total_income' => fn ($query) => $query->where('type', 'income')], 'amount')
            ->withSum(['transactions as total_expense' => fn ($query) => $query->where('type', 'expense')], 'amount')
            ->orderByDesc('balance')
            ->orderBy('name')
            ->get();

        $totalBalance = (int) $accounts->sum('balance');

        return view('accounts.index', [
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Account::create([
            'name' => $validated['name'],
            'balance' => (int) ($validated['balance'] ?? 0),
        ]);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Rekening/dompet berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account): View
    {
        $account->loadCount('transactions');
        $totalIncome = (int) $account->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = (int) $account->transactions()->where('type', 'expense')->sum('amount');

        $transactions = $account->transactions()
            ->with('category')
            ->latest('date')
            ->latest('id')
            ->paginate(10);

        return view('accounts.show', [
            'account' => $account,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account): View
    {
        return view('accounts.edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $account->update([
            'name' => $request->validated('name'),
        ]);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Nama rekening/dompet berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account): RedirectResponse
    {
        if ($account->transactions()->exists()) {
            return redirect()
                ->route('accounts.index')
                ->with('error', 'Rekening/dompet tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Rekening/dompet berhasil dihapus.');
    }
}
