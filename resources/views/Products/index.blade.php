@extends('Layouts.app')

@section('title', 'Products & Modifiers')

@section('main')
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Catalog management</p>
                <h1 class="mt-1 text-3xl font-bold text-stone-950">Products & modifiers</h1>
                <p class="mt-2 text-sm text-stone-600">Add drinks, bakery items, food, stock counts, and option prices.</p>
            </div>
            @if (in_array(auth()->user()->role, ['manager', 'admin'], true))
                <a href="{{ route('Products.create') }}" class="rounded-lg bg-stone-950 px-4 py-2.5 text-sm font-bold text-white hover:bg-stone-800">Add product</a>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($products as $product)
                <article class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="text-xs font-semibold uppercase tracking-wider text-amber-700">{{ $product->category->name }}</p><h2 class="mt-1 text-lg font-bold text-stone-950">{{ $product->name }}</h2></div>
                        <strong class="text-lg text-amber-800">${{ number_format((float) $product->price, 2) }}</strong>
                    </div>
                    <p class="mt-3 min-h-10 text-sm text-stone-600">{{ $product->description }}</p>
                    @if ($product->tracks_inventory)
                        <p class="mt-3 text-sm font-semibold {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-700' : 'text-emerald-700' }}">Stock: {{ $product->stock_quantity }} <span class="font-normal text-stone-500">/ alert at {{ $product->low_stock_threshold }}</span></p>
                    @else
                        <p class="mt-3 text-sm text-stone-500">Made to order / no stock tracking</p>
                    @endif
                    <div class="mt-4 border-t border-stone-100 pt-3"><p class="text-xs font-semibold uppercase tracking-wider text-stone-500">Modifiers</p><p class="mt-1 text-sm text-stone-700">{{ $product->productOptions->map(fn ($option) => $option->name.' (+$'.number_format((float) $option->additional_price, 2).')')->implode(', ') ?: 'None' }}</p></div>
                    @if (in_array(auth()->user()->role, ['manager', 'admin'], true))
                        <div class="mt-5 flex gap-2"><a href="{{ route('Products.edit', $product) }}" class="flex-1 rounded-lg border border-stone-300 px-3 py-2 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Edit</a><form method="POST" action="{{ route('Products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Delete</button></form></div>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-10 text-center text-stone-500 md:col-span-2 xl:col-span-3">No products yet. Add the first drink or bakery item.</div>
            @endforelse
        </div>
    </div>
@endsection
