<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WaSender Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <i class="fab fa-whatsapp" style="font-size: 3rem; color: var(--primary);"></i>
            <h2 style="margin-top: 1rem;">WaSender Admin</h2>
        </div>

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('password')
                    <span class="text-danger" style="font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="d-flex align-center">
                    <input type="checkbox" name="remember" style="margin-right: 0.5rem;">
                    <span style="font-size: 0.9rem; color: var(--muted);">Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>
</div>

</body>
</html>
