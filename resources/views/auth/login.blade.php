<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OSEM — Login</title>
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body, html {
            height: 100%;
            margin: 0;
            background: #d0ecd3;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            background-image: url('{{ asset('static/img/background/loginbackground.png') }}');            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(27, 76, 42, 0.72);
        }

        .login-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .login-left-content img {
            width: 220px;
            max-width: 70%;
        }

        .login-left-content p {
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            margin-top: 1.25rem;
            font-weight: 300;
            letter-spacing: 0.03em;
        }

        /* RIGHT SIDE */
        .login-right {
            width: 460px;
            min-width: 380px;
            background: #d0ecd3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3rem 2rem;
        }

        .login-right h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1b4c2a;
            margin-bottom: 0.25rem;
        }

        .login-right .subtitle {
            font-size: 0.875rem;
            color: #057361;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1b4c2a;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-control {
            border: 1.5px solid #a8d5b0;
            background: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            color: #1b4c2a;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #17a060;
            box-shadow: 0 0 0 3px rgba(23, 160, 96, 0.15);
            outline: none;
        }

        .form-check-input:checked {
            background-color: #17a060;
            border-color: #17a060;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #1b4c2a;
        }

        .btn-login {
            background: #17a060;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            width: 100%;
            transition: background 0.2s;
            margin-top: 1.25rem;
        }

        .btn-login:hover {
            background: #057361;
            color: #ffffff;
        }

        .forgot-link {
            font-size: 0.8rem;
            color: #057361;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: #1b4c2a;
            text-decoration: underline;
        }

        .error-box {
            background: #fde8e8;
            border: 1px solid #f5c6c6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: #b91c1c;
            margin-bottom: 1.25rem;
        }

        /* QUICK LINKS */
        .quick-links {
            margin-top: auto;
            padding-top: 2rem;
        }

        .quick-links-box {
            background: rgba(255,255,255,0.6);
            border: 1px solid #a8d5b0;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }

        .quick-links-box p {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #1b4c2a;
            margin-bottom: 0.6rem;
        }

        .quick-links-box a {
            display: block;
            font-size: 0.82rem;
            color: #057361;
            text-decoration: none;
            margin-bottom: 0.3rem;
        }

        .quick-links-box a:hover {
            color: #1b4c2a;
            text-decoration: underline;
        }

        .quick-links-box a:last-child {
            margin-bottom: 0;
        }

    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="login-left">
        <div class="login-left-content">
            <img src="{{ asset('static/img/logo/logo.png') }}" alt="OSEM Logo">            
            <p>Online Security and Enforcement Management</p>
            <p>Office of Security Management of Security</p>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="login-right">

        <div>
            <h1>Welcome back.</h1>
            <p class="subtitle">Sign in to your IIUM account to continue.</p>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @session('status')
                <div class="error-box" style="background:#e8f5e9;border-color:#a5d6a7;color:#1b4c2a;">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="email">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@iium.edu.my"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <label class="form-check d-flex align-items-center gap-2 m-0">
                        <input class="form-check-input m-0" type="checkbox" name="remember" id="remember_me">
                        <span class="form-check-label">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    Sign In
                </button>

            </form>
        </div>

        <div class="quick-links">
            <div class="quick-links-box">
                <p>Quick Help</p>
                <a href="tel:+60364215555">OSEM Hotline:+603-6421 5555</a>
                <a href="mailto:osem@iium.edu.my">Email: osem@iium.edu.my</a>
                <a href="tel:+999">MERS999:+999</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>