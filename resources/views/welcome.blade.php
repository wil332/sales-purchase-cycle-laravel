@extends('layouts.welcome')

@section('content')
<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e293b 100%);
        color: white;
        padding: 80px 0 100px 0;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
        pointer-events: none;
    }
    .hero-content {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        align-items: center;
        gap: 48px;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #a5b4fc;
        padding: 6px 14px;
        border-radius: var(--radius-full);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 24px;
    }
    .hero h1 {
        font-size: 46px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }
    .hero h1 span {
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero p {
        font-size: 17px;
        color: #cbd5e1;
        margin-bottom: 32px;
        max-width: 540px;
    }
    .hero-buttons {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .hero-card {
        background: rgba(255, 255, 255, 0.07);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .hero-stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .stat-box {
        background: rgba(15, 23, 42, 0.6);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .stat-box h3 {
        font-size: 26px;
        font-weight: 800;
        color: #60a5fa;
        margin-bottom: 4px;
    }
    .stat-box p {
        font-size: 13px;
        color: #94a3b8;
        margin: 0;
    }

    /* Feature Value Props */
    .features-section {
        margin-top: -40px;
        position: relative;
        z-index: 10;
        margin-bottom: 64px;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .feature-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }
    .feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-green { background: #ecfdf5; color: #10b981; }
    .icon-purple { background: #f5f3ff; color: #7c3aed; }
    .icon-orange { background: #fff7ed; color: #ea580c; }
    .feature-text h4 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--dark);
    }
    .feature-text p {
        font-size: 13px;
        color: var(--slate-600);
        line-height: 1.4;
    }

    /* Section Header */
    .section-header {
        text-align: center;
        margin-bottom: 48px;
    }
    .section-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .section-header h2 {
        font-size: 32px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .section-header p {
        font-size: 16px;
        color: var(--slate-600);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Product Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
    }
    .product-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
        border-color: #cbd5e1;
    }
    .product-img-wrap {
        height: 180px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 20px;
    }
    .product-img-placeholder {
        font-size: 48px;
        color: #94a3b8;
    }
    .product-sku-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(15, 23, 42, 0.85);
        color: white;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }
    .product-stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #dcfce7;
        color: #15803d;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .product-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .product-body h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .product-desc {
        font-size: 13px;
        color: var(--slate-600);
        margin-bottom: 16px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .product-price {
        font-size: 15px;
        font-weight: 800;
        color: var(--primary);
    }

    /* AI Teaser Banner */
    .ai-banner {
        background: linear-gradient(135deg, #312e81 0%, #4338ca 50%, #3b82f6 100%);
        border-radius: 24px;
        padding: 48px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.4);
        margin: 64px 0;
    }
    .ai-banner-text h3 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 12px;
    }
    .ai-banner-text p {
        font-size: 16px;
        color: #e0e7ff;
        max-width: 540px;
    }

    @media (max-width: 992px) {
        .hero-content {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .hero p {
            margin-left: auto;
            margin-right: auto;
        }
        .hero-buttons {
            justify-content: center;
        }
        .features-grid {
            grid-template-columns: 1fr 1fr;
        }
        .ai-banner {
            flex-direction: column;
            text-align: center;
            gap: 24px;
        }
    }
    @media (max-width: 600px) {
        .features-grid {
            grid-template-columns: 1fr;
        }
        .hero h1 {
            font-size: 34px;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div>
                <div class="hero-badge">
                    <i class="fa-solid fa-sparkles"></i> All-in-One E-Commerce & POS System
                </div>
                <h1>Katalog Belanja Pintar & <span>Manajemen Terintegrasi</span></h1>
                <p>
                    Nikmati kemudahan transaksi online otomatis, pembayaran instan via Xendit Payment Gateway, dan bantuan AI Assistant 24/7 untuk segala kebutuhan operasional toko Anda.
                </p>
                <div class="hero-buttons">
                    <a href="#katalog" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-bag-shopping"></i> Jelajahi Produk
                    </a>
                    @if (Auth::check() && auth()->user()->hasRole('administrator'))
                        <a href="{{ url('/admin') }}" class="btn btn-outline btn-lg" style="color: white; border-color: rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('admin.chatbot.index') }}" class="btn btn-outline btn-lg" style="color: white; border-color: rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-robot"></i> Tanya Asisten AI
                        </a>
                    @endif
                </div>
            </div>

            <!-- Hero Stats Box -->
            <div class="hero-card">
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: white; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-chart-line text-primary"></i> Ringkasan Ekosistem
                </h3>
                <div class="hero-stat-grid">
                    <div class="stat-box">
                        <h3>{{ $products->count() }}+</h3>
                        <p>Produk Siap Kirim</p>
                    </div>
                    <div class="stat-box">
                        <h3>100%</h3>
                        <p>Pembayaran Otomatis</p>
                    </div>
                    <div class="stat-box">
                        <h3>24/7</h3>
                        <p>AI Support Online</p>
                    </div>
                    <div class="stat-box">
                        <h3>Multi</h3>
                        <p>Channel POS & SCM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Value Props -->
<section class="features-section" id="keunggulan">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon icon-blue">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="feature-text">
                    <h4>Pengiriman Cepat</h4>
                    <p>Integrasi surat jalan & tracking pesanan otomatis ke seluruh wilayah.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-green">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="feature-text">
                    <h4>Xendit Gateway</h4>
                    <p>Mendukung QRIS, Virtual Account, & E-Wallet dengan konfirmasi instan.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-purple">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="feature-text">
                    <h4>Asisten AI Cerdas</h4>
                    <p>Konsultasi produk dan rekomendasi belanja real-time bertenaga AI.</p>
                </div>
            </div>
            <div class="feature-card">
                <div class="feature-icon icon-orange">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div class="feature-text">
                    <h4>Jaminan Kualitas</h4>
                    <p>Produk bergaransi resmi dengan kemudahan retur pembelian.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Showcase -->
<section class="container" id="katalog" style="padding-top: 40px;">
    <div class="section-header">
        <span class="section-badge">Koleksi Pilihan</span>
        <h2>Katalog Produk Unggulan</h2>
        <p>Temukan berbagai produk berkualitas terbaik dengan harga bersaing dan jaminan stok terpercaya.</p>
    </div>

    <div class="products-grid">
        @forelse($products as $product)
            <div class="product-card">
                <div class="product-img-wrap">
                    <span class="product-sku-badge">{{ $product->sku }}</span>
                    <span class="product-stock-badge"><i class="fa-solid fa-check"></i> Tersedia</span>
                    <i class="fa-solid fa-box-open product-img-placeholder"></i>
                </div>
                <div class="product-body">
                    <h3>{{ $product->nama_barang }}</h3>
                    <p class="product-desc">{{ $product->keterangan ?: 'Produk berkualitas tinggi dengan spesifikasi standar industri terpercaya.' }}</p>
                    <div class="product-footer">
                        <span class="product-price">SKU: {{ $product->sku }}</span>
                        <a href="{{ route('admin.purchase.orders.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-cart-plus"></i> Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: white; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-solid fa-box-archive" style="font-size: 48px; color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 8px;">Belum Ada Produk Ditampilkan</h3>
                <p style="color: var(--slate-600); font-size: 14px; margin-bottom: 20px;">Silakan login ke dashboard untuk menambahkan data Master Barang.</p>
                <a href="{{ route('admin.master.barang.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Tambah Master Barang
                </a>
            </div>
        @endforelse
    </div>

    <!-- AI Promo Banner -->
    <div class="ai-banner">
        <div class="ai-banner-text">
            <h3><i class="fa-solid fa-wand-magic-sparkles"></i> Butuh Rekomendasi Produk atau Bantuan?</h3>
            <p>Tanyakan langsung pada AI Assistant kami untuk mengecek ketersediaan barang, simulasi harga, atau panduan pesanan.</p>
        </div>
        <div>
            <a href="{{ route('admin.chatbot.index') }}" class="btn btn-secondary btn-lg" style="background: white; color: #312e81; font-weight: 800;">
                <i class="fa-solid fa-comments"></i> Chat Sekarang
            </a>
        </div>
    </div>
</section>
@endsection
