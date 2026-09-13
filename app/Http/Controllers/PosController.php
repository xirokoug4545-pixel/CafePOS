<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\TillSession;
use App\Models\WasteLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PosController extends Controller
{
    public function pay(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.payment_type' => ['required', Rule::in(['cash', 'card', 'mobile'])],
            'payments.*.amount' => ['required', 'numeric', 'min:0.01'],
            'payments.*.tip_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($order->payment_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'This order has already been paid.'], 422);
        }

        return DB::transaction(function () use ($validated, $order, $request): JsonResponse {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $paymentTotal = collect($validated['payments'])->sum('amount');
            $tipTotal = collect($validated['payments'])->sum(fn ($payment) => $payment['tip_amount'] ?? 0);
            $due = (float) $order->total_amount + (float) $order->tip_amount;

            if (round($paymentTotal, 2) < round($due, 2)) {
                return response()->json(['success' => false, 'message' => 'Payment does not cover the order total.'], 422);
            }

            foreach ($validated['payments'] as $payment) {
                if (($payment['tip_amount'] ?? 0) > 0 && $payment['payment_type'] !== 'card') {
                    return response()->json(['success' => false, 'message' => 'Tips can only be added to card payments.'], 422);
                }

                OrderPayment::create([
                    'order_id' => $order->id,
                    'user_id' => $request->user()?->id,
                    'payment_type' => $payment['payment_type'],
                    'amount' => $payment['amount'],
                    'tip_amount' => $payment['tip_amount'] ?? 0,
                    'reference' => 'SIM-'.str()->upper(str()->random(10)),
                ]);
            }

            $order->update([
                'payment_status' => 'paid',
                'tip_amount' => $tipTotal,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            foreach ($order->orderItems()->where('status', 'active')->get() as $item) {
                $product = Product::query()->lockForUpdate()->find($item->product_id);

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

            return response()->json(['success' => true, 'order_id' => $order->id, 'receipt_reference' => 'SIM-'.$order->id], 201);
        });
    }

    public function voidItem(Request $request, Order $order, OrderItem $item): JsonResponse
    {
        abort_unless($item->order_id === $order->id, 404);

        $validated = $request->validate(['reason' => ['required', 'string', 'min:3', 'max:500']]);
        $managerOverride = $request->boolean('manager_override');

        if ((! $managerOverride && $request->user()?->role === 'cashier') || ($order->payment_status === 'paid' && (! $managerOverride || ! in_array($request->user()?->role, ['manager', 'admin'], true)))) {
            return response()->json(['success' => false, 'message' => 'Manager override is required after payment.'], 403);
        }

        if ($item->status === 'voided') {
            return response()->json(['success' => false, 'message' => 'This item is already voided.'], 422);
        }

        $item->update(['status' => 'voided', 'voided_by' => $request->user()?->id, 'void_reason' => $validated['reason']]);

        return response()->json(['success' => true, 'message' => 'Order item voided.']);
    }

    public function logWaste(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'reason_type' => ['required', Rule::in(['waste', 'comp'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        DB::transaction(function () use ($validated, $request): void {
            $product = Product::query()->lockForUpdate()->findOrFail($validated['product_id']);
            $product->decrement('stock_quantity', $validated['quantity']);
            WasteLog::create([...$validated, 'user_id' => $request->user()?->id]);
            InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()?->id,
                'movement_type' => $validated['reason_type'],
                'quantity' => -$validated['quantity'],
                'reason' => $validated['reason'],
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Waste record saved.'], 201);
    }

    public function openTill(Request $request): JsonResponse
    {
        $validated = $request->validate(['opening_float' => ['required', 'numeric', 'min:0']]);
        $active = TillSession::whereNull('closed_at')->first();

        if ($active) {
            return response()->json(['success' => false, 'message' => 'A till session is already open.'], 422);
        }

        $session = TillSession::create(['opened_by' => $request->user()->id, 'opening_float' => $validated['opening_float'], 'opened_at' => now()]);

        return response()->json(['success' => true, 'till_session_id' => $session->id], 201);
    }

    public function closeTill(Request $request, TillSession $tillSession): JsonResponse
    {
        $validated = $request->validate(['counted_cash' => ['required', 'numeric', 'min:0'], 'discrepancy_notes' => ['nullable', 'string', 'max:500']]);
        $cashSales = OrderPayment::where('payment_type', 'cash')->whereDate('created_at', today())->sum('amount');
        $expected = (float) $tillSession->opening_float + (float) $cashSales;
        $tillSession->update(['closed_by' => $request->user()->id, 'expected_cash' => $expected, 'counted_cash' => $validated['counted_cash'], 'discrepancy' => $validated['counted_cash'] - $expected, 'discrepancy_notes' => $validated['discrepancy_notes'] ?? null, 'closed_at' => now()]);

        return response()->json(['success' => true, 'expected_cash' => $expected, 'discrepancy' => $tillSession->discrepancy]);
    }
}