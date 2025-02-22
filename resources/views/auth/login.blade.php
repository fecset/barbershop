<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<header class="header">
    <div class="header__container ">
        <div class="header__logo">
            <img src="{{ asset('img/Logo.svg') }}" alt="Иконка ножниц" class="header__icon">
            <h1 class="header__title">Бородатый Гуру</h1>
        </div>

        <nav class="header__nav">
            <a class="header__nav-link" href="{{ route('home') }}">Главная</a>
            <a class="header__nav-link" href="{{ route('register') }}">Регистрация</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2 style="text-align: center">Авторизация</h2>
    @isset($_GET['auth'])
        <div class="invalid-alert">Неверный логин или пароль!</div>
    @endisset
    <form method="POST" action="{{route('login')}}">
        @csrf
        @include('components.Input', ['attributeName' => 'email', 'attributeType' => 'email', 'attributeTitle' => 'E-mail'])
        @include('components.Input', ['attributeName' => 'password', 'attributeType' => 'password', 'attributeTitle' => 'Пароль'])
        <button type="submit" class="submit-button">Авторизоваться</button>
    </form>
</div>

</body>
</html>
