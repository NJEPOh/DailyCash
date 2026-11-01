<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Store or update the monthly expense budget for the authenticated user.
     */
    public function store(StoreBudgetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Budget::updateOrCreate(
            [
                'user_id' => (int) Auth::id(),
                'month' => $validated['month'],
            ],
            ['amount' => (int) $validated['amount']]
        );

        return redirect()
            ->back()
            ->with('success', 'Anggaran bulanan berhasil disimpan.');
    }
}
