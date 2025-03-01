<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Авторизация | Бородатый Гуру</title>
    <meta name="description" content="Войдите в личный кабинет барбершопа «Бородатый Гуру» для управления своими записями, просмотра истории посещений и бронирования услуг.">
    <meta name="keywords" content="авторизация, вход, барбершоп, личный кабинет, записи, услуги, бронирование, Москва">
    <meta name="author" content="Бородатый Гуру">

    <meta property="og:title" content="Авторизация | Бородатый Гуру">
    <meta property="og:description" content="Войдите в личный кабинет барбершопа «Бородатый Гуру» для управления своими записями, просмотра истории посещений и бронирования услуг.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="google-site-verification" content="SjTH18c0vMrO1PjwzfqLQuZOWxFTm_k60U3YIFF0uB4">
    <meta name="yandex-verification" content="c9a4e132d5f4950c">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
<header class="header">
    <div class="header__container ">
        <div class="header__logo">
            <img src="{{ asset('img/Logo.svg') }}" alt="Иконка ножниц" class="header__icon">
            <a class="header__title" href="{{ route('home') }}" style="text-decoration: none; font-weight: bold">Бородатый Гуру</a>

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
        @include('components.input', ['attributeName' => 'email', 'attributeType' => 'email', 'attributeTitle' => 'E-mail'])
        @include('components.input', ['attributeName' => 'password', 'attributeType' => 'password', 'attributeTitle' => 'Пароль'])
        <button type="submit" class="submit-button">Авторизоваться</button>
    </form>
</div>

</body>
</html>
