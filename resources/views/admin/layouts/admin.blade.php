@extends('layouts.app')

@section('body_class', 'nav-md')

@section('page')
    <div class="container body">
        <div class="main_container">
            @include('admin.sections.navigation')
            @include('admin.sections.header')

            <div class="right_col" role="main">
                <div class="page-title">
                    <div class="title_left">
                        <h1 class="h3">@yield('title')</h1>
                    </div>
                </div>
                <div class="clearfix"></div>

                {{-- Alert Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade in" role="alert" style="border-radius: 8px; margin-bottom: 20px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <i class="fa fa-check-circle" style="font-size: 16px; margin-right: 6px;"></i> <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" style="border-radius: 8px; margin-bottom: 20px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <i class="fa fa-exclamation-triangle" style="font-size: 16px; margin-right: 6px;"></i> <strong>Gagal!</strong> {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>

            <footer>
                <div class="pull-right">
                    &copy; {{ date('Y') }} ERP POS & E-Commerce System
                </div>
                <div class="clearfix"></div>
            </footer>
        </div>
    </div>
@endsection

@section('styles')
    {{ Html::style(mix('assets/admin/css/admin.css')) }}
    <style>
        #sidebar-menu .side-menu > li.active > a .fa-chevron-down {
            transform: rotate(180deg);
            transition: transform 0.2s ease;
        }
        #sidebar-menu .side-menu > li > a .fa-chevron-down {
            transition: transform 0.2s ease;
        }
        .child_menu li.current-page a,
        .child_menu li a:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
            font-weight: 600;
        }
        .field-feedback-live {
            transition: all 0.2s ease;
        }
        /* Fix action buttons alignment in all tables */
        table td form {
            display: inline-block !important;
            margin: 0 !important;
            padding: 0 !important;
            vertical-align: middle;
        }
        table td .btn {
            margin-bottom: 0 !important;
            vertical-align: middle;
        }
    </style>
@endsection

@section('scripts')
    {{ Html::script(mix('assets/admin/js/admin.js')) }}
    <script>
        $(document).ready(function() {
            // 1. Matikan semua listener lama agar tidak terjadi konflik sidebar dropdown
            $('#sidebar-menu a').off('click');

            // Handler klik sidebar dropdown
            $('#sidebar-menu').on('click', '.dropdown-item-menu > a', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $li = $(this).parent('li');
                var $childMenu = $li.children('.child_menu');

                if ($li.hasClass('active')) {
                    $li.removeClass('active');
                    $childMenu.slideUp(200);
                } else {
                    $li.addClass('active');
                    $childMenu.slideDown(200);
                }
            });

            // 2. UNIVERSAL REAL-TIME FORM VALIDATOR (Untuk Seluruh Form di Aplikasi)
            function validateField($input) {
                // Abaikan tombol, field hidden, atau disabled
                if ($input.is(':disabled') || $input.is(':hidden') || $input.attr('type') === 'hidden' || $input.attr('type') === 'submit' || $input.attr('type') === 'button') {
                    return true;
                }

                var val = $.trim($input.val());
                var isRequired = $input.prop('required') || $input.hasClass('required') || $input.attr('required') !== undefined;
                var type = $input.attr('type');
                var tagName = $input.prop('tagName').toLowerCase();
                var name = $input.attr('name') || '';

                // Cari atau buat wadah teks umpan balik (feedback)
                var $parent = $input.closest('.col-md-6, .col-sm-6, .col-xs-12, .form-group');
                var $feedback = $parent.find('.field-feedback-live');
                if ($feedback.length === 0) {
                    $feedback = $('<div class="field-feedback-live" style="margin-top: 4px; font-size: 12px;"></div>');
                    if ($input.closest('.input-group').length > 0) {
                        $input.closest('.input-group').after($feedback);
                    } else {
                        $input.after($feedback);
                    }
                }

                // A. Pengecekan Field Wajib (Required)
                if (isRequired && val === '') {
                    $input.addClass('parsley-error').css('border-color', '#d9534f');
                    $feedback.html('<span class="text-danger"><i class="fa fa-times-circle"></i> Field ini wajib diisi.</span>');
                    return false;
                }

                // B. Pengecekan Select Dropdown
                if (tagName === 'select' && isRequired && (val === '' || val === null)) {
                    $input.addClass('parsley-error').css('border-color', '#d9534f');
                    $feedback.html('<span class="text-danger"><i class="fa fa-times-circle"></i> Silakan pilih salah satu opsi.</span>');
                    return false;
                }

                // C. Pengecekan Format Email
                if (type === 'email' && val !== '') {
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if (!emailReg.test(val)) {
                        $input.addClass('parsley-error').css('border-color', '#d9534f');
                        $feedback.html('<span class="text-danger"><i class="fa fa-times-circle"></i> Format email tidak valid.</span>');
                        return false;
                    }
                }

                // D. Pengecekan Angka / Kuantitas / Harga
                if ((type === 'number' || $input.hasClass('number') || name.indexOf('kuantitas') !== -1 || name.indexOf('harga') !== -1 || name.indexOf('jumlah') !== -1) && val !== '') {
                    var num = parseFloat(val);
                    if (isNaN(num) || num < 0) {
                        $input.addClass('parsley-error').css('border-color', '#d9534f');
                        $feedback.html('<span class="text-danger"><i class="fa fa-times-circle"></i> Angka harus bernilai 0 atau lebih.</span>');
                        return false;
                    }
                }

                // Jika semua lolos validasi
                $input.removeClass('parsley-error').css('border-color', '#26B99A');
                if (val !== '' && isRequired) {
                    $feedback.html('<span class="text-success" style="color: #26B99A;"><i class="fa fa-check-circle"></i> Valid.</span>');
                } else if (val === '' && !isRequired) {
                    $feedback.html('');
                }
                return true;
            }

            // Pasang event BLUR, CHANGE, dan INPUT secara real-time pada seluruh form
            $(document).on('blur change input', 'form input, form select, form textarea', function() {
                validateField($(this));
            });
        });
    </script>
@endsection