<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'productOptions'])->orderBy('name')->get();

        return view('Products.index', compact('products'));
    }

    public function create(): View
    {
        return view('Products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $options = $validated['options'] ?? [];
        unset($validated['options']);

        $product = Product::create($validated);
        $this->syncOptions($product, $options);

        return redirect()->route('Products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('Products.edit', [
            'product' => $product->load('productOptions'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validated($request);
        $options = $validated['options'] ?? [];
        unset($validated['options']);

        $product->update($validated);
        $this->syncOptions($product, $options);

        return redirect()->route('Products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('Products.index')->with('success', 'Product deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_seasonal' => ['nullable', 'boolean'],
            'tracks_inventory' => ['nullable', 'boolean'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'options' => ['nullable', 'array'],
            'options.*.option_type' => ['required_with:options.*.name', 'string', 'max:100'],
            'options.*.name' => ['required_with:options.*.option_type', 'string', 'max:100'],
            'options.*.additional_price' => ['required_with:options.*.name', 'numeric', 'min:0'],
        ]);
    }

    private function syncOptions(Product $product, array $options): void
    {
        $product->productOptions()->delete();

        foreach ($options as $option) {
            if (! empty($option['name'])) {
                ProductOption::create([
                    'product_id' => $product->id,
                    'option_type' => $option['option_type'],
                    'name' => $option['name'],
                    'additional_price' => $option['additional_price'],
                ]);
            }
        }
    }
}