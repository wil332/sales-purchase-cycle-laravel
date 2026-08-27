<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UsahaKu Store') }} - Toko Online & POS Terpercaya</title>

    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --secondary: #10b981;
            --dark: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-400: #94a3b8;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-full: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--slate-50);
            color: var(--dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Top Announcement Bar */
        .topbar {
            background: linear-gradient(90deg, #1e293b, #0f172a);
            color: #94a3b8;
            font-size: 13px;
            padding: 8px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar-badge {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 600;
        }

        /* Navbar Header */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.5px;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
            font-weight: 600;
            font-size: 15px;
            color: var(--slate-600);
        }
        .nav-links a:hover {
            color: var(--primary);
        }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
        }
        .btn-outline {
            background: transparent;
            color: var(--slate-800);
            border: 1px solid #cbd5e1;
        }
        .btn-outline:hover {
            background: var(--slate-100);
            border-color: #94a3b8;
        }
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        .btn-secondary:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        .btn-sm {
            padding: 6px 14px;
            font-size: 13px;
        }
        .btn-lg {
            padding: 14px 28px;
            font-size: 16px;
        }

        /* Layout Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Footer */
        .site-footer {
            background: #0b1120;
            color: #94a3b8;
            padding: 64px 0 24px 0;
            border-top: 1px solid #1e293b;
            margin-top: 80px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer-col h4 {
            color: white;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .footer-col ul {
            list-style: none;
        }
        .footer-col ul li {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .footer-col ul li a:hover {
            color: white;
        }
        .footer-bottom {
            padding-top: 24px;
            border-top: 1px solid #1e293b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .payment-badges {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .payment-badge {
            background: #1e293b;
            color: #cbd5e1;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Floating AI Assistant Button */
        .ai-floating-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 14px 22px;
            border-radius: var(--radius-full);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 14px;
            z-index: 999;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .ai-floating-btn:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 16px 30px rgba(124, 58, 237, 0.5);
            color: white;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 16px;
            }
            .nav-links {
                display: none;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }
            .footer-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- Announcement Bar -->
    <div class="topbar">
        <div class="topbar-left">
            <span class="topbar-badge"><i class="fa-solid fa-bolt"></i> PROMO</span>
            <span>Gratis Ongkir ke Seluruh Indonesia & Garansi Resmi!</span>
        </div>
        <div class="topbar-right">
            <span><i class="fa-solid fa-phone"></i> CS: 0812-3456-7890</span>
            <span>|</span>
            <span><i class="fa-solid fa-shield-halved"></i> Pembayaran Aman by Xendit</span>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <nav class="navbar">
        <a href="{{ url('/') }}" class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <span>{{ config('app.name', 'UsahaKu') }}</span>
        </a>

        <ul class="nav-links">
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="#katalog">Katalog Produk</a></li>
            <li><a href="#keunggulan">Kenapa Kami?</a></li>
            <li><a href="{{ route('admin.chatbot.index') }}"><i class="fa-solid fa-robot text-primary"></i> Tanya AI</a></li>
        </ul>

        <div class="nav-actions">
            @if (Route::has('login'))
                @if (!Auth::check())
                    <a href="{{ url('/login') }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk
                    </a>
                    @if(config('auth.users.registration'))
                        <a href="{{ url('/register') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-user-plus"></i> Daftar
                        </a>
                    @endif
                @else
                    @if(auth()->user()->hasRole('administrator'))
                        <a href="{{ url('/admin') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('protection.membership') }}" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-user"></i> Akun Saya
                        </a>
                    @endif
                    <a href="{{ url('/logout') }}" class="btn btn-outline btn-sm" title="Keluar">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                @endif
            @endif
        </div>
    </nav>

    <!-- Page Body Content -->
    @yield('content')

    <!-- Floating AI Widget -->
    <a href="{{ route('admin.chatbot.index') }}" class="ai-floating-btn">
        <i class="fa-solid fa-wand-magic-sparkles"></i>
        <span>AI Shopping Assistant</span>
    </a>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="brand" style="color: white; margin-bottom: 16px;">
                        <div class="brand-icon">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <span>{{ config('app.name', 'UsahaKu') }}</span>
                    </div>
                    <p style="font-size: 14px; color: #94a3b8; line-height: 1.7;">
                        Platform e-commerce dan manajemen rantai pasok cerdas terintegrasi POS, sistem pergudangan, pembayaran instan otomatis via Xendit, dan asisten AI pintar.
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="#katalog">Katalog Produk</a></li>
                        <li><a href="#keunggulan">Keunggulan Toko</a></li>
                        <li><a href="{{ route('admin.chatbot.index') }}">Konsultasi AI</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Akun & Layanan</h4>
                    <ul>
                        <li><a href="{{ url('/login') }}">Masuk Akun</a></li>
                        <li><a href="{{ url('/register') }}">Registrasi Baru</a></li>
                        <li><a href="{{ url('/admin') }}">Panel Manajemen (Admin)</a></li>
                        <li><a href="{{ route('admin.xendit.index') }}">Portal Pembayaran</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Metode Pembayaran</h4>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px;">Didukung oleh Xendit Payment Gateway terlisensi Bank Indonesia:</p>
                    <div class="payment-badges">
                        <span class="payment-badge"><i class="fa-solid fa-qrcode"></i> QRIS</span>
                        <span class="payment-badge"><i class="fa-solid fa-credit-card"></i> Virtual Account</span>
                        <span class="payment-badge"><i class="fa-solid fa-wallet"></i> E-Wallet</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>
                    &copy; {{ date('Y') }} <strong>{{ config('app.name', 'UsahaKu') }}</strong>. All rights reserved.
                </div>
                <div>
                    Built with Laravel & AI POS System
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
