<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-1: #0f172a;
            --color-2: #64748b;
            --color-3: #f1f5f9;
            --color-4: #f8fafc;
            --color-5: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-4);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: var(--color-5);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .login-title {
            color: var(--color-1);
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 8px;
            text-align: center;
        }

        .login-subtitle {
            color: var(--color-2);
            font-size: 0.9rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            color: var(--color-1);
            font-size: 0.85rem;
        }

        .form-control {
            border: 1px solid var(--color-3);
            padding: 12px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--color-1);
        }

        .btn-login {
            background-color: var(--color-1);
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            border: none;
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
            color: white;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: none;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-title">Admin Login</div>
        <div class="login-subtitle">Please enter your credentials to continue</div>

        @if ($errors->any())
            <div class="alert-error">
                <ul class="mb-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="admin@example.com"
                    required value="{{ old('email') }}">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                    required>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember" style="color: var(--color-2);">
                        Remember me
                    </label>
                </div>
                <a href="#" class="text-decoration-none small"
                    style="color: var(--color-1); font-weight: 500;">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>
    </div>

</body>

</html>
