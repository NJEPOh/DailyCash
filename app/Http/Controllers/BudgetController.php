<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;

class BudgetController extends Controller
{
    /**
     * Store or update the monthly expense budget.
     */
    public function store(StoreBudgetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Budget::updateOrCreate(
            ['month' => $validated['month']],
            ['amount' => (int) $validated['amount']]
        );

        return redirect()
            ->back()
            ->with('success', 'Anggaran bulanan berhasil disimpan.');
    }
}
