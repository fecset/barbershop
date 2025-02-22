<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запись</title>
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

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="Введите ваше имя" class="form-control @error('name') is-invalid @enderror">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Введите ваш адрес электронной почты" class="form-control @error('email') is-invalid @enderror">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="tel" id="phone" name="phone" placeholder="Введите ваш номер телефона"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

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
