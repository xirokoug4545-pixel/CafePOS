<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('Categories.index', compact('categories'));
    }

    public function create()
    {
        return view('Categories.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
        ]);

        Category::create($validatedData);
        return redirect()->route('Categories.index')->with('success', 'Category created succesfully!');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('Categories.edit', compact('category'));
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('Categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validatedData);
        return redirect()->route('Categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('Categories.index')->with('success', 'Category deleted successfully!');
    }    
}
