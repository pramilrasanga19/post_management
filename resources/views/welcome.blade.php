<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">

    @vite('resources/css/welcome.css')

</head>
<body>
    <div class="container">
        <h1>Welcome to Post Manager</h1>

        <div class="button-group">
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-register">Register</a>
            @endif
        </div>
    </div>
</body>
</html>
