<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Бородатый Гуру</title>
</head>
<body>
<div class="page">
    <header class="header">
        <div class="header__container container">
            <div class="header__logo">
                <img src="{{ asset('img/Logo.svg') }}" alt="Иконка ножниц" class="header__icon">
                <a class="header__title" href="{{ route('home') }}" style="text-decoration: none; font-weight: bold">Бородатый Гуру</a>

            </div>
            <nav class="header__nav">
                <a href="#about-block" class="header__nav-link">О нас</a>
                <a href="#photo-block" class="header__nav-link">Фото</a>
                <a href="#contact-block" class="header__nav-link">Контакты</a>
                @guest
                <a href="{{ route('login') }}" class="header__nav-link">Авторизация</a>
                <a href="{{ route('register') }}" class="header__nav-link">Регистрация</a>
                @endguest

                @auth
                <a class="header__nav-link" href="{{ route('profile') }}">Личный кабинет</a>
                @endauth

            </nav>

            <button class="header__button button" onclick="window.location.href='{{ route('appointments.create') }}'">Записаться</button>
        </div>
    </header>

    <section class="intro">
        <div class="intro__container container">
            <h2 class="intro__title">Бородатый Гуру</h2>
            <p class="intro__subtitle">Барбершоп</p>
            <button class="intro__button button" onclick="window.location.href='{{ route('appointments.create') }}'">Записаться</button>
        </div>
    </section>

    <div class="contacts">
        <div class="contact-info container">
            <p class="contact-info__item"><img src="{{ asset('img/phone.svg') }}" alt="Телефон"> +7 (919) 777-91-99</p>
            <p class="contact-info__item contact-info__link"><img src="{{ asset('img/mail.svg') }}" alt="Email">barber-guru@gmail.com</p>
            <p class="contact-info__item"><img src="{{ asset('img/adress.svg') }}" alt="Адрес" style="width: 21px;"> Мерляковский пер., 10, Москва</p>
        </div>

        <div class="image-container container">
            <img src="{{ asset('img/barber.jpg') }}" alt="Барбер" class="image-container__img">
        </div>
    </div>

    <section class="about container">
        <h2 class="about__title" id="about-block">О нас</h2>
        <div class="text-container">
            <p class="about__subtitle">Воплощение мужского<br> стиля: История нашего<br> барбершопа.</p>
            <p class="about__text">
                Барбершоп "Бородатый гуру" был основан в 2015 году двумя закадычными друзьями,
                Дмитрием и Сергеем. Их увлечение мужским стилем и уходом за внешностью началось с детства.
                Они мечтали создать место, где каждый мужчина сможет почувствовать себя настоящим джентльменом.
            </p>
        </div>
    </section>

    <section class="gallery container">
        <h2 class="gallery__title" id="photo-block">Фото</h2>
        <div class="gallery__grid">
            <img src="{{ asset('img/photo1.jpg') }}" alt="Барбершоп" class="gallery__item photo1">
            <img src="{{ asset('img/photo2.jpg') }}" alt="Мастер за работой" class="gallery__item photo2">
            <img src="{{ asset('img/photo3.jpg') }}" alt="Кресло барбершопа" class="gallery__item photo3">
            <img src="{{ asset('img/photo4.jpg') }}" alt="Инструменты барбера" class="gallery__item photo4">
        </div>
    </section>

    <section class="contacts container">
        <h2 class="contacts__title" id="contact-block">Контакты</h2>
        <div class="contact-info-map">
            <p class="contact-info__item-map"><img src="{{ asset('img/phone.svg') }}" alt="Телефон"> +7 (919) 777-91-99</p>
            <p class="contact-info__item-map"><img src="{{ asset('img/mail.svg') }}" alt="Email"> barber-guru@gmail.com</p>
            <p class="contact-info__item-map"><img src="{{ asset('img/adress.svg') }}" alt="Адрес"> Мерляковский пер., 10, Москва</p>
        </div>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2245.2230547284594!2d37.59815808745225!3d55.75462733528557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a4c1f297289%3A0xe08c7694d52a7244!2z0JzQtdGA0LfQu9GP0LrQvtCy0YHQutC40Lkg0L_QtdGALiwgMTAsINCc0L7RgdC60LLQsCwgMTIxMDY5!5e0!3m2!1sru!2sru!4v1729613065207!5m2!1sru!2sru"
                width="1080"
                height="486"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

    <footer class="footer">
        <div class="footer__container container">
            <div class="footer__logo">
                <img src="{{ asset('img/Logo.svg') }}" alt="Логотип" class="footer__icon">
                <h2 class="footer__title">Бородатый Гуру</h2>
            </div>
            <p class="footer__rights">© 2024 Все права защищены</p>
        </div>
    </footer>
</div>
</body>
</html>
