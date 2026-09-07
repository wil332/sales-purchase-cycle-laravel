<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Auth routes
 */
Route::group(['namespace' => 'Auth'], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    if (config('auth.users.registration')) {
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register');
    }

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

    // Confirmation Routes...
    if (config('auth.users.confirm_email')) {
        Route::get('confirm/{user_by_code}', 'ConfirmController@confirm')->name('confirm');
        Route::get('confirm/resend/{user_by_email}', 'ConfirmController@sendEmail')->name('confirm.send');
    }

    // Social Authentication Routes...
    Route::get('social/redirect/{provider}', 'SocialLoginController@redirect')->name('social.redirect');
    Route::get('social/login/{provider}', 'SocialLoginController@login')->name('social.login');
});

/**
 * Backend routes
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

    // Dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    //Users
    Route::get('users', 'UserController@index')->name('users');
    Route::get('users/restore', 'UserController@restore')->name('users.restore');
    Route::get('users/{id}/restore', 'UserController@restoreUser')->name('users.restore-user');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::get('users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::put('users/{user}', 'UserController@update')->name('users.update');
    Route::any('users/{id}/destroy', 'UserController@destroy')->name('users.destroy');
    Route::get('permissions', 'PermissionController@index')->name('permissions');
    Route::get('permissions/{user}/repeat', 'PermissionController@repeat')->name('permissions.repeat');
    Route::get('dashboard/log-chart', 'DashboardController@getLogChartData')->name('dashboard.log.chart');
    Route::get('dashboard/registration-chart', 'DashboardController@getRegistrationChartData')->name('dashboard.registration.chart');
    Route::get('products/data', 'product\ProductController@getProductsData')->name('products.data');
    Route::get('products', 'product\ProductController@getProductList')->name('products.index');
    Route::get('products/create', 'product\ProductController@getProductCreate')->name('products.create');
    Route::post('products', 'product\ProductController@postProductCreate')->name('products.store');
    Route::get('products/{sku}', 'product\ProductController@getProductShow')->name('products.show');
    Route::get('products/{sku}/edit', 'product\ProductController@getProductEdit')->name('products.edit');
    Route::put('products/{sku}', 'product\ProductController@putProductEdit')->name('products.update');
    Route::get('products/{sku}/destroy', 'product\ProductController@getProductDestroy')->name('products.destroy');

    // Categories CRUD
    Route::get('categories/data', 'CategoryController@getData')->name('categories.data');
    Route::get('categories/{category}/destroy', 'CategoryController@destroy')->name('categories.destroy');
    Route::resource('categories', 'CategoryController');
    Route::get(
    'chatbot',
    'ChatbotController@index'
)->name('chatbot.index');

Route::post(
    'chatbot',
    'ChatbotController@chat'
)->name('chatbot.chat');

    // Xendit Payment Gateway
    Route::group(['prefix' => 'xendit', 'as' => 'xendit.'], function () {
        Route::get('payments', 'XenditPaymentController@index')->name('index');
        Route::get('payments/create', 'XenditPaymentController@create')->name('create');
        Route::post('payments', 'XenditPaymentController@store')->name('store');
        Route::get('payments/{payment}', 'XenditPaymentController@show')->name('show');
        Route::post('payments/{payment}/refresh', 'XenditPaymentController@refresh')->name('refresh');
        Route::get(
    'payments/{payment}/poll-status',
    'XenditPaymentController@pollStatus'
)->name('poll');
    });

});

// Webhook Xendit — HARUS di luar middleware admin (dipanggil server Xendit, bukan user login)
Route::post('xendit/callback', 'Admin\XenditPaymentController@callback')->name('xendit.callback');

Route::group(['prefix' => 'admin/purchase', 'as' => 'admin.purchase.', 'namespace' => 'Admin\Purchase', 'middleware' => 'admin'], function () {

    // Purchase Requests
    Route::get('requests', 'PurchaseRequestController@index')->name('requests.index');
    Route::get('requests/create', 'PurchaseRequestController@create')->name('requests.create');
    Route::post('requests', 'PurchaseRequestController@store')->name('requests.store');
    Route::get('requests/{request}', 'PurchaseRequestController@show')->name('requests.show');
    Route::get('requests/{request}/edit', 'PurchaseRequestController@edit')->name('requests.edit');
    Route::put('requests/{request}', 'PurchaseRequestController@update')->name('requests.update');
    Route::patch('requests/{request}', 'PurchaseRequestController@update');
    Route::delete('requests/{request}', 'PurchaseRequestController@destroy')->name('requests.destroy');

    // Purchase Orders
    Route::get('orders', 'PurchaseOrderController@index')->name('orders.index');
    Route::get('orders/create', 'PurchaseOrderController@create')->name('orders.create');
    Route::post('orders', 'PurchaseOrderController@store')->name('orders.store');
    Route::get('orders/{order}', 'PurchaseOrderController@show')->name('orders.show');
    Route::get('orders/{order}/edit', 'PurchaseOrderController@edit')->name('orders.edit');
    Route::put('orders/{order}', 'PurchaseOrderController@update')->name('orders.update');
    Route::patch('orders/{order}', 'PurchaseOrderController@update');
    Route::delete('orders/{order}', 'PurchaseOrderController@destroy')->name('orders.destroy');
    Route::get('orders/create-from-request/{no_faktur}', 'PurchaseOrderController@createFromRequest')->name('orders.create-from-request');
    Route::get('orders/{order}/items-json', 'PurchaseOrderController@getOrderItemsJson')->name('orders.items-json');


    // Goods Receipts
    Route::get('receipts', 'GoodsReceiptController@index')->name('receipts.index');
    Route::get('receipts/create', 'GoodsReceiptController@create')->name('receipts.create');
    Route::post('receipts', 'GoodsReceiptController@store')->name('receipts.store');
    Route::get('receipts/{receipt}', 'GoodsReceiptController@show')->name('receipts.show');
    Route::get('receipts/{receipt}/edit', 'GoodsReceiptController@edit')->name('receipts.edit');
    Route::put('receipts/{receipt}', 'GoodsReceiptController@update')->name('receipts.update');
    Route::patch('receipts/{receipt}', 'GoodsReceiptController@update');
    Route::delete('receipts/{receipt}', 'GoodsReceiptController@destroy')->name('receipts.destroy');
    Route::get('receipts/{receipt}/items-json', 'GoodsReceiptController@getReceiptItemsJson')->name('receipts.items-json');

    // Purchase Invoices
    Route::get('invoices', 'PurchaseInvoiceController@index')->name('invoices.index');
    Route::get('invoices/create', 'PurchaseInvoiceController@create')->name('invoices.create');
    Route::post('invoices', 'PurchaseInvoiceController@store')->name('invoices.store');
    Route::get('invoices/{invoice}', 'PurchaseInvoiceController@show')->name('invoices.show');
    Route::get('invoices/{invoice}/edit', 'PurchaseInvoiceController@edit')->name('invoices.edit');
    Route::put('invoices/{invoice}', 'PurchaseInvoiceController@update')->name('invoices.update');
    Route::patch('invoices/{invoice}', 'PurchaseInvoiceController@update');
    Route::delete('invoices/{invoice}', 'PurchaseInvoiceController@destroy')->name('invoices.destroy');
    Route::get('invoices/{invoice}/amount-json', 'PurchaseInvoiceController@getInvoiceAmountJson')->name('invoices.amount-json');

    // Purchase Payments
    Route::get('payments', 'PurchasePaymentController@index')->name('payments.index');
    Route::get('payments/create', 'PurchasePaymentController@create')->name('payments.create');
    Route::post('payments', 'PurchasePaymentController@store')->name('payments.store');
    Route::get('payments/{payment}', 'PurchasePaymentController@show')->name('payments.show');
    Route::get('payments/{payment}/edit', 'PurchasePaymentController@edit')->name('payments.edit');
    Route::put('payments/{payment}', 'PurchasePaymentController@update')->name('payments.update');
    Route::patch('payments/{payment}', 'PurchasePaymentController@update');
    Route::delete('payments/{payment}', 'PurchasePaymentController@destroy')->name('payments.destroy');

    // Purchase Returns
    Route::get('returns', 'PurchaseReturnController@index')->name('returns.index');
    Route::get('returns/create', 'PurchaseReturnController@create')->name('returns.create');
    Route::post('returns', 'PurchaseReturnController@store')->name('returns.store');
    Route::get('returns/{return}', 'PurchaseReturnController@show')->name('returns.show');
    Route::get('returns/{return}/edit', 'PurchaseReturnController@edit')->name('returns.edit');
    Route::put('returns/{return}', 'PurchaseReturnController@update')->name('returns.update');
    Route::patch('returns/{return}', 'PurchaseReturnController@update');
    Route::delete('returns/{return}', 'PurchaseReturnController@destroy')->name('returns.destroy');

});

Route::group(['prefix' => 'admin/sales', 'as' => 'admin.sales.', 'namespace' => 'Admin\Sales', 'middleware' => 'admin'], function () {

    // Sales Orders
    Route::get('orders', 'SalesOrderController@index')->name('orders.index');
    Route::get('orders/create', 'SalesOrderController@create')->name('orders.create');
    Route::post('orders', 'SalesOrderController@store')->name('orders.store');
    Route::get('orders/{order}', 'SalesOrderController@show')->name('orders.show');
    Route::get('orders/{order}/edit', 'SalesOrderController@edit')->name('orders.edit');
    Route::put('orders/{order}', 'SalesOrderController@update')->name('orders.update');
    Route::patch('orders/{order}', 'SalesOrderController@update');
    Route::delete('orders/{order}', 'SalesOrderController@destroy')->name('orders.destroy');
    Route::get('orders/{order}/items-json', 'SalesOrderController@getOrderItemsJson')->name('orders.items-json');

    // Shipments
    Route::get('shipments', 'ShipmentsController@index')->name('shipments.index');
    Route::get('shipments/create', 'ShipmentsController@create')->name('shipments.create');
    Route::post('shipments', 'ShipmentsController@store')->name('shipments.store');
    Route::get('shipments/{shipment}', 'ShipmentsController@show')->name('shipments.show');
    Route::get('shipments/{shipment}/edit', 'ShipmentsController@edit')->name('shipments.edit');
    Route::put('shipments/{shipment}', 'ShipmentsController@update')->name('shipments.update');
    Route::patch('shipments/{shipment}', 'ShipmentsController@update');
    Route::delete('shipments/{shipment}', 'ShipmentsController@destroy')->name('shipments.destroy');
    Route::get('shipments/{shipment}/items-json', 'ShipmentsController@getShipmentItemsJson')->name('shipments.items-json');

    // Sales Invoices
    Route::get('invoices', 'SalesInvoiceController@index')->name('invoices.index');
    Route::get('invoices/create', 'SalesInvoiceController@create')->name('invoices.create');
    Route::post('invoices', 'SalesInvoiceController@store')->name('invoices.store');
    Route::get('invoices/{invoice}', 'SalesInvoiceController@show')->name('invoices.show');
    Route::get('invoices/{invoice}/edit', 'SalesInvoiceController@edit')->name('invoices.edit');
    Route::put('invoices/{invoice}', 'SalesInvoiceController@update')->name('invoices.update');
    Route::patch('invoices/{invoice}', 'SalesInvoiceController@update');
    Route::delete('invoices/{invoice}', 'SalesInvoiceController@destroy')->name('invoices.destroy');
    Route::get('invoices/{invoice}/amount-json', 'SalesInvoiceController@getInvoiceAmountJson')->name('invoices.amount-json');

    // Sales Payments
    Route::get('payments', 'SalesPaymentController@index')->name('payments.index');
    Route::get('payments/create', 'SalesPaymentController@create')->name('payments.create');
    Route::post('payments', 'SalesPaymentController@store')->name('payments.store');
    Route::get('payments/{payment}', 'SalesPaymentController@show')->name('payments.show');
    Route::get('payments/{payment}/edit', 'SalesPaymentController@edit')->name('payments.edit');
    Route::put('payments/{payment}', 'SalesPaymentController@update')->name('payments.update');
    Route::patch('payments/{payment}', 'SalesPaymentController@update');
    Route::delete('payments/{payment}', 'SalesPaymentController@destroy')->name('payments.destroy');

    // Sales Returns
    Route::get('returns', 'SalesReturnController@index')->name('returns.index');
    Route::get('returns/create', 'SalesReturnController@create')->name('returns.create');
    Route::post('returns', 'SalesReturnController@store')->name('returns.store');
    Route::get('returns/{return}', 'SalesReturnController@show')->name('returns.show');
    Route::get('returns/{return}/edit', 'SalesReturnController@edit')->name('returns.edit');
    Route::put('returns/{return}', 'SalesReturnController@update')->name('returns.update');
    Route::patch('returns/{return}', 'SalesReturnController@update');
    Route::delete('returns/{return}', 'SalesReturnController@destroy')->name('returns.destroy');

});

Route::group(['prefix' => 'admin/master', 'as' => 'admin.master.', 'namespace' => 'Admin\Master', 'middleware' => 'admin'], function () {

    // Barang (Unified Product & Master Barang)
    Route::get('barang/check-sku', '\App\Http\Controllers\Admin\product\ProductController@checkSku')->name('barang.check-sku');
    Route::get('barang', '\App\Http\Controllers\Admin\product\ProductController@getProductList')->name('barang.index');
    Route::get('barang/create', '\App\Http\Controllers\Admin\product\ProductController@getProductCreate')->name('barang.create');
    Route::post('barang', '\App\Http\Controllers\Admin\product\ProductController@postProductCreate')->name('barang.store');
    Route::get('barang/{barang}', '\App\Http\Controllers\Admin\product\ProductController@getProductShow')->name('barang.show');
    Route::get('barang/{barang}/edit', '\App\Http\Controllers\Admin\product\ProductController@getProductEdit')->name('barang.edit');
    Route::put('barang/{barang}', '\App\Http\Controllers\Admin\product\ProductController@putProductEdit')->name('barang.update');
    Route::patch('barang/{barang}', '\App\Http\Controllers\Admin\product\ProductController@putProductEdit');
    Route::delete('barang/{barang}', '\App\Http\Controllers\Admin\product\ProductController@getProductDestroy')->name('barang.destroy');

    // Vendor
    Route::get('vendor', 'VendorController@index')->name('vendor.index');
    Route::get('vendor/create', 'VendorController@create')->name('vendor.create');
    Route::post('vendor', 'VendorController@store')->name('vendor.store');
    Route::get('vendor/{vendor}', 'VendorController@show')->name('vendor.show');
    Route::get('vendor/{vendor}/edit', 'VendorController@edit')->name('vendor.edit');
    Route::put('vendor/{vendor}', 'VendorController@update')->name('vendor.update');
    Route::patch('vendor/{vendor}', 'VendorController@update');
    Route::delete('vendor/{vendor}', 'VendorController@destroy')->name('vendor.destroy');
    Route::get('vendor/{vendor}/barang', 'VendorController@getBarangJson')->name('vendor.barang');

    // Pengguna
    Route::get('pengguna', 'PenggunaController@index')->name('pengguna.index');
    Route::get('pengguna/create', 'PenggunaController@create')->name('pengguna.create');
    Route::post('pengguna', 'PenggunaController@store')->name('pengguna.store');
    Route::get('pengguna/{pengguna}', 'PenggunaController@show')->name('pengguna.show');
    Route::get('pengguna/{pengguna}/edit', 'PenggunaController@edit')->name('pengguna.edit');
    Route::put('pengguna/{pengguna}', 'PenggunaController@update')->name('pengguna.update');
    Route::patch('pengguna/{pengguna}', 'PenggunaController@update');
    Route::delete('pengguna/{pengguna}', 'PenggunaController@destroy')->name('pengguna.destroy');

    // Pelanggan
    Route::get('pelanggan', 'PelangganController@index')->name('pelanggan.index');
    Route::get('pelanggan/create', 'PelangganController@create')->name('pelanggan.create');
    Route::post('pelanggan', 'PelangganController@store')->name('pelanggan.store');
    Route::get('pelanggan/{pelanggan}', 'PelangganController@show')->name('pelanggan.show');
    Route::get('pelanggan/{pelanggan}/edit', 'PelangganController@edit')->name('pelanggan.edit');
    Route::put('pelanggan/{pelanggan}', 'PelangganController@update')->name('pelanggan.update');
    Route::patch('pelanggan/{pelanggan}', 'PelangganController@update');
    Route::delete('pelanggan/{pelanggan}', 'PelangganController@destroy')->name('pelanggan.destroy');

});



Route::get('/', 'HomeController@index');

/**
 * Membership
 */
Route::group(['as' => 'protection.'], function () {
    Route::get('membership', 'MembershipController@index')->name('membership')->middleware('protection:' . config('protection.membership.product_module_number') . ',protection.membership.failed');
    Route::get('membership/access-denied', 'MembershipController@failed')->name('membership.failed');
    Route::get('membership/clear-cache/', 'MembershipController@clearValidationCache')->name('membership.clear_validation_cache');
});