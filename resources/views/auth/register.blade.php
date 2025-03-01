<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Регистрация | Бородатый Гуру</title>
    <meta name="description" content="Зарегистрируйтесь в барбершопе «Бородатый Гуру» и получите доступ к онлайн-записи, управлению бронированиями и персональному кабинету.">
    <meta name="keywords" content="регистрация, барбершоп, личный кабинет, записаться, услуги, бронирование, Москва">
    <meta name="author" content="Бородатый Гуру">

    <meta property="og:title" content="Регистрация | Бородатый Гуру">
    <meta property="og:description" content="Зарегистрируйтесь в барбершопе «Бородатый Гуру» и получите доступ к онлайн-записи, управлению бронированиями и персональному кабинету.">
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
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
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
            <a class="header__nav-link" href="{{ route('login') }}">Авторизация</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2 style="text-align: center">Регистрация</h2>

    <form method="POST" action="{{route('register')}}">
        @csrf
        @include('components.input', ['attributeName' => 'name', 'attributeType' => 'text', 'attributeTitle' => 'Имя'])
        @include('components.input', ['attributeName' => 'last_name', 'attributeType' => 'text', 'attributeTitle' => 'Фамилия'])
        @include('components.input', ['attributeName' => 'email', 'attributeType' => 'email', 'attributeTitle' => 'E-mail'])
        @include('components.input', ['attributeName' => 'phone', 'attributeType' => 'text', 'attributeTitle' => 'Телефон'])
        @include('components.input', ['attributeName' => 'password', 'attributeType' => 'password', 'attributeTitle' => 'Пароль'])

        <button type="submit" class="submit-button">Зарегестрироваться</button>
    </form>
</div>

</body>
</html>
