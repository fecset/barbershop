<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Записаться на прием «Бородатый Гуру»</title>
    <meta name="description" content="Запишитесь на стрижку или бритье в барбершопе «Бородатый Гуру» в Москве. Удобная форма записи на услуги, выбор мастера и даты.">
    <meta name="keywords" content="записаться на стрижку, барбершоп, Москва, запись онлайн, услуги для мужчин, бритье, стрижка, барбершоп Москва">
    <meta name="author" content="Бородатый Гуру">
    <meta name="robots" content="noindex, nofollow">

    <meta property="og:title" content="Записаться на прием в барбершоп «Бородатый Гуру»">
    <meta property="og:description" content="Запишитесь на стрижку или бритье в барбершопе «Бородатый Гуру» в Москве. Удобная форма записи на услуги, выбор мастера и даты.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">

    <link rel="canonical" href="{{ url()->current() }}">

    <meta name="google-site-verification" content="SjTH18c0vMrO1PjwzfqLQuZOWxFTm_k60U3YIFF0uB4">
    <meta name="yandex-verification" content="c9a4e132d5f4950c">

    <link rel="stylesheet" href="{{ asset('css/record.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/ru.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ru.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
</head>

<body>
<div style="display: flex; align-items: center; width: 34%;">
    <p class="back" style="display: flex; align-items: center;" onclick="window.location.href='{{ route('home') }}'">
        <img src="{{ asset('img/left-arrow.svg') }}" alt="back" style="margin-right: 15px; text-decoration: none;"> Назад
    </p>
</div>

<div class="container">
    <div class="logo">
        <img src="{{ asset('img/Logo.svg') }}" alt="">
        <h1>Бородатый Гуру</h1>
    </div>
    <p>Мерзляковский пер., 10, Москва</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="статус" value="Ожидает подтверждения">
        <input type="hidden" name="name" value="{{ auth()->user()->name }}">
        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
        <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">

        <div class="form-group">
            <label for="service">Выберите услугу</label>
            <select class="form-group-input @error('услуга_id') is-invalid @enderror" id="service" name="услуга_id">
                <option value="">Выберите услугу</option>
                @foreach($services as $service)
                    <option value="{{ $service->услуга_id }}" {{ old('услуга_id') == $service->услуга_id ? 'selected' : '' }}>
                        {{ $service->название }}
                    </option>
                @endforeach
            </select>
            @error('услуга_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="master">Выберите мастера</label>
            <select class="form-group-input @error('мастер_id') is-invalid @enderror" id="master" name="мастер_id">
                <option value="">Выберите мастера</option>
            </select>
            @error('мастер_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date-picker">Выберите дату и время</label>
            <input type="text" id="date-picker" name="дата_время" required class="form-control @error('дата_время') is-invalid @enderror" disabled value="{{ old('дата_время') }}">
            @error('дата_время')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="button-wrapper">
            <button type="submit" class="submit-button">Записаться</button>
        </div>
    </form>
</div>
<script defer src="{{ asset('js/record.js') }}"></script>

</body>
</html>
