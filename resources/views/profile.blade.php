<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Личный кабинет | Бородатый Гуру</title>
    <meta name="description" content="Ваш личный кабинет в барбершопе «Бородатый Гуру». Управляйте своими записями, просматривайте историю посещений и записывайтесь на новые услуги.">
    <meta name="keywords" content="личный кабинет, барбершоп, записи, услуги, Москва, бронирование, мастер, стрижка, бритье">
    <meta name="author" content="Бородатый Гуру">
    <meta name="robots" content="noindex, nofollow">

    <meta property="og:title" content="Личный кабинет | Бородатый Гуру">
    <meta property="og:description" content="Ваш личный кабинет в барбершопе «Бородатый Гуру». Управляйте своими записями, просматривайте историю посещений и записывайтесь на новые услуги.">
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
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <script defer src="{{ asset('js/profile.js') }}"></script>
</head>

<body>
<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <img src="{{ asset('img/Logo.svg') }}" alt="Иконка ножниц" class="header__icon">
            <a class="header__title" href="{{ route('home') }}" style="text-decoration: none; font-weight: bold">Бородатый Гуру</a>
        </div>

        <div class="header__nav" style="margin-right: 0px">
            <p style="margin-top: 23px"> {{ auth()->user()->email }}</p>

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class=" exit-btn">Выход</button>
            </form>
        </div>

    </div>
</header>

<div class="container">
    <h2>Личный кабинет</h2>
    <p>{{ auth()->user()->name }} {{ auth()->user()->last_name }}</p>
    <button style="border-radius: 10px; margin-top: -10px" class="intro__button button" onclick="window.location.href='{{ route('appointments.create') }}'">Записаться</button>
    <h3>Ваши записи</h3>

    <table class="table">
        <thead>
        <tr class="table__row">
            <th class="table__header">Дата и время</th>
            <th class="table__header">Мастер</th>
            <th class="table__header">Услуга</th>
            <th class="table__header">Статус</th>
            <th class="table__header">Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($appointments as $appointment)
            <tr class="table__row">
                <td class="table__cell">{{ $appointment->дата_время }}</td>
                <td class="table__cell">{{ $appointment->master->имя }}</td>
                <td class="table__cell">{{ $appointment->service->название }}</td>
                <td class="table__cell">{{ $appointment->статус }}</td>
                <td class="table__cell">

                    <button
                        type="button"
                        class="table__button table__button--danger"
                        onclick="confirmDelete('delete-form-{{ $appointment->запись_id }}')"
                    >
                        Удалить
                    </button>

                    <form
                        id="delete-form-{{ $appointment->запись_id }}"
                        action="{{ route('appointments.destroy', $appointment->запись_id) }}"
                        method="POST"
                        style="display: none;"
                    >
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div class="modal-overlay" id="modal-overlay">
    <div class="modal">
        <h4>Подтверждение отмены</h4>
        <p>Вы действительно хотите удалить эту запись?</p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeModal()">Отмена</button>
            <button class="btn-confirm" id="confirm-btn">Удалить</button>
        </div>
    </div>
</div>

</body>
</html>
