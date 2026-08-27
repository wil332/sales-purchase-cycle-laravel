@extends('layouts.welcome')

@section('content')
<div class="container" style="padding: 60px 24px; min-height: 70vh;">
    <div style="max-width: 680px; margin: 0 auto;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 36px;">
            <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #2563eb, #4f46e5); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 16px auto; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Area Pengguna / Member Profil</h1>
            <p style="color: #64748b; font-size: 15px;">Informasi akun dan akses sistem aplikasi {{ config('app.name', 'UsahaKu') }}.</p>
        </div>

        <!-- User Profile Card -->
        <div style="background: white; border-radius: 20px; padding: 36px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.06); margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 20px; padding-bottom: 24px; border-bottom: 1px solid #f1f5f9; margin-bottom: 24px;">
                <img src="{{ auth()->check() ? auth()->user()->avatar : 'https://www.gravatar.com/avatar/default?d=identicon' }}" alt="Profile" style="width: 64px; height: 64px; border-radius: 50%; border: 3px solid #e2e8f0;">
                <div>
                    <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">{{ auth()->check() ? auth()->user()->name : 'Tamu (Guest)' }}</h3>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">{{ auth()->check() ? auth()->user()->email : 'Belum Login' }}</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px;">
                <div style="background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #edf2f7;">
                    <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Status Akun</span>
                    <p style="font-size: 15px; font-weight: 700; color: #10b981; margin: 4px 0 0 0;">
                        <i class="fa-solid fa-circle-check"></i> Terverifikasi & Aktif
                    </p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #edf2f7;">
                    <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Peran Akses (Role)</span>
                    <p style="font-size: 15px; font-weight: 700; color: #2563eb; margin: 4px 0 0 0;">
                        <i class="fa-solid fa-id-badge"></i> {{ auth()->check() && auth()->user()->roles->count() ? auth()->user()->roles->first()->name : 'User / Member' }}
                    </p>
                </div>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                @if(auth()->check() && auth()->user()->hasRole('administrator'))
                    <a href="{{ url('/admin') }}" class="btn btn-primary" style="flex: 1;">
                        <i class="fa-solid fa-gauge-high"></i> Masuk ke Dashboard Admin
                    </a>
                @endif
                <a href="{{ url('/#katalog') }}" class="btn btn-outline" style="flex: 1;">
                    <i class="fa-solid fa-bag-shopping"></i> Belanja di Katalog Toko
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
