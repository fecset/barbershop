<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <link rel="stylesheet" href="{{ asset('admin-panel/css/auth.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin-panel/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('admin-panel/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('admin-panel/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin-panel/img/favicon/site.webmanifest') }}">


    <script defer src="{{ asset('admin-panel/js/auth.js') }}"></script>
</head>

<body class="page">

<header class="header">
    <div class="header__top">
        <img class="header__icon" src="{{ asset('admin-panel/img/logo.svg')}}" alt="logo">
        <h1 class="header__title">Админ-панель "Бородатый Гуру"</h1>
    </div>
</header>

<main class="main">
    <div class="auth_header">
        <h2 class="auth__title">Авторизация</h2>
    </div>
    <div class="auth">

        <form class="auth_form" id="authForm" method="POST" action="{{ route('admin.login') }}">
            @csrf
            @isset($_GET['auth'])
                <div class="invalid-alert">Неверный логин или пароль!</div>
            @endisset
            <div class="auth_item">
                <img src="{{ asset('admin-panel/img/login.svg') }}" alt="login" class="auth_icon">
                <input type="text" name="login" id="login" placeholder="Логин" aria-describedby="validationLogin">
            </div>
            @error('login')
            <div id="validationLogin" class="invalid-feedback">
                {{  $message }}
            </div>
            @enderror

            <div class="auth_item">
                <img src="{{ asset('admin-panel/img/password.svg') }}" alt="password" class="auth_icon">
                <input type="password" name="password" id="password" placeholder="Пароль" aria-describedby="validationPassword">
            </div>
            @error('password')
            <div id="validationPassword" class="invalid-feedback">
                {{  $message }}
            </div>
            @enderror


            <button type="submit" class="auth_button">Авторизоваться</button>
        </form>
    </div>
</main>

</body>

</html>
