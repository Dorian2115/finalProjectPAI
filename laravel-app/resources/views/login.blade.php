<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div>
        <div class="card">
            <h1>Logowanie</h1>
            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="Email" 
                        required 
                        maxlength="255"
                        pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                        title="Wprowadź poprawny adres email"
                        value="{{ old('email') }}">
                </div>
                
                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Hasło" 
                        required
                        minlength="8"
                        maxlength="255">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
            </form>
            
            @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <strong>Wystąpiły błędy:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="mt-3" style="text-align: center;">
                <p>Nie masz konta? <a href="{{ route('register') }}" style="color: #ef3b2d; text-decoration: none; font-weight: 600;">Zarejestruj się</a></p>
            </div>
        </div>
    </div>
</body>
</html>
