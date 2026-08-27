@extends('admin.layouts.admin')

@section('title', 'Pusat Operasional Bisnis & E-Commerce')

@section('content')
<style>
    /* Dashboard Header Banner */
    .dashboard-header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: white;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);
    }
    .banner-text h2 {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 6px 0;
        color: #ffffff;
        letter-spacing: -0.5px;
    }
    .banner-text p {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }
    .banner-badge {
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        border: 1px solid rgba(96, 165, 250, 0.3);
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* KPI Grid - 100% Inline, Equal Height & Responsive */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .kpi-card {
        background: #fff;
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid #e9edf2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 105px;
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .kpi-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .kpi-info h3 {
        font-size: 26px;
        font-weight: 800;
        margin: 4px 0 0 0;
        color: #1e293b;
        line-height: 1.1;
    }
    .kpi-info span {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    .kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .kpi-blue { background: #eff6ff; color: #2563eb; }
    .kpi-purple { background: #f5f3ff; color: #7c3aed; }
    .kpi-orange { background: #fff7ed; color: #ea580c; }
    .kpi-green { background: #ecfdf5; color: #10b981; }

    /* Quick Actions Bar */
    .quick-actions-bar {
        background: #fff;
        border-radius: 14px;
        padding: 18px 24px;
        margin-bottom: 28px;
        border: 1px solid #e9edf2;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .quick-actions-title {
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .action-btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Dashboard Panels */
    .dashboard-panel {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        border: 1px solid #e9edf2;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        margin-bottom: 28px;
        height: calc(100% - 28px);
        display: flex;
        flex-direction: column;
    }
    .dashboard-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dashboard-panel-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-clean th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        border-top: none !important;
        padding: 12px 10px;
    }
    .table-clean td {
        vertical-align: middle !important;
        font-size: 13px;
        padding: 12px 10px;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 600px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        .dashboard-header-banner {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<!-- Header Welcome Banner -->
<div class="dashboard-header-banner">
    <div class="banner-text">
        <h2>Executive Business & POS Dashboard</h2>
        <p>Pantau arus rantai pasok pengadaan, pesanan penjualan, inventori, dan status kas secara real-time.</p>
    </div>
    <div class="banner-badge">
        <i class="fa fa-calendar"></i>
        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>

<!-- Top KPI Cards (Equal Height Grid) -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-info">
            <span>Pesanan Penjualan</span>
            <h3>{{ number_format($totalSalesOrders) }}</h3>
        </div>
        <div class="kpi-icon kpi-blue">
            <i class="fa fa-shopping-bag"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-info">
            <span>Order Pembelian (PO)</span>
            <h3>{{ number_format($totalPurchaseOrders) }}</h3>
        </div>
        <div class="kpi-icon kpi-purple">
            <i class="fa fa-truck"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-info">
            <span>Master Produk</span>
            <h3>{{ number_format($totalProducts) }}</h3>
        </div>
        <div class="kpi-icon kpi-orange">
            <i class="fa fa-cubes"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-info">
            <span>Total Pelanggan</span>
            <h3>{{ number_format($totalCustomers) }}</h3>
        </div>
        <div class="kpi-icon kpi-green">
            <i class="fa fa-users"></i>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="quick-actions-bar">
    <div class="quick-actions-title">
        <i class="fa fa-bolt text-warning"></i>
        <span>Pintasan Cepat (Quick Actions):</span>
    </div>
    <div class="action-btn-group">
        <a href="{{ route('admin.sales.orders.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Buat Sales Order
        </a>
        <a href="{{ route('admin.purchase.orders.create') }}" class="btn btn-sm btn-info">
            <i class="fa fa-plus"></i> Buat Purchase Order
        </a>
        <a href="{{ route('admin.master.barang.create') }}" class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i> Tambah Barang
        </a>
        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-sm btn-dark">
            <i class="fa fa-comments"></i> Tanya AI Assistant
        </a>
        <a href="{{ route('admin.xendit.create') }}" class="btn btn-sm btn-warning">
            <i class="fa fa-credit-card"></i> Tagihan Xendit
        </a>
    </div>
</div>

<!-- Main Content Row: Sales & Purchase -->
<div class="row">
    <!-- Recent Sales Orders -->
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h4 class="dashboard-panel-title">
                    <i class="fa fa-line-chart text-primary"></i> Penjualan Terkini (Sales Orders)
                </h4>
                <a href="{{ route('admin.sales.orders.index') }}" class="btn btn-xs btn-default">Lihat Semua &rarr;</a>
            </div>

            <div class="table-responsive" style="flex-grow: 1;">
                <table class="table table-hover table-clean">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                            <tr>
                                <td><strong>{{ $sale->no_order_penjualan }}</strong></td>
                                <td>{{ optional($sale->tanggal)->format('d M Y') ?: '-' }}</td>
                                <td>{{ optional($sale->pelanggan)->nama_pelanggan ?: 'Umum' }}</td>
                                <td>
                                    @php
                                        $statusVal = trim((string) $sale->status);
                                    @endphp
                                    @if($statusVal === '0' || strtolower($statusVal) === 'batal')
                                        <span class="label label-danger">Batal</span>
                                    @elseif($statusVal === '1' || strtolower($statusVal) === 'aktif')
                                        <span class="label label-success">Aktif</span>
                                    @elseif($statusVal === '2' || strtolower($statusVal) === 'diproses')
                                        <span class="label label-info">Diproses</span>
                                    @elseif($statusVal === '3' || strtolower($statusVal) === 'selesai' || $statusVal === '$')
                                        <span class="label label-primary">Selesai</span>
                                    @else
                                        <span class="label label-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.sales.orders.show', [$sale->no_order_penjualan]) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 30px;">Belum ada pesanan penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Purchase Orders -->
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h4 class="dashboard-panel-title">
                    <i class="fa fa-truck text-purple"></i> Pembelian Terkini (Purchase Orders)
                </h4>
                <a href="{{ route('admin.purchase.orders.index') }}" class="btn btn-xs btn-default">Lihat Semua &rarr;</a>
            </div>

            <div class="table-responsive" style="flex-grow: 1;">
                <table class="table table-hover table-clean">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Tanggal</th>
                            <th>Vendor / Supplier</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPurchases as $po)
                            <tr>
                                <td><strong>{{ $po->no_order_pembelian }}</strong></td>
                                <td>{{ optional($po->tanggal)->format('d M Y') ?: '-' }}</td>
                                <td>{{ optional($po->vendor)->nama_vendor ?: '-' }}</td>
                                <td>
                                    @php
                                        $poStatusVal = trim((string) $po->status);
                                    @endphp
                                    @if($poStatusVal === '0' || strtolower($poStatusVal) === 'batal')
                                        <span class="label label-danger">Batal</span>
                                    @elseif($poStatusVal === '1' || strtolower($poStatusVal) === 'aktif')
                                        <span class="label label-success">Aktif</span>
                                    @elseif($poStatusVal === '2' || strtolower($poStatusVal) === 'diproses')
                                        <span class="label label-info">Diproses</span>
                                    @elseif($poStatusVal === '3' || strtolower($poStatusVal) === 'selesai')
                                        <span class="label label-primary">Selesai</span>
                                    @else
                                        <span class="label label-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.purchase.orders.show', [$po->no_order_pembelian]) }}" class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 30px;">Belum ada order pembelian ke vendor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payments & AI Assistant Row -->
<div class="row">
    <!-- Xendit Payments Overview -->
    <div class="col-md-7 col-sm-12 col-xs-12">
        <div class="dashboard-panel">
            <div class="dashboard-panel-header">
                <h4 class="dashboard-panel-title">
                    <i class="fa fa-credit-card text-success"></i> Riwayat Pembayaran Digital (Xendit Gateway)
                </h4>
                <a href="{{ route('admin.xendit.index') }}" class="btn btn-xs btn-default">Lihat Semua &rarr;</a>
            </div>

            <div class="table-responsive" style="flex-grow: 1;">
                <table class="table table-hover table-clean">
                    <thead>
                        <tr>
                            <th>Ref / Invoice</th>
                            <th>Customer Email</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td><strong>{{ $payment->external_id ?: $payment->reference_id }}</strong></td>
                                <td>{{ $payment->payer_email ?: '-' }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td><span class="label label-default">{{ $payment->payment_method ?: 'Online' }}</span></td>
                                <td>
                                    @if($payment->status === 'PAID')
                                        <span class="label label-success">LUNAS</span>
                                    @elseif($payment->status === 'PENDING')
                                        <span class="label label-warning">PENDING</span>
                                    @elseif($payment->status === 'EXPIRED')
                                        <span class="label label-danger">EXPIRED</span>
                                    @else
                                        <span class="label label-default">{{ $payment->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 30px;">Belum ada transaksi pembayaran digital via Xendit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- AI Shopping & POS Assistant Card -->
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="dashboard-panel" style="background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 100%); color: white; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa fa-magic text-warning"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; color: white; font-weight: 700;">AI Business Assistant</h4>
                        <span style="font-size: 12px; color: #c4b5fd;">Didukung oleh Gemini AI API</span>
                    </div>
                </div>
                <p style="font-size: 13px; color: #ddd6fe; line-height: 1.6; margin-bottom: 20px;">
                    Gunakan asisten AI untuk menganalisis pergerakan stok barang, membantu rekomendasi pesanan kepada pelanggan, atau membuat draf purchase order secara cerdas.
                </p>
            </div>
            <a href="{{ route('admin.chatbot.index') }}" class="btn btn-warning btn-block" style="font-weight: 700; color: #1e1b4b; padding: 12px;">
                <i class="fa fa-comments"></i> Buka Chat AI Assistant
            </a>
        </div>
    </div>
</div>
@endsection
