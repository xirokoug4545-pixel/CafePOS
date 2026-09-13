<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function invoice(Order $order): View
    {
        $order->load(['orderItems.product', 'payments']);

        return view('orders.invoice', compact('order'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_mode' => ['nullable', 'in:counter,table'],
            'table_name' => ['nullable', 'string', 'max:100'],
            'hold' => ['nullable', 'boolean'],
            'payment_type' => ['required', 'in:cash,card,mobile'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.options' => ['nullable', 'array'],
            'items.*.options.*' => ['integer', 'exists:product_options,id'],
        ]);

        DB::beginTransaction();

        try {
            $products = Product::query()
                ->with('productOptions')
                ->whereIn('id', collect($validated['items'])->pluck('product_id')->unique())
                ->get()
                ->keyBy('id');

            $totalAmount = 0;
            $orderItems = [];

            foreach ($validated['items'] as $index => $item) {
                $product = $products->get($item['product_id']);
                $optionIds = collect($item['options'] ?? [])->unique()->values();
                $selectedOptions = $product->productOptions->whereIn('id', $optionIds);

                if ($selectedOptions->count() !== $optionIds->count()) {
                    throw ValidationException::withMessages([
                        "items.{$index}.options" => 'Each option must belong to the selected product.',
                    ]);
                }

                $unitPrice = (float) $product->price + (float) $selectedOptions->sum('additional_price');
                $quantity = $item['quantity'];

                if ($product->tracks_inventory && $product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        "items.{$index}.quantity" => "Only {$product->stock_quantity} {$product->name} item(s) remain in stock.",
                    ]);
                }

                $totalAmount += $unitPrice * $quantity;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'selected_options' => $selectedOptions->map(fn ($option) => [
                        'id' => $option->id,
                        'option_type' => $option->option_type,
                        'name' => $option->name,
                        'additional_price' => (float) $option->additional_price,
                    ])->values()->all(),
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'status' => 'preparing',
                'order_mode' => $validated['order_mode'] ?? 'counter',
                'table_name' => $validated['table_name'] ?? null,
                'total_amount' => round($totalAmount, 2),
                'subtotal' => round($totalAmount, 2),
                'payment_status' => 'paid',
                'held_at' => ($validated['hold'] ?? false) ? now() : null,
            ]);

            $order->orderItems()->createMany($orderItems);

            OrderPayment::create([
                'order_id' => $order->id,
                'user_id' => $request->user()?->id,
                'payment_type' => $validated['payment_type'],
                'amount' => round($totalAmount, 2),
                'reference' => 'SIM-'.str()->upper(str()->random(10)),
            ]);

            foreach ($order->orderItems as $orderItem) {
                $product = Product::query()->lockForUpdate()->find($orderItem->product_id);

                if ($product?->tracks_inventory) {
                    $product->decrement('stock_quantity', $orderItem->quantity);
                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'user_id' => $request->user()?->id,
                        'movement_type' => 'sale',
                        'quantity' => -$orderItem->quantity,
                        'order_id' => $order->id,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'invoice_url' => route('orders.invoice', $order),
            ], 201);
        } catch (ValidationException $exception) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'The order could not be validated.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            DB::rollBack();
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'The order could not be created.',
            ], 500);
        }
    }
}
