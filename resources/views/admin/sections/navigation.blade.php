<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ route('admin.dashboard') }}" class="site_title">
                <i class="fa fa-shopping-bag"></i> <span>{{ config('app.name', 'UsahaKu') }}</span>
            </a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ auth()->user()->avatar }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>{{ __('views.backend.section.navigation.greeting') }},</span>
                <h2>{{ auth()->user()->name }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Menu Transaksi</h3>
                <ul class="nav side-menu">
                    <li class="{{ request()->is('admin') ? 'current-page' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/chatbot*') ? 'current-page' : '' }}">
                        <a href="{{ route('admin.chatbot.index') }}">
                            <i class="fa fa-comments-o" aria-hidden="true"></i>
                            AI Assistant
                        </a>
                    </li>

                    <!-- Pembelian (Purchase) -->
                    @php $isPurchaseActive = request()->is('admin/purchase*'); @endphp
                    <li class="dropdown-item-menu {{ $isPurchaseActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            Pembelian (Purchase)
                            <span class="fa fa-chevron-down pull-right" style="margin-top: 4px;"></span>
                        </a>
                        <ul class="nav child_menu" style="{{ $isPurchaseActive ? 'display: block;' : 'display: none;' }}">
                            <li class="{{ request()->is('admin/purchase/requests*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.requests.index') }}">
                                    Permintaan Pembelian
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/purchase/orders*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.orders.index') }}">
                                    Purchase Order (PO)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/purchase/receipts*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.receipts.index') }}">
                                    Penerimaan Barang
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/purchase/invoices*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.invoices.index') }}">
                                    Tagihan Pembelian (Invoice)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/purchase/payments*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.payments.index') }}">
                                    Pembayaran Vendor
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/purchase/returns*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.purchase.returns.index') }}">
                                    Retur Pembelian
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Penjualan (Sales) -->
                    @php $isSalesActive = request()->is('admin/sales*') || request()->is('admin/xendit*'); @endphp
                    <li class="dropdown-item-menu {{ $isSalesActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-line-chart" aria-hidden="true"></i>
                            Penjualan (Sales)
                            <span class="fa fa-chevron-down pull-right" style="margin-top: 4px;"></span>
                        </a>
                        <ul class="nav child_menu" style="{{ $isSalesActive ? 'display: block;' : 'display: none;' }}">
                            <li class="{{ request()->is('admin/sales/orders*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.sales.orders.index') }}">
                                    Sales Order (SO)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/sales/shipments*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.sales.shipments.index') }}">
                                    Pengiriman Barang
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/sales/invoices*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.sales.invoices.index') }}">
                                    Tagihan Penjualan (Invoice)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/sales/payments*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.sales.payments.index') }}">
                                    Pembayaran Pelanggan
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/sales/returns*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.sales.returns.index') }}">
                                    Retur Penjualan
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/xendit*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.xendit.index') }}">
                                    Pembayaran Xendit (Online)
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Master Data -->
                    @php $isMasterActive = request()->is('admin/master*') || request()->is('admin/categories*') || request()->is('admin/products*'); @endphp
                    <li class="dropdown-item-menu {{ $isMasterActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-database" aria-hidden="true"></i>
                            Master Data
                            <span class="fa fa-chevron-down pull-right" style="margin-top: 4px;"></span>
                        </a>
                        <ul class="nav child_menu" style="{{ $isMasterActive ? 'display: block;' : 'display: none;' }}">
                            <li class="{{ request()->is('admin/categories*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.categories.index') }}">
                                    Kategori Produk
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/products*') || request()->is('admin/master/barang*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.products.index') }}">
                                    Data Barang / Produk
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/master/vendor*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.master.vendor.index') }}">
                                    Data Vendor (Supplier)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/master/pelanggan*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.master.pelanggan.index') }}">
                                    Data Pelanggan (Customer)
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/master/pengguna*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.master.pengguna.index') }}">
                                    Data Staf / Karyawan
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="menu_section">
                <h3>Sistem & Pengaturan</h3>
                <ul class="nav side-menu">
                    <!-- User Management -->
                    @php $isUserActive = request()->is('admin/users*') || request()->is('admin/permissions*'); @endphp
                    <li class="dropdown-item-menu {{ $isUserActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            User Management
                            <span class="fa fa-chevron-down pull-right" style="margin-top: 4px;"></span>
                        </a>
                        <ul class="nav child_menu" style="{{ $isUserActive ? 'display: block;' : 'display: none;' }}">
                            <li class="{{ request()->is('admin/users') && !request()->is('admin/users/restore') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.users') }}">
                                    Daftar Pengguna
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/users/restore*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.users.restore') }}">
                                    Restore Pengguna
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/permissions*') ? 'current-page' : '' }}">
                                <a href="{{ route('admin.permissions') }}">
                                    Hak Akses (Permissions)
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- System Logs -->
                    @php $isLogActive = request()->is('admin/log-viewer*'); @endphp
                    <li class="dropdown-item-menu {{ $isLogActive ? 'active' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            System Logs
                            <span class="fa fa-chevron-down pull-right" style="margin-top: 4px;"></span>
                        </a>
                        <ul class="nav child_menu" style="{{ $isLogActive ? 'display: block;' : 'display: none;' }}">
                            <li class="{{ request()->is('admin/log-viewer') ? 'current-page' : '' }}">
                                <a href="{{ route('log-viewer::dashboard') }}">
                                    Log Dashboard
                                </a>
                            </li>
                            <li class="{{ request()->is('admin/log-viewer/logs*') ? 'current-page' : '' }}">
                                <a href="{{ route('log-viewer::logs.list') }}">
                                    Daftar Logs
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
