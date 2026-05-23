<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Housekeeping — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            color: white;
            padding: 35px 30px 25px;
            text-align: center;
        }

        .login-header i {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }

        .login-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 13px;
            opacity: 0.7;
            margin: 0;
        }

        .login-body {
            padding: 30px;
        }

        .form-control:focus {
            border-color: #0f3460;
            box-shadow: 0 0 0 0.2rem rgba(15, 52, 96, 0.2);
        }

        .btn-login {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px;
            border-radius: 8px;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }

        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            border-left: none;
        }

        .login-footer {
            text-align: center;
            padding: 15px 30px;
            background: #f8f9fa;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Header --}}
    <div class="login-header">
        <i class="bi bi-building"></i>
        <h4>HotelKeep</h4>
        <p>Housekeeping Management System</p>
    </div>

    {{-- Body --}}
    <div class="login-body">
        <h6 class="fw-semibold mb-4 text-center text-muted">Sign in to your account</h6>

        {{-- Session Status --}}
        @if(session('status'))
            <div class="alert alert-success alert-sm mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="you@hotel.com"
                           required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="remember" id="remember">
                    <label class="form-check-label small" for="remember">
                        Remember me
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <div class="login-footer">
        <i class="bi bi-shield-check"></i>
        Staff access only &mdash; contact Admin for account issues
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>