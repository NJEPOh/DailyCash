<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $typeFilter = $request->query('type');

        $categoriesQuery = Category::query()
            ->withCount('transactions')
            ->withSum('transactions', 'amount')
            ->orderBy('type')
            ->orderBy('name');

        if (in_array($typeFilter, ['income', 'expense'], true)) {
            $categoriesQuery->where('type', $typeFilter);
        }

        $categories = $categoriesQuery->get();
        $incomeCount = Category::where('type', 'income')->count();
        $expenseCount = Category::where('type', 'expense')->count();

        return view('categories.index', [
            'categories' => $categories,
            'typeFilter' => $typeFilter,
            'incomeCount' => $incomeCount,
            'expenseCount' => $expenseCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->loadCount('transactions');
        $totalAmount = (int) $category->transactions()->sum('amount');
        $transactions = $category->transactions()
            ->with('account')
            ->latest('date')
            ->latest('id')
            ->paginate(10);

        return view('categories.show', [
            'category' => $category,
            'totalAmount' => $totalAmount,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $category->loadCount('transactions');

        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->transactions()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh transaksi.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
