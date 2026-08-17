<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/image/icon_univ_bsi.png') }}">
    <title>Login Administrator - Toko Online Makanan</title>

    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Font Awesome -->
    <link href="{{ asset('backend/dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background: radial-gradient(circle at top right, #064e3b 0%, #0f172a 60%, #020617 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        .login-card-modern {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 440px;
            padding: 40px 36px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-brand-icon {
            width: 56px;
            height: 56px;
            background: #ecfdf5;
            color: #059669;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 14px;
            box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.2);
        }
        .login-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px;
            letter-spacing: -0.02em;
        }
        .login-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin: 0;
        }
        .form-label-modern {
            font-weight: 700;
            font-size: 13px;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }
        .input-group-modern {
            position: relative;
            margin-bottom: 18px;
        }
        .input-group-modern i {
            position: absolute;
            left: 14px;
            top: 14px;
            color: #94a3b8;
            font-size: 15px;
            z-index: 5;
        }
        .form-control-modern {
            width: 100%;
            padding: 12px 14px 12px 42px !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 10px !important;
            font-size: 14px !important;
            font-family: inherit !important;
            color: #0f172a !important;
            transition: all 0.2s ease !important;
            box-sizing: border-box;
        }
        .form-control-modern:focus {
            outline: none !important;
            border-color: #059669 !important;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
        }
        .btn-login-modern {
            width: 100%;
            padding: 13px;
            background: #059669;
            border: 1px solid #059669;
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login-modern:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 14px 20px -3px rgba(5, 150, 105, 0.4);
        }
        .alert-login {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <div class="login-card-modern">
        <div class="login-brand">
            <div class="login-brand-icon">
                <i class="fa fa-cutlery"></i>
            </div>
            <h2 class="login-title">Administrator Portal</h2>
            <p class="login-subtitle">Masuk untuk mengelola produk, pesanan, dan laporan toko</p>
        </div>

        @if(session()->has('error'))
            <div class="alert-login">
                <i class="fa fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('backend.login.post') }}" method="post">
            @csrf
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label-modern">Alamat Email</label>
                <div class="input-group-modern">
                    <i class="fa fa-envelope-o"></i>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control-modern" placeholder="admin@gmail.com" required autocomplete="email" autofocus>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label-modern">Password</label>
                <div class="input-group-modern">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" class="form-control-modern" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-login-modern">
                <span>Masuk ke Dashboard</span>
                <i class="fa fa-arrow-right"></i>
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 12.5px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 16px;">
            &copy; {{ date('Y') }} Toko Online Makanan Nusantara
        </div>
    </div>
</body>

</html>