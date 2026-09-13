<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->with(['products' => fn ($query) => $query->with('productOptions')->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('menu.index', compact('categories'));
    }
}
