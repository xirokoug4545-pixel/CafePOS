<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryMovement;
use App\Models\OrderPayment;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with('orderItems.product')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'preparing', 'ready', 'completed'])],
        ]);

        DB::transaction(function () use ($order, $validated, $request): void {
            $order->update(['status' => $validated['status']]);

            if ($validated['status'] === 'completed' && $order->payment_status !== 'paid') {
                OrderPayment::create([
                    'order_id' => $order->id,
                    'user_id' => $request->user()?->id,
                    'payment_type' => 'cash',
                    'amount' => $order->total_amount,
                    'reference' => 'SIM-CASH-'.$order->id,
                ]);

                $order->update(['payment_status' => 'paid', 'completed_at' => now()]);

                foreach ($order->orderItems()->where('status', 'active')->get() as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($product?->tracks_inventory) {
                        $product->decrement('stock_quantity', $item->quantity);
                        InventoryMovement::create([
                            'product_id' => $product->id,
                            'user_id' => $request->user()?->id,
                            'movement_type' => 'sale',
                            'quantity' => -$item->quantity,
                            'order_id' => $order->id,
                        ]);
                    }
                }
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'order' => $order->fresh('orderItems.product'),
            ]);
        }

        return back()->with('success', 'Order status updated successfully.');
    }
}
