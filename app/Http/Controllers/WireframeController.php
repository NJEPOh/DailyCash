<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WireframeController extends Controller
{
    /**
     * Display the low-fidelity UI wireframes for all 8 main pages (Section 8.1).
     */
    public function index(): View
    {
        return view('wireframes.index');
    }
}
