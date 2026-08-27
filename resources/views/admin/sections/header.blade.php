<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <!-- User Profile Dropdown -->
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar }}" alt="">
                        <span>{{ auth()->user()->name }}</span>
                        <span class="fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li style="padding: 10px 20px; border-bottom: 1px solid #eee;">
                            <small class="text-muted">Masuk sebagai:</small>
                            <div style="font-weight: bold; color: #333;">{{ auth()->user()->email }}</div>
                        </li>
                        <li>
                            <a href="{{ route('protection.membership') }}">
                                <i class="fa fa-user pull-right"></i> Profil Akun
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/') }}" target="_blank">
                                <i class="fa fa-shopping-bag pull-right"></i> Toko Online (Katalog)
                            </a>
                        </li>
                        <li class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="{{ route('logout') }}" style="color: #d9534f;">
                                <i class="fa fa-sign-out pull-right"></i> Keluar (Logout)
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Notifications Dropdown -->
                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge bg-green">3</span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                            <a>
                                <span class="image"><i class="fa fa-shopping-bag text-primary" style="font-size: 20px;"></i></span>
                                <span>
                                    <strong>Pesanan Masuk</strong>
                                    <span class="time">Baru saja</span>
                                </span>
                                <span class="message">
                                    Pesanan Penjualan baru siap diproses di modul Sales.
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><i class="fa fa-credit-card text-success" style="font-size: 20px;"></i></span>
                                <span>
                                    <strong>Xendit Payment</strong>
                                    <span class="time">10 mnt lalu</span>
                                </span>
                                <span class="message">
                                    Pembayaran digital invoice berhasil diverifikasi otomatis.
                                </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><i class="fa fa-comments text-info" style="font-size: 20px;"></i></span>
                                <span>
                                    <strong>AI Assistant</strong>
                                    <span class="time">Aktif</span>
                                </span>
                                <span class="message">
                                    Gemini AI Assistant siap membantu manajemen inventori.
                                </span>
                            </a>
                        </li>
                        <li>
                            <div class="text-center">
                                <a href="{{ route('admin.dashboard') }}">
                                    <strong>Lihat Semua Aktivitas</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- Quick Link to Storefront -->
                <li class="hidden-xs">
                    <a href="{{ url('/') }}" target="_blank" title="Buka Halaman Depan Toko">
                        <i class="fa fa-external-link"></i> <span style="font-weight: 600;">Lihat Toko</span>
                    </a>
                </li>

                <!-- AI Quick Link -->
                <li class="hidden-xs">
                    <a href="{{ route('admin.chatbot.index') }}" title="Chat AI Assistant">
                        <i class="fa fa-magic text-warning"></i> <span>Tanya AI</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
