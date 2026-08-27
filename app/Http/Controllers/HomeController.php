<?php

namespace App\Http\Controllers;

use App\Models\Master\Barang;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the e-commerce storefront landing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Barang::where('status', 1)->latest('created_at')->take(12)->get();
        return view('welcome', compact('products'));
    }
}
