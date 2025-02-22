<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>

    <link rel="stylesheet" href="{{ asset('admin-panel/css/auth.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin-panel/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin-panel/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin-panel/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin-panel/img/favicon/site.webmanifest') }}">


    <script defer src="{{ asset('admin-panel/js/auth.js') }}"></script>
</head>

<body class="page">
<script>
    if (localStorage.getItem('isLoggedIn')) {
        window.location.href = "/admin-panel/dashboard";
    }
</script>
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
        <form class="auth_form" id="authForm">
            <div class="auth_item">
                <img src="{{ asset('admin-panel/img/login.svg') }}" alt="login" class="auth_icon">
                <input type="text" id="username" placeholder="Логин" required>
            </div>
            <div class="auth_item">
                <img src="{{ asset('admin-panel/img/password.svg') }}" alt="password" class="auth_icon">
                <input type="password" id="password" placeholder="Пароль" required>
            </div>
            <div class="error" id="authError"></div>
            <button type="submit" class="auth_button">Авторизоваться</button>
        </form>
    </div>
</main>

</body>

</html>
