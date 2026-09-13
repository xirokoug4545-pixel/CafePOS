@extends('Layouts.app')

@section('title', 'Live Orders')

@section('main')
    @php
        $statusStyles = [
            'pending' => 'border-amber-200 bg-amber-50 text-amber-800',
            'preparing' => 'border-sky-200 bg-sky-50 text-sky-800',
            'ready' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            'completed' => 'border-stone-200 bg-stone-100 text-stone-700',
        ];
        $statusDots = [
            'pending' => 'bg-amber-500',
            'preparing' => 'bg-sky-500',
            'ready' => 'bg-emerald-500',
            'completed' => 'bg-stone-500',
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col justify-between gap-4 border-b border-stone-200 pb-6 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Staff workspace</p>
                <h1 class="mt-1 text-3xl font-bold tracking-tight text-stone-950">Live orders</h1>
                <p class="mt-2 text-sm text-stone-600">Keep the counter and kitchen moving with the latest customer orders.</p>
            </div>
            <button type="button" onclick="window.location.reload()" class="inline-flex items-center justify-center rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-700 shadow-sm transition hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2">
                Refresh orders
            </button>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($orders as $order)
            @php
                $status = strtolower($order->status);
                $statusClass = $statusStyles[$status] ?? 'border-stone-200 bg-stone-100 text-stone-700';
                $statusDot = $statusDots[$status] ?? 'bg-stone-500';
            @endphp
            <article class="flex h-full flex-col overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4 border-b border-stone-100 px-5 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-stone-500">Order</p>
                            <h2 class="mt-1 text-xl font-bold text-stone-950">#{{ $order->id }}</h2>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-bold {{ $statusClass }}">
                            <span class="h-2 w-2 rounded-full {{ $statusDot }}"></span>
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                    <div class="flex-1 px-5 py-4">
                        <div class="mb-3 flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-stone-500">
                            <span>Items</span>
                            <span>{{ $order->orderItems->sum('quantity') }} total</span>
                        </div>
                        @foreach ($order->orderItems as $item)
                            @php
                                $options = collect($item->selected_options ?? [])->map(function ($option) {
                                    return is_array($option) ? ($option['name'] ?? null) : $option;
                                })->filter()->implode(', ');
                            @endphp
                            <div class="border-b border-stone-100 py-3 last:border-b-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex min-w-0 items-start gap-3">
                                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-md bg-stone-100 px-2 text-xs font-bold text-stone-700">{{ $item->quantity }}x</span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-stone-900">{{ $item->product->name }}</p>
                                            <p class="mt-1 text-xs text-stone-500">{{ $options ? 'Options: '.$options : 'Original preparation' }}</p>
                                        </div>
                                    </div>
                                    <strong class="shrink-0 text-sm text-stone-900">${{ number_format((float) $item->price * $item->quantity, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-4 flex items-center justify-between border-t border-stone-200 pt-4">
                            <span class="font-semibold text-stone-600">Total</span>
                            <strong class="text-xl text-stone-950">${{ number_format((float) $order->total_amount, 2) }}</strong>
                        </div>
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="mt-4 inline-flex w-full items-center justify-center rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">View / Print Invoice</a>
                    </div>

                    <div class="border-t border-stone-100 bg-stone-50 px-5 py-4">
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            <label class="sr-only" for="order-status-{{ $order->id }}">Update order status</label>
                            <select id="order-status-{{ $order->id }}" name="status" class="min-w-0 flex-1 rounded-lg border-stone-300 bg-white py-2.5 text-sm font-semibold text-stone-700 shadow-sm focus:border-amber-600 focus:ring-amber-600" aria-label="Order status">
                                @foreach (['pending', 'preparing', 'ready', 'completed'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-lg bg-stone-950 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2">Update</button>
                        </form>
                    </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white px-6 py-14 text-center md:col-span-2 xl:col-span-3">
                <p class="text-lg font-semibold text-stone-800">No orders have been placed yet.</p>
                <p class="mt-2 text-sm text-stone-500">New customer orders will appear here.</p>
            </div>
        @endforelse
        </div>
    </div>
@endsection