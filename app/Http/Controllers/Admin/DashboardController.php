<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Pelanggan;
use App\Models\Master\Vendor;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Sales\SalesOrder;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the business & e-commerce application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Business Metrics
        $totalSalesOrders = SalesOrder::count();
        $totalPurchaseOrders = PurchaseOrder::count();
        $totalProducts = Barang::count();
        $totalCustomers = Pelanggan::count();
        $totalVendors = Vendor::count();

        // Payment Volume
        $totalRevenue = Payment::where('status', 'PAID')->sum('paid_amount');
        $pendingPaymentsCount = Payment::where('status', 'PENDING')->count();

        // Recent Transactions
        $recentSales = SalesOrder::with(['pelanggan', 'details'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $recentPurchases = PurchaseOrder::with(['vendor', 'details'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $recentPayments = Payment::latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSalesOrders',
            'totalPurchaseOrders',
            'totalProducts',
            'totalCustomers',
            'totalVendors',
            'totalRevenue',
            'pendingPaymentsCount',
            'recentSales',
            'recentPurchases',
            'recentPayments'
        ));
    }
}
