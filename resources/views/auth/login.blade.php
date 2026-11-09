<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Connexion - PokeWorld Admin</title>
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-body d-flex align-items-center justify-content-center min-vh-100 p-3">

<div class="card border-0 shadow-sm login-card w-100">
    <div class="card-body p-4 p-md-5">
        <a href="{{ route('home') }}" class="d-block text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="PokeWorld" style="max-height: 70px;">
        </a>
        <h1 class="h4 fw-bold text-center mb-4">Connexion à l'administration</h1>

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-1"></i> Se connecter</button>
        </form>

        <p class="text-center mt-4 mb-0"><a href="{{ route('home') }}" class="text-muted small">&larr; Retour au site</a></p>
    </div>
</div>

</body>
</html>
