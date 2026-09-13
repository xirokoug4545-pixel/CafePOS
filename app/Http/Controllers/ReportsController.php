<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\WasteLog;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        $salesToday = Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total_amount');
        $ordersToday = Order::whereDate('created_at', today())->count();
        $tipsToday = OrderPayment::whereDate('created_at', today())->sum('tip_amount');
        $paymentBreakdown = OrderPayment::whereDate('created_at', today())->selectRaw('payment_type, SUM(amount) as total')->groupBy('payment_type')->pluck('total', 'payment_type');
        $lowStockProducts = Product::where('tracks_inventory', true)->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->orderBy('stock_quantity')->get();
        $wasteToday = WasteLog::whereDate('created_at', today())->sum('quantity');
        $categorySales = InventoryMovement::query()->where('movement_type', 'sale')->whereDate('inventory_movements.created_at', today())->join('products', 'products.id', '=', 'inventory_movements.product_id')->join('categories', 'categories.id', '=', 'products.category_id')->selectRaw('categories.name, SUM(ABS(inventory_movements.quantity) * products.price) as total')->groupBy('categories.id', 'categories.name')->pluck('total', 'name');

        return view('dashboard', compact('salesToday', 'ordersToday', 'tipsToday', 'paymentBreakdown', 'lowStockProducts', 'wasteToday', 'categorySales'));
    }
}