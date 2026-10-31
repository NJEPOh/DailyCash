<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = [
            'start_date' => $request->query('start_date'),
            'end_date' => $request->query('end_date'),
            'category_id' => $request->query('category_id'),
            'type' => $request->query('type'),
            'account_id' => $request->query('account_id'),
        ];

        $baseQuery = Transaction::query()->filter($filters);

        $filteredIncome = (int) (clone $baseQuery)->where('type', 'income')->sum('amount');
        $filteredExpense = (int) (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $filteredCount = (int) (clone $baseQuery)->count();

        $transactions = (clone $baseQuery)
            ->with(['account', 'category'])
            ->latest('date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('type')->orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();

        return view('transactions.index', [
            'transactions' => $transactions,
            'categories' => $categories,
            'accounts' => $accounts,
            'filters' => $filters,
            'filteredIncome' => $filteredIncome,
            'filteredExpense' => $filteredExpense,
            'filteredCount' => $filteredCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $categories = Category::orderBy('type')->orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();
        $defaultType = in_array($request->query('type'), ['income', 'expense'], true)
            ? $request->query('type')
            : 'expense';

        return view('transactions.create', [
            'categories' => $categories,
            'accounts' => $accounts,
            'defaultType' => $defaultType,
            'defaultAccountId' => $request->query('account_id'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $proofPath = null;

        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('proofs', 'public');
        }

        DB::transaction(function () use ($validated, $proofPath): void {
            $account = Account::lockForUpdate()->findOrFail($validated['account_id']);
            $amount = (int) $validated['amount'];

            Transaction::create([
                'account_id' => $account->id,
                'category_id' => $validated['category_id'],
                'type' => $validated['type'],
                'amount' => $amount,
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'proof_image' => $proofPath,
            ]);

            if ($validated['type'] === 'income') {
                $account->increment('balance', $amount);
            } else {
                $account->decrement('balance', $amount);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan dan saldo rekening telah diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): View
    {
        $transaction->load(['account', 'category']);

        return view('transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        $transaction->load(['account', 'category']);
        $categories = Category::orderBy('type')->orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => $categories,
            'accounts' => $accounts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $transaction): void {
            $oldAccount = Account::lockForUpdate()->findOrFail($transaction->account_id);

            if ($transaction->type === 'income') {
                $oldAccount->decrement('balance', $transaction->amount);
            } else {
                $oldAccount->increment('balance', $transaction->amount);
            }

            $newAccount = Account::lockForUpdate()->findOrFail($validated['account_id']);
            $newAmount = (int) $validated['amount'];

            if ($validated['type'] === 'income') {
                $newAccount->increment('balance', $newAmount);
            } else {
                $newAccount->decrement('balance', $newAmount);
            }

            $proofPath = $transaction->proof_image;

            if ($request->boolean('remove_proof_image') && $proofPath) {
                Storage::disk('public')->delete($proofPath);
                $proofPath = null;
            }

            if ($request->hasFile('proof_image')) {
                if ($proofPath) {
                    Storage::disk('public')->delete($proofPath);
                }
                $proofPath = $request->file('proof_image')->store('proofs', 'public');
            }

            $transaction->update([
                'account_id' => $newAccount->id,
                'category_id' => $validated['category_id'],
                'type' => $validated['type'],
                'amount' => $newAmount,
                'date' => $validated['date'],
                'description' => $validated['description'] ?? null,
                'proof_image' => $proofPath,
            ]);
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui dan saldo rekening telah disesuaikan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $account = Account::findOrFail($transaction->account_id);

        if ($transaction->type === 'income' && $account->balance < $transaction->amount) {
            return redirect()
                ->route('transactions.index')
                ->with('error', 'Transaksi pemasukan ini tidak dapat dihapus karena saldo rekening telah terpakai dan akan menjadi negatif.');
        }

        DB::transaction(function () use ($transaction): void {
            $lockedAccount = Account::lockForUpdate()->findOrFail($transaction->account_id);

            if ($transaction->type === 'income') {
                $lockedAccount->decrement('balance', $transaction->amount);
            } else {
                $lockedAccount->increment('balance', $transaction->amount);
            }

            $oldProof = $transaction->proof_image;
            $transaction->delete();

            if ($oldProof && Storage::disk('public')->exists($oldProof)) {
                Storage::disk('public')->delete($oldProof);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus dan dampak terhadap saldo telah dikembalikan.');
    }

    /**
     * Stream the proof image file from public storage.
     */
    public function proof(Transaction $transaction): StreamedResponse
    {
        abort_if(
            ! $transaction->proof_image || ! Storage::disk('public')->exists($transaction->proof_image),
            404
        );

        return Storage::disk('public')->response($transaction->proof_image);
    }
}
