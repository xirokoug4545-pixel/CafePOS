<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cafe POS Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <header class="border-b border-stone-800 bg-stone-950 text-stone-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">Cafe POS</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Build your order</h1>
                <p class="mt-2 max-w-xl text-sm text-stone-300">Choose your favourites, customize them, and send your order straight to the counter.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden rounded-full border border-stone-700 px-4 py-2 text-sm text-stone-300 sm:inline-flex">Open today</span>
                <a href="{{ route('login') }}" class="rounded-lg border border-amber-400 px-4 py-2.5 text-sm font-bold text-amber-300 transition hover:bg-amber-400 hover:text-stone-950">Staff login</a>
            </div>
        </div>
    </header>

    <main class="mx-auto grid max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:grid-cols-3 lg:px-8">
        <section class="space-y-10 lg:col-span-2" aria-labelledby="menu-heading">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-amber-700">The menu</p>
                    <h2 id="menu-heading" class="mt-1 text-2xl font-bold tracking-tight text-stone-950">Made for your moment</h2>
                </div>
                <span class="text-sm text-stone-500">{{ $categories->sum(fn ($category) => $category->products->count()) }} items</span>
            </div>

            @forelse ($categories as $category)
                <section aria-labelledby="category-{{ $category->id }}">
                    <div class="mb-4 flex items-center gap-3">
                        <h3 id="category-{{ $category->id }}" class="text-xl font-bold text-stone-900">{{ $category->name }}</h3>
                        <span class="h-px flex-1 bg-stone-300"></span>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach ($category->products as $product)
                            <article class="product-card flex h-full flex-col rounded-2xl border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}">
                                <div class="flex items-start justify-between gap-3">
                                    <h4 class="text-lg font-bold text-stone-950">{{ $product->name }}</h4>
                                    @if ($product->is_seasonal)
                                        <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">✨ Seasonal</span>
                                    @endif
                                </div>
                                <p class="mt-2 min-h-12 text-sm leading-6 text-stone-600">{{ $product->description ?: 'A cafe favourite, prepared fresh to order.' }}</p>
                                <p class="mt-4 text-lg font-bold text-amber-800">${{ number_format((float) $product->price, 2) }}</p>

                                @php($optionGroups = $product->productOptions->groupBy('option_type'))
                                @if ($optionGroups->isNotEmpty())
                                    <div class="mt-5 space-y-3 border-t border-stone-100 pt-4">
                                        @foreach ($optionGroups as $optionType => $options)
                                            <label class="block text-sm font-semibold text-stone-700">
                                                {{ $optionType }}
                                                <select class="product-option mt-1.5 block w-full rounded-lg border-stone-300 bg-stone-50 py-2.5 text-sm text-stone-800 shadow-sm focus:border-amber-600 focus:ring-amber-600" data-option-type="{{ $optionType }}">
                                                    <option value="" data-price="0">No {{ strtolower($optionType) }}</option>
                                                    @foreach ($options as $option)
                                                        <option value="{{ $option->id }}" data-price="{{ $option->additional_price }}" data-option-name="{{ $option->name }}">
                                                            {{ $option->name }}@if ((float) $option->additional_price > 0) (+${{ number_format((float) $option->additional_price, 2) }})@endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                                <button type="button" class="add-to-cart mt-6 inline-flex w-full items-center justify-center rounded-lg bg-amber-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-600 focus:ring-offset-2">
                                    Add to order
                                </button>
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="rounded-2xl border border-dashed border-stone-300 bg-white p-10 text-center text-stone-500">The menu is being prepared.</div>
            @endforelse
        </section>

        <aside class="lg:col-span-1">
            <div class="sticky top-6 rounded-2xl border border-stone-200 bg-white p-5 shadow-md" aria-labelledby="cart-heading">
                <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-amber-700">Your order</p>
                        <h2 id="cart-heading" class="mt-1 text-xl font-bold text-stone-950">Order summary</h2>
                    </div>
                    <span id="cart-count" class="inline-flex h-8 min-w-8 items-center justify-center rounded-full bg-stone-100 px-2 text-sm font-bold text-stone-700">0</span>
                </div>

                <div id="cart-items" class="divide-y divide-stone-100">
                    <p class="py-8 text-center text-sm text-stone-500">Your cart is empty.</p>
                </div>

                <div class="mt-4 border-t border-stone-200 pt-4">
                    <div class="flex items-center justify-between text-lg font-bold text-stone-950">
                        <span>Total</span>
                        <span>$<span id="cart-total">0.00</span></span>
                    </div>
                    <label for="payment-type" class="mt-5 block text-sm font-semibold text-stone-700">Pay now with</label>
                    <select id="payment-type" class="mt-1.5 block w-full rounded-lg border-stone-300 bg-stone-50 py-2.5 text-sm text-stone-800 shadow-sm focus:border-amber-600 focus:ring-amber-600">
                        <option value="cash">Cash</option>
                        <option value="card">Card (simulated)</option>
                        <option value="mobile">Mobile payment (simulated)</option>
                    </select>
                    <button type="button" id="place-order" class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-stone-950 px-4 py-3 text-sm font-bold text-white transition hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        Pay & place order
                    </button>
                    <p id="order-notice" class="mt-3 min-h-5 text-center text-sm font-semibold" role="status" aria-live="polite"></p>
                </div>
            </div>
        </aside>
    </main>

    <script>
        const cart = [];
        const cartItems = document.getElementById('cart-items');
        const cartCount = document.getElementById('cart-count');
        const cartTotal = document.getElementById('cart-total');
        const placeOrderButton = document.getElementById('place-order');
        const paymentType = document.getElementById('payment-type');
        const notice = document.getElementById('order-notice');

        const money = (value) => Number(value).toFixed(2);

        function selectedOptions(product) {
            return [...product.querySelectorAll('.product-option')]
                .map((select) => select.selectedOptions[0])
                .filter((option) => option.value)
                .map((option) => ({
                    id: Number(option.value),
                    name: option.dataset.optionName,
                    price: Number(option.dataset.price),
                }));
        }

        function renderCart() {
            cartItems.innerHTML = '';
            let total = 0;
            let count = 0;

            if (!cart.length) {
                cartItems.innerHTML = '<p class="py-8 text-center text-sm text-stone-500">Your cart is empty.</p>';
            }

            cart.forEach((item, index) => {
                const itemTotal = item.unitPrice * item.quantity;
                total += itemTotal;
                count += item.quantity;
                const row = document.createElement('div');
                row.className = 'py-4';
                row.innerHTML = `
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold text-stone-900">${item.name}</p>
                            <p class="mt-1 text-xs text-stone-500">${item.options.length ? item.options.map((option) => option.name).join(', ') : 'Original'}</p>
                        </div>
                        <p class="font-semibold text-stone-900">$${money(itemTotal)}</p>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <div class="inline-flex items-center rounded-lg border border-stone-200">
                            <button type="button" class="quantity-button px-3 py-1.5 text-stone-700 hover:bg-stone-100" data-index="${index}" data-change="-1" aria-label="Decrease quantity">−</button>
                            <span class="min-w-8 text-center text-sm font-semibold">${item.quantity}</span>
                            <button type="button" class="quantity-button px-3 py-1.5 text-stone-700 hover:bg-stone-100" data-index="${index}" data-change="1" aria-label="Increase quantity">+</button>
                        </div>
                        <button type="button" class="remove-item text-xs font-semibold text-red-700 hover:text-red-900" data-index="${index}">Remove</button>
                    </div>`;
                cartItems.appendChild(row);
            });

            cartCount.textContent = count;
            cartTotal.textContent = money(total);
            placeOrderButton.disabled = !cart.length;
        }

        document.querySelectorAll('.add-to-cart').forEach((button) => {
            button.addEventListener('click', () => {
                const product = button.closest('.product-card');
                const options = selectedOptions(product);
                const productId = Number(product.dataset.productId);
                const unitPrice = Number(product.dataset.productPrice) + options.reduce((sum, option) => sum + option.price, 0);
                const optionKey = options.map((option) => option.id).join(',');
                const existing = cart.find((item) => item.productId === productId && item.optionKey === optionKey);

                if (existing) {
                    existing.quantity += 1;
                } else {
                    cart.push({ productId, name: product.dataset.productName, unitPrice, quantity: 1, options, optionKey });
                }

                notice.textContent = `${product.dataset.productName} added.`;
                notice.className = 'mt-3 min-h-5 text-center text-sm font-semibold text-emerald-700';
                renderCart();
            });
        });

        cartItems.addEventListener('click', (event) => {
            const quantityButton = event.target.closest('.quantity-button');
            const removeButton = event.target.closest('.remove-item');
            const index = Number((quantityButton || removeButton)?.dataset.index);

            if (quantityButton) {
                cart[index].quantity += Number(quantityButton.dataset.change);
                if (cart[index].quantity < 1) cart.splice(index, 1);
                renderCart();
            }

            if (removeButton) {
                cart.splice(index, 1);
                renderCart();
            }
        });

        placeOrderButton.addEventListener('click', async () => {
            if (!cart.length) return;

            placeOrderButton.disabled = true;
            notice.textContent = 'Sending your order...';
            notice.className = 'mt-3 min-h-5 text-center text-sm font-semibold text-stone-500';

            try {
                const response = await fetch('{{ route('order.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        payment_type: paymentType.value,
                        items: cart.map((item) => ({
                            product_id: item.productId,
                            quantity: item.quantity,
                            options: item.options.map((option) => option.id),
                        })),
                    }),
                });
                const result = await response.json();

                if (!response.ok || !result.success) throw new Error(result.message || 'Unable to place order.');

                cart.length = 0;
                notice.innerHTML = `Order #${result.order_id} received. Thank you! <a href="${result.invoice_url}" target="_blank" class="ml-2 underline">View invoice</a>`;
                notice.className = 'mt-3 min-h-5 text-center text-sm font-semibold text-emerald-700';
                renderCart();
            } catch (error) {
                notice.textContent = error.message;
                notice.className = 'mt-3 min-h-5 text-center text-sm font-semibold text-red-700';
                placeOrderButton.disabled = false;
            }
        });

        renderCart();
    </script>
</body>
</html>
