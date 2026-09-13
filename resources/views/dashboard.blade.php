@extends('Layouts.app')

@section('title', 'Dashboard')

@section('main')
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-200 pb-6 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Cafe operations</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Today at a glance</h1>
                <p class="mt-2 text-sm text-stone-600">Sales, stock, payments, and the till in one place.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg bg-stone-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-stone-800">Open live orders</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['label' => 'Sales today', 'value' => '$'.number_format((float) $salesToday, 2), 'tone' => 'text-amber-700'], ['label' => 'Orders today', 'value' => number_format($ordersToday), 'tone' => 'text-sky-700'], ['label' => 'Tips collected', 'value' => '$'.number_format((float) $tipsToday, 2), 'tone' => 'text-emerald-700'], ['label' => 'Waste units', 'value' => number_format($wasteToday), 'tone' => 'text-red-700']] as $stat)
                <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm"><p class="text-sm font-semibold text-stone-500">{{ $stat['label'] }}</p><p class="mt-3 text-3xl font-bold {{ $stat['tone'] }}">{{ $stat['value'] }}</p></div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between"><div><h2 class="text-lg font-bold text-stone-950">Sales by category</h2><p class="mt-1 text-sm text-stone-500">Paid sales recorded today.</p></div><span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">Daily</span></div>
                <div class="mt-6 space-y-4">
                    @forelse ($categorySales as $category => $total)
                        <div><div class="mb-1 flex justify-between text-sm"><span class="font-semibold text-stone-700">{{ $category }}</span><span class="font-bold text-stone-950">${{ number_format((float) $total, 2) }}</span></div><div class="h-2 rounded-full bg-stone-100"><div class="h-2 rounded-full bg-amber-600" style="width: {{ $salesToday > 0 ? min(100, ((float) $total / (float) $salesToday) * 100) : 0 }}%"></div></div></div>
                    @empty
                        <p class="py-6 text-sm text-stone-500">No category sales yet today.</p>
                    @endforelse
                </div>
            </section>
            <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm"><h2 class="text-lg font-bold text-stone-950">Payment mix</h2><div class="mt-5 space-y-3">@forelse ($paymentBreakdown as $type => $total)<div class="flex items-center justify-between rounded-lg bg-stone-50 px-3 py-3"><span class="text-sm font-semibold capitalize text-stone-600">{{ $type }}</span><span class="font-bold text-stone-950">${{ number_format((float) $total, 2) }}</span></div>@empty<p class="py-6 text-sm text-stone-500">No payments recorded yet today.</p>@endforelse</div></section>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5"><div class="flex items-center justify-between"><h2 class="text-lg font-bold text-amber-950">Low stock alerts</h2><span class="rounded-full bg-amber-200 px-2.5 py-1 text-xs font-bold text-amber-900">{{ $lowStockProducts->count() }}</span></div><div class="mt-4 divide-y divide-amber-200">@forelse ($lowStockProducts as $product)<div class="flex items-center justify-between py-3"><span class="text-sm font-semibold text-amber-950">{{ $product->name }}</span><span class="text-sm font-bold text-red-700">{{ $product->stock_quantity }} left</span></div>@empty<p class="py-4 text-sm text-amber-800">All tracked products are stocked above their thresholds.</p>@endforelse</div></section>
            <section class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm"><h2 class="text-lg font-bold text-stone-950">POS actions</h2><div class="mt-4 grid gap-3 sm:grid-cols-2"><a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-stone-200 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-amber-500 hover:text-amber-800">Manage live orders</a><a href="{{ route('home') }}" class="rounded-lg border border-stone-200 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-amber-500 hover:text-amber-800">Open counter POS</a><a href="{{ route('Categories.index') }}" class="rounded-lg border border-stone-200 px-4 py-3 text-sm font-semibold text-stone-700 transition hover:border-amber-500 hover:text-amber-800">Manage categories</a><span class="rounded-lg border border-dashed border-stone-300 px-4 py-3 text-sm font-semibold text-stone-400">Till API ready</span></div></section>
        </div>
    </div>
@endsection
