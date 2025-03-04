<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель "Бородатый Гуру"</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin-panel/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('admin-panel/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('admin-panel/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin-panel/img/favicon/site.webmanifest') }}">

    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('admin-panel/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/service.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/masters.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/records.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/calendar.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <script type="module" src="{{ asset('admin-panel/js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/color-calendar@1.0.5/dist/bundle.js"></script>
    <link rel="stylesheet" href="{{ asset('admin-panel/css/calendar/theme-basic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-panel/css/calendar/theme-glass.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="page">

<header class="header">
    <div class="header__top">
        <img class="header__icon" src="{{ asset('admin-panel/img/logo.svg') }}" alt="logo">
        <h1 class="header__title">Админ-панель "Бородатый Гуру"</h1>
    </div>
    <div class="header__login">
        <img class="header__login-icon" src="{{ asset('admin-panel/img/avatar2.png') }}" alt="User Icon">
        <span class="header__login-text">
            @if(auth('superadmin')->check())
                {{ auth('superadmin')->user()->имя }} <!-- Имя суперадминистратора -->
            @elseif(auth('admin')->check())
                {{ auth('admin')->user()->имя }} <!-- Имя администратора -->
            @else
                Username
            @endif
        </span>
        <button class="header__arrow-button" id="dropdownButton">
            <img src="{{ asset('admin-panel/img/arrow-down.svg') }}" alt="Arrow Down">
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
            <ul>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" id="logoutLink" style="background: none; border: none; color: white; display: flex; align-items: center; padding: 8px 12px; cursor: pointer;">
                            Выйти
                            <img src="{{ asset('admin-panel/img/log-out.svg') }}" alt="" style="margin-left: 8px; height: 18px;">
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>


<div class="container">
    <aside class="sidebar">
        <div class="sidebar__user">
            <img class="sidebar__avatar" src="{{ asset('admin-panel/img/avatar.png')}}" alt="User Avatar">
            <div class="sidebar__user-info">
                <span class="sidebar__username">
                    @if(auth('superadmin')->check())
                        {{ auth('superadmin')->user()->имя }} <!-- Имя суперадминистратора -->
                    @elseif(auth('admin')->check())
                        {{ auth('admin')->user()->имя }} <!-- Имя администратора -->
                    @else
                        Username
                    @endif</span>
                <span class="sidebar__role">
                    @if(auth('superadmin')->check())
                        Superadmin
                    @elseif(auth('admin')->check())
                        Admin
                    @else
                        Guest
                    @endif
                </span>
            </div>
        </div>

        <ul class="sidebar__menu">
            <li class="sidebar__title">
                <p class="sidebar__text">Меню</p>
            </li>
            <li class="sidebar__item">
                <a href="#" class="sidebar__link" data-icon="home">
                    <img class="sidebar__icon" src="{{ asset('admin-panel/img/home.svg')}}" alt="Home Icon">
                    Главная
                </a>
            </li>
            <li class="sidebar__item">
                <a href="#" class="sidebar__link" data-icon="services">
                    <img class="sidebar__icon" src="{{ asset('admin-panel/img/services.svg') }}" alt="Services Icon">
                    Управление услугами
                </a>
            </li>
            <li class="sidebar__item">
                <a href="#" class="sidebar__link" data-icon="masters">
                    <img class="sidebar__icon" src="{{ asset('admin-panel/img/masters.svg') }}" alt="Masters Icon">
                    Мастера
                </a>
            </li>
            <li class="sidebar__item">
                <a href="#" class="sidebar__link" data-icon="records">
                    <img class="sidebar__icon" src="{{ asset('admin-panel/img/records.svg') }}" alt="Records Icon">
                    Записи
                </a>
            </li>
            @if(auth('superadmin')->check())
            <li class="sidebar__item" id="adminTab">
                <a href="#" class="sidebar__link" data-icon="admin" onclick="showTab('admin')">
                    <img class="sidebar__icon" src="{{ asset('admin-panel/img/admin.svg') }}" alt="Admin Icon">
                    Администраторы
                </a>
            </li>
            @endif
        </ul>

    </aside>

    <!-- Окно Главная -->
    <main class="main-content" id="main-content">
        <section class="schedule-table">
            <div class="schedule-table_header main-header">
                <h2 class="schedule-table__title main-title">Таблица записей</h2>
            </div>
            <table id="scheduleTable" class="schedule-records-table">
                <thead>
                <tr id="mastersRow">
                    <th></th>
                </tr>
                </thead>
                <tbody id="scheduleBody">
                </tbody>
            </table>
        </section>

        <!-- Модальное окно для ввода клиента -->
        <div id="clientModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Запись клиента</h2>
                </div>


                <div id="recordDetails" style="display: none;">
                    <p><strong>Статус:</strong> <span id="recordStatus"></span></p>
                    <p><strong>ID записи:</strong> <span id="recordId">-</span></p>
                </div>


                <label for="clientNameInput">Имя клиента:</label>
                <input type="text" id="clientNameInput" maxlength="35" required>

                <label for="clientPhoneInput">Телефон:</label>
                <input type="tel" id="clientPhoneInput" maxlength="15" placeholder="7XXXXXXXXXX" required readonly>

                <label for="serviceSelect">Выберите услугу:</label>
                <select id="serviceSelect" required>
                    <option value="" disabled selected>-- Выберите услугу --</option>
                    <!-- Варианты услуг добавляются программно -->
                    <option value="other">Другое</option>
                </select>

                <div id="otherServiceContainer" style="display: none;">
                    <label for="otherServiceInput">Введите название услуги:</label>
                    <input type="text" id="otherServiceInput" maxlength="30">
                </div>

                <div id="errorMessage" class="error-message"></div>
                <div class="buttons">
                    <button id="saveClientButton">Сохранить</button>
                    <button id="deleteClientButton" style="display: none; background-color: #8E2E2E; color: white; margin-left: 10px;">Удалить</button>
                </div>
            </div>
        </div>


        <div id="recordModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="mastersName">Имя мастера</h2>
                    <img src="{{ asset('admin-panel/img/close.svg') }}" alt="close" class="close-button">
                </div>


                <p>Список записей: </p>
                <ul id="recordList"></ul>
            </div>
        </div>

        <section class="new-records" style="display: none;">
            <div class="new-records_header main-header">
                <h2 class="new-records__title main-title">Новые записи</h2>
            </div>
            <table class="records-table">
                <thead>
                <tr>
                    <th>Клиент</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody id="newRecordsTableBody">
                </tbody>
            </table>
        </section>

        <div class="section-div">
            <section class="masters">
                <div class="masters_header main-header">
                    <h2 class="masters__title main-title">Мастера</h2>
                </div>
                <ul class="masters-list">
                </ul>
            </section>

            <section class="calendar">
                <div class="calendar_header main-header">
                    <h2 class="calendar__title main-title">Календарь</h2>
                </div>
                <div class="calendar_container">
                    <div id="calendar-a"></div>
                </div>
            </section>
        </div>


    </main>
    <script type="module" src="{{ asset('admin-panel/js/calendar.js') }}"></script>

    <!-- Окно Услуги -->
    <div class="main-services main" id="servicesSection" style="display: none;">
        <div class="services_header">
            <h2 class="services__title">Управление услугами</h2>
        </div>
        <div class="services">
            <table class="services__table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Специализация</th>
                    <th>Стоимость</th>
                    <th>Действие</th>
                    <th>
                        <button id="addServiceButton" class="service__button service__button--add">
                            <img src="{{ asset('admin-panel/img/add.svg') }}" alt="Добавить услугу">
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody id="servicesTableBody">

                </tbody>
            </table>
        </div>
    </div>

    <!-- Модальное окно Настройки -->
    <div id="settingsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Настройки</h2>
                <img class="close" id="closeSettingsModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>
            <table>
                <tr>
                    <td>ID</td>
                    <td>
                        <span id="serviceId"></span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Название</td>
                    <td>
                        <span id="serviceName" contenteditable="false"></span>
                    </td>
                    <td><img class="edit-icon" src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit"></td>
                </tr>
                <tr>
                    <td>Специализация</td>
                    <td>
                        <select id="serviceSpecialization">
                            <option value="Стрижка и укладка">Стрижка и укладка</option>
                            <option value="Бритьё и уход за бородой">Бритьё и уход за бородой</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Стоимость</td>
                    <td>
                        <span id="priceValue" contenteditable="false"></span> ₽
                    </td>
                    <td><img class="edit-icon" src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit"></td>
                </tr>
            </table>
            <button id="saveSettings">Сохранить</button>
        </div>
    </div>

    <!-- Модальное окно Добавление услуги -->
    <div id="addServiceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Добавить услугу</h2>
                <img class="close" id="closeAddServiceModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>

            <label for="newServiceName">Название:</label>
            <input type="text" id="newServiceName" class="newService_input" required maxlength="30">
            <span id="nameError" class="error-message"></span>
            <br>

            <label for="newServicePrice">Цена:</label>
            <input type="number" id="newServicePrice" class="newService_input" required>
            <span id="priceError" class="error-message"></span><br>

            <label for="serviceSpec">Специализация:</label>
            <select id="serviceSpec" required>
                <option value="" disabled selected>Выберите специализацию</option>
                <option value="Стрижка и укладка">Стрижка и укладка</option>
                <option value="Бритьё и уход за бородой">Бритьё и уход за бородой</option>
            </select><br>
            <span id="specializationError" class="error-message"></span><br>
            <button id="saveNewService">Сохранить</button>
        </div>
    </div>

    <!-- Окно Мастера -->
    <div class="main-masters main" id="mastersSection" style="display: none;">
        <div class="masters-header">
            <h2 class="masters-title">Список мастеров</h2>
        </div>
        <div class="masters-content">
            <table class="masters-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Специализация</th>
                    <th>График</th>
                    <th>Действие</th>
                    <th>
                        <button id="addMasterButton" class="master-button master__button--add">
                            <img src="{{ asset('admin-panel/img/add.svg') }}" alt="Добавить услугу">
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody id="mastersTableBody">
                </tbody>
            </table>
        </div>
    </div>
    <!-- Модальное окно настройки мастеров -->
    <div id="masterSettingsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Настройки</h2>
                <img class="close" id="closeMasterModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>
            <table class="modal-table">
                <tr>
                    <td class="modal-label">ID</td>
                    <td><span id="masterId" contenteditable="false">001</span></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="modal-label">Имя</td>
                    <td><span id="masterName" contenteditable="false">Иван</span></td>
                    <td><img class="edit-icon" src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit"></td>
                </tr>

                <tr>
                    <td class="modal-label">Специализация</td>
                    <td>
                        <select id="editMasterSpecialization" required>
                            <option value="" disabled selected>Выберите специализацию</option>
                            <option value="Стрижка и укладка">Стрижка и укладка</option>
                            <option value="Бритьё и уход за бородой">Бритьё и уход за бородой</option>
                            <option value="Уборка">Уборка</option>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="modal-label">График</td>
                    <td><span id="masterSchedule" contenteditable="true"></span></td>
                    <td><img class="edit-icon" src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit"></td>
                </tr>
            </table>
            <button id="saveMasterSettings" class="modal-save-button">Сохранить</button>
        </div>
    </div>
    <!-- Модальное окно добавления мастера -->
    <div id="addMasterModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Добавить мастера</h2>
                <img class="close" id="closeAddMasterModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>
            <label for="newMasterName">Имя:</label>
            <input type="text" id="newMasterName" class="newMaster_input" required maxlength="30">
            <span id="masterNameError" class="error-message"></span>
            <br>
            <label for="newMasterSpecialization">Специализация:</label>
            <select id="newMasterSpecialization" required>
                <option value="" disabled selected>Выберите специализацию</option>
                <option value="Стрижка и укладка">Стрижка и укладка</option>
                <option value="Бритьё и уход за бородой">Бритьё и уход за бородой</option>
                <option value="Уборка">Уборка</option>
            </select>
            <br>
            <label for="newMasterSchedule">График:</label>
            <input placeholder="Пн-Пт 9:00-19:00" type="text" id="newMasterSchedule" class="newMaster_input" required>
            <span id="scheduleError" class="error-message"></span>

            <br>
            <button id="saveNewMaster">Сохранить</button>
        </div>
    </div>


    <!-- Окно Записей -->
    <div class="main-records" id="recordsSection" style="display: none;">
        <div class="records-header">
            <h2 class="records-title">Записи</h2>
        </div>
        <div class="records-content">
            <table class="records-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody id="recordsTableBody">

                </tbody>
            </table>
        </div>
    </div>

    <!-- Модальное окно для подробностей записи -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Подробнее</h2>
                <img class="close" id="closeDetailsModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>
            <table>
                <tr>
                    <td>ID</td>
                    <td><span id="detailId"></span></td>
                </tr>
                <tr>
                    <td>Имя клиента</td>
                    <td><span id="detailClientName"></span></td>
                </tr>
                <tr>
                    <td>Номер телефона</td>
                    <td><span id="detailPhone"></span></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><span id="detailEmail"></span></td>
                </tr>
                <tr>
                    <td>Мастер</td>
                    <td><span id="detailMaster"></span></td>
                </tr>
                <tr>
                    <td>Услуга</td>
                    <td><span id="detailService"></span></td>
                </tr>
                <tr>
                    <td>Стоимость</td>
                    <td><span id="detailPrice"></span></td>
                </tr>
                <tr>
                    <td>Дата</td>
                    <td><span id="detailDate"></span></td>
                </tr>
                <tr>
                    <td>Статус записи</td>
                    <td><span id="detailStatus"></span></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Окно Администраторы -->
    @if(auth('superadmin')->check())
    <div class="main-admins main" id="adminSection">
        <div class="admins-header">
            <h2 class="admins-title">Список администраторов</h2>
        </div>
        <div class="admins-content">
            <table class="admins-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Логин</th>
                    <th>Действие</th>
                    <th>
                        <button id="addAdminButton" class="admin-button admin__button--add">
                            <img src="{{ asset('admin-panel/img/add.svg') }}" alt="Добавить администратора">
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody id="adminsTableBody">
                </tbody>
            </table>
        </div>
        @endif
        <!-- Модальное окно настройки администратора -->
        <div id="adminSettingsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Настройки администратора</h2>
                    <img class="close" id="closeAdminModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
                </div>
                <table class="modal-table">
                    <tr>
                        <td>ID:</td>
                        <td><span id="adminId" contenteditable="false"></span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Имя:</td>
                        <td>
                            <span id="adminName" contenteditable="false"></span>
                        </td>
                        <td>
                            <img src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit" class="edit-icon" style="cursor:pointer;">
                        </td>
                    </tr>
                    <tr>
                        <td>Логин:</td>
                        <td>
                            <span id="adminLogin" contenteditable="false"></span>
                        </td>
                        <td>
                            <img src="{{ asset('admin-panel/img/edit.svg') }}" alt="Edit" class="edit-icon" style="cursor:pointer;">
                        </td>
                    </tr>

                </table>
                <button id="saveAdminSettings" class="modal-save-button">Сохранить</button>
            </div>
        </div>


    </div>

    <!-- Модальное окно добавления администратора -->
    <div id="addAdminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Добавить администратора</h2>
                <img class="close" id="closeAddAdminModal" src="{{ asset('admin-panel/img/close.svg') }}" alt="close">
            </div>
            <label for="newAdminName">Имя:</label>
            <input type="text" id="newAdminName" class="newAdmin_input" required maxlength="30">
            <span id="adminNameError" class="error-message"></span>
            <br>
            <label for="newAdminLogin">Логин:</label>
            <input type="text" id="newAdminLogin" class="newAdmin_input" required maxlength="30">
            <span id="loginError" class="error-message"></span>
            <br>
            <label for="newAdminPassword">Пароль:</label>
            <input type="text" id="newAdminPassword" class="newAdmin_input" required maxlength="25">
            <span id="passwordError" class="error-message"></span>

            <br>
            <button id="saveNewAdmin">Сохранить</button>
        </div>
    </div>

</div>
<script type="module" src="{{ asset('admin-panel/js/modules/tabs.js') }}"></script>
<script type="module" src="{{ asset('admin-panel/js/modules/masters.js') }}"></script>
<script type="module" src="{{ asset('admin-panel/js/modules/services.js') }}"></script>
<script type="module" src="{{ asset('admin-panel/js/modules/records.js') }}"></script>
<script type="module" src="{{ asset('admin-panel/js/modules/admin.js') }}"></script>
<script type="module" src="{{ asset('admin-panel/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
</body>

</html>
