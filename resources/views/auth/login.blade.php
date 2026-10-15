<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Kendaraan App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2440 0%, #1e3a5f 50%, #0d9488 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,.97);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%; max-width: 420px;
            box-shadow: 0 32px 80px rgba(0,0,0,.3);
        }
        .brand-logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #1e3a5f, #0d9488);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: #fff;
            margin: 0 auto 20px;
        }
        .form-control {
            border-radius: 10px;
            border-color: #e2e8f0;
            padding: 12px 16px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13,148,136,.15);
        }
        .btn-login {
            background: linear-gradient(135deg, #1e3a5f, #0d9488);
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 15px; font-weight: 600;
            letter-spacing: .3px;
            transition: opacity .2s, transform .2s;
        }
        .btn-login:hover { opacity: .9; transform: translateY(-1px); }
        .input-group-text { border-radius: 10px 0 0 10px; background: #f8fafc; border-color: #e2e8f0; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-logo"><i class="bi bi-truck"></i></div>
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color:#1e3a5f;">Kendaraan App</h4>
            <p class="text-muted" style="font-size:13px;">Sistem Pemesanan & Monitoring Kendaraan</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger" style="border-radius:10px;font-size:13px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:13px;">Ingat saya</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="text-center mt-4 p-3" style="background:#f8fafc;border-radius:10px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.8px;font-weight:600;color:#64748b;margin-bottom:8px;">Demo Login</div>
            <div style="font-size:12px;color:#475569;">
                <strong>Admin:</strong> admin@kendaraan.com / admin123<br>
                <strong>Approver L1:</strong> approver1@kendaraan.com / approver123<br>
                <strong>Approver L2:</strong> approver2@kendaraan.com / approver123
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
