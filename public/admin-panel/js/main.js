import { initTabSwitching } from './modules/tabs.js';
import { initMasters } from './modules/masters.js';
import { initServices } from './modules/services.js';
import { initRecords } from './modules/records.js';
import { initAdmins } from './modules/admin.js';

export const state = {
    selectedDate: new Date().toISOString().split('T')[0],
};

initTabSwitching();
initMasters();
initServices();
initRecords();
initAdmins();

const icons = {
    home: {
        default: 'img/home.svg',
        active: 'img/home-active.svg'
    },
    services: {
        default: 'img/services.svg',
        active: 'img/services-active.svg'
    },
    masters: {
        default: 'img/masters.svg',
        active: 'img/masters-active.svg'
    },
    records: {
        default: 'img/records.svg',
        active: 'img/records-active.svg'
    },
    admin: {
        default: 'img/admin.svg',
        active: 'img/admin-active.svg'
    }
};

const links = document.querySelectorAll('.sidebar__link');

function resetIcons() {
    links.forEach(otherLink => {
        const iconKey = otherLink.getAttribute('data-icon');
        const img = otherLink.querySelector('.sidebar__icon');

        if (icons[iconKey]) {
            otherLink.classList.remove('sidebar__link--active');
            img.src = icons[iconKey].default;
        }
    });
}

links.forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();

        resetIcons();

        const iconKey = this.getAttribute('data-icon');
        const img = this.querySelector('.sidebar__icon');

        if (icons[iconKey]) {
            this.classList.add('sidebar__link--active');
            img.src = icons[iconKey].active;
        }
    });

    link.addEventListener('mouseenter', function () {
        const iconKey = this.getAttribute('data-icon');
        const img = this.querySelector('.sidebar__icon');

        if (icons[iconKey] && !this.classList.contains('sidebar__link--active')) {
            img.src = icons[iconKey].active;
        }
    });

    link.addEventListener('mouseleave', function () {
        const iconKey = this.getAttribute('data-icon');
        const img = this.querySelector('.sidebar__icon');

        if (icons[iconKey] && !this.classList.contains('sidebar__link--active')) {
            img.src = icons[iconKey].default;
        }
    });
});


const dropdownButton = document.getElementById('dropdownButton');
const dropdownMenu = document.getElementById('dropdownMenu');
const logoutLink = document.getElementById('logoutLink');

dropdownButton.addEventListener('click', function () {
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function (event) {
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', () => {

    // Загружаем данные о мастерах с сервера
    fetch('/api/masters')
        .then(response => response.json())
        .then(masters => {
            const mastersList = document.querySelector('.masters-list');
            mastersList.innerHTML = '';

            masters.forEach(master => {
                const listItem = document.createElement('li');
                listItem.textContent = `${master.имя} - ${master.график_работы}`;

                listItem.addEventListener('click', () => {
                    openRecordModal(master.имя);
                });

                mastersList.appendChild(listItem);
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки данных:', error);
        });
});

async function openRecordModal(masterName) {
    const recordModal = document.getElementById('recordModal');

    const masterNameElement = document.getElementById('mastersName');
    if (masterNameElement) {
        masterNameElement.textContent = masterName;
    }

    try {
        const recordsData = await getRecordsForMaster(masterName);
        const appointments = recordsData.appointments || [];
        const clients = recordsData.clients || [];
        const services = recordsData.services || [];
        const masters = recordsData.masters || [];

        // Найти ID выбранного мастера
        const master = masters.find(m => m.имя === masterName);
        if (!master) {
            console.error('Мастер не найден:', masterName);
            return;
        }
        const masterId = master.мастер_id;

        // Фильтруем записи только для выбранного мастера
        const records = appointments.filter(record => record.мастер_id === masterId);

        const recordList = document.getElementById('recordList');
        recordList.innerHTML = '';

        if (records.length === 0) {
            const noRecordsMessage = document.createElement('li');
            noRecordsMessage.textContent = 'Записи отсутствуют';
            noRecordsMessage.style.color = '#999';
            noRecordsMessage.style.fontStyle = 'italic';
            recordList.appendChild(noRecordsMessage);
        } else {
            records.forEach((record, index) => {
                const recordItem = document.createElement('li');

                // Найти имя клиента по его ID
                const client = clients.find(c => c.клиент_id === record.клиент_id);
                const clientName = client ? `${client.имя} ${client.фамилия}` : 'Неизвестно';

                // Найти название услуги по её ID
                const service = services.find(s => s.услуга_id === record.услуга_id);
                const serviceName = service ? service.название : 'Неизвестно';

                const recordNumber = document.createElement('span');
                recordNumber.className = 'record-number';
                recordNumber.textContent = index + 1;

                const recordText = document.createElement('span');
                recordText.innerHTML = `<strong>Дата и время:</strong> ${record.дата_время}, <strong>Клиент:</strong> ${clientName}, <strong>Услуга:</strong> ${serviceName}`;

                recordItem.appendChild(recordNumber);
                recordItem.appendChild(recordText);
                recordList.appendChild(recordItem);
            });
        }

        recordModal.style.display = 'block';

        document.querySelector('.close-button').onclick = function() {
            recordModal.style.display = 'none';
        };

        window.onclick = function(event) {
            if (event.target === recordModal) {
                recordModal.style.display = 'none';
            }
        };
    } catch (error) {
        console.error('Ошибка при получении записей:', error);
    }
}


function getRecordsForMaster(masterName) {
    return fetch(`/api/appointments?master=${encodeURIComponent(masterName)}`)
        .then(response => response.json())
        .then(records => records || [])
        .catch(error => {
            console.error('Ошибка при загрузке записей:', error);
            return [];
        });
}


document.addEventListener('DOMContentLoaded', () => {

    const userData = JSON.parse(localStorage.getItem('currentUser'));

    logoutLink.addEventListener('click', function () {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('role');
        localStorage.removeItem('currentUser');
        localStorage.removeItem('activeTab');
        window.location.href = 'auth.html';
    });

    const role = localStorage.getItem('role');

    const activeTab = localStorage.getItem('activeTab') || 'home';
    showTab(activeTab);

});

document.addEventListener('DOMContentLoaded', initScheduleTable);

function initScheduleTable() {
    fetch('/api/masters')
        .then(response => response.json())
        .then(masters => {
            masters = masters.filter(master => master.специализация !== 'Уборка');
            generateScheduleHeader(masters);
            generateScheduleBody(masters);
            loadRecords();
        })
        .catch(error => {
            console.error('Ошибка при загрузке мастеров:', error);
        });
}




function generateScheduleHeader(masters) {
    const mastersRow = document.getElementById('mastersRow');
    masters.forEach(master => {
        const th = document.createElement('th');
        th.textContent = master.имя;
        mastersRow.appendChild(th);
    });
}


export function generateScheduleBody(masters) {
    const scheduleBody = document.getElementById('scheduleBody');
    scheduleBody.innerHTML = '';
    const startTime = 9;
    const endTime = 19;
    const interval = 30;
    const today = new Date();
    const maxDate = new Date();
    maxDate.setDate(today.getDate() + 30);

    for (let hour = startTime; hour < endTime; hour++) {
        for (let minute = 0; minute < 60; minute += interval) {
            const row = document.createElement('tr');
            const timeCell = document.createElement('td');
            timeCell.classList.add('time-cell');
            const timeText = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
            timeCell.textContent = timeText;
            row.appendChild(timeCell);

            masters.forEach(master => {
                const cell = document.createElement('td');
                cell.classList.add('schedule-cell');
                cell.dataset.time = timeText;
                cell.dataset.master = master.имя;
                cell.dataset.date = state.selectedDate;

                const cellDateTime = new Date(`${state.selectedDate}T${timeText}`);
                if (cellDateTime < today || cellDateTime > maxDate) {
                    cell.classList.add('disabled-cell');
                } else {
                    cell.addEventListener('click', () => handleCellClick(cell));
                }

                row.appendChild(cell);
            });
            scheduleBody.appendChild(row);
        }
    }
}


async function getNextRecordId() {
    try {
        const response = await fetch('/api/appointments');
        const data = await response.json();
        const records = Array.isArray(data.appointments) ? data.appointments : [];
        return records.length > 0 ? Math.max(...records.map(r => parseInt(r.запись_id, 10))) + 1 : 1;
    } catch (error) {
        console.error('Ошибка при получении ID следующей записи:', error);
        return 1;
    }
}

async function getMasterIdByName(masterName) {
    try {
        const response = await fetch('/api/masters');
        const data = await response.json();
        console.log('Masters from API:', data);

        // Приводим строки к нижнему регистру и убираем лишние пробелы
        const normalizedMasterName = masterName.trim().toLowerCase();

        const master = data.find(m => m.имя.trim().toLowerCase() === normalizedMasterName);

        return master ? master.мастер_id : null;
    } catch (error) {
        console.error('Ошибка при получении мастера:', error);
        return null;
    }
}

async function getServiceIdByName(serviceName) {
    try {
        const response = await fetch('/api/services');
        const data = await response.json();
        console.log('Services from API:', data);

        // Приводим строки к нижнему регистру и убираем лишние пробелы
        const normalizedServiceName = serviceName.trim().toLowerCase();

        const service = data.find(s => s.название.trim().toLowerCase() === normalizedServiceName);

        return service ? service.услуга_id : null;
    } catch (error) {
        console.error('Ошибка при получении услуги:', error);
        return null;
    }
}



async function saveRecord(master, time, client, phone, service, date, isEdit = false) {
    try {
        console.log('Trying to find master:', master);
        console.log('Trying to find service:', service);

        const [masterId, serviceId, nextRecordId] = await Promise.all([
            getMasterIdByName(master),
            getServiceIdByName(service),
            getNextRecordId()
        ]);

        console.log('masterId:', masterId);
        console.log('serviceId:', serviceId);

        if (!masterId || !serviceId) {
            console.error('Ошибка: мастер или услуга не найдены.');
            return;
        }

        const requestData = {
            запись_id: isEdit ? undefined : nextRecordId.toString(),
            клиент_имя: client,
            клиент_телефон: phone,
            клиент_email: '-',
            мастер_id: masterId,
            мастер_имя: master,
            услуга_id: serviceId,
            услуга_название: service,
            дата_время: `${date} ${time}`,
            статус: 'Подтверждена'
        };


        const response = await fetch('/api/appointments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        });


        if (!response.ok) {
            throw new Error(`Ошибка при ${isEdit ? 'редактировании' : 'создании'} записи`);
        }

        console.log('Запись успешно сохранена');
        await loadRecords(date);
    } catch (error) {
        console.error('Ошибка при сохранении записи:', error);
    }
}



export async function loadRecords(date) {
    try {
        const response = await fetch('/api/appointments');
        const data = await response.json();
        const records = Array.isArray(data.appointments) ? data.appointments : [];
        const dateToLoad = date || new Date().toISOString().split('T')[0];

        document.querySelectorAll('.schedule-cell').forEach(cell => {
            cell.textContent = '';
        });

        records.forEach(record => {
            const recordDate = record.дата_время.split(" ")[0];
            const recordTime = record.дата_время.split(" ")[1];

            if (recordDate === dateToLoad) {
                const cell = document.querySelector(
                    `.schedule-cell[data-master="${record.мастер_имя}"][data-time="${recordTime}"]`
                );
                if (cell) {
                    cell.textContent = `${record.клиент_имя} (${record.услуга_название})`;
                }
            }
        });
    } catch (error) {
        console.error('Ошибка при загрузке записей:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => loadRecords());


const serviceSelect = document.getElementById('serviceSelect');
const otherServiceContainer = document.getElementById('otherServiceContainer');
const otherServiceInput = document.getElementById('otherServiceInput');
const deleteClientButton = document.getElementById('deleteClientButton');


serviceSelect.addEventListener('change', () => {
    if (serviceSelect.value === 'other') {
        otherServiceContainer.style.display = 'block';
        otherServiceInput.required = true;
    } else {
        otherServiceContainer.style.display = 'none';
        otherServiceInput.value = '';
        otherServiceInput.required = false;
    }
});


deleteClientButton.addEventListener('click', () => {
    if (selectedCell) {
        const masterName = selectedCell.dataset.master;
        const time = selectedCell.dataset.time;

        deleteRecord(masterName, time, state.selectedDate);

        closeClientModal();
        loadRecords(state.selectedDate);
    }
});



async function populateServiceSelect(masterSpecialization) {
    try {
        const response = await fetch('/api/services');
        const services = await response.json();

        const filteredServices = services.filter(service => service.специализация === masterSpecialization);

        serviceSelect.innerHTML = `
            <option value="" disabled selected>-- Выберите услугу --</option>
        `;

        filteredServices.forEach(service => {
            const option = document.createElement('option');
            option.value = service.услуга_id;
            option.textContent = service.название;
            serviceSelect.appendChild(option);
        });

        const otherOption = document.createElement('option');
        otherOption.value = 'other';
        otherOption.textContent = 'Другое';
        serviceSelect.appendChild(otherOption);
    } catch (error) {
        console.error('Ошибка при загрузке услуг:', error);
    }
}

async function deleteRecord(master, time, date) {
    try {
        // Найдем запись для удаления по мастеру, времени и дате
        const response = await fetch('/api/appointments');
        const records = await response.json();

        const recordToDelete = records.find(record => {
            const recordDate = record.дата_время.split(" ")[0];
            const recordTime = record.дата_время.split(" ")[1];
            const recordMaster = record.мастер_имя;
            return recordMaster === master && recordTime === time && recordDate === date;
        });

        if (recordToDelete) {
            // Удаляем запись
            const deleteResponse = await fetch(`/api/appointments/${recordToDelete.запись_id}`, {
                method: 'DELETE',
            });

            if (deleteResponse.ok) {
                console.log('Запись успешно удалена');
                loadRecords(date);
            } else {
                throw new Error('Ошибка при удалении записи');
            }
        }
    } catch (error) {
        console.error('Ошибка при удалении записи:', error);
    }
}




async function handleCellClick(cell) {
    console.log('Клик по ячейке:', cell);

    const cellDateTime = new Date(`${state.selectedDate}T${cell.dataset.time}`);
    const today = new Date();
    const maxDate = new Date();
    maxDate.setDate(today.getDate() + 30);

    if (cellDateTime < today || cellDateTime > maxDate) {
        console.log('Выбранная дата вне допустимого диапазона');
        return;
    }

    console.log('Запрос к API на получение записи...');
    const record = await getRecordForCell(cell.dataset.master, cell.dataset.time, state.selectedDate);

    console.log('Полученная запись:', record);

    // Открываем модальное окно независимо от наличия записи
    openClientModal(cell, record);
}



const clientModal = document.getElementById('clientModal');
const clientNameInput = document.getElementById('clientNameInput');
const saveClientButton = document.getElementById('saveClientButton');
const errorMessage = document.getElementById('errorMessage');

let selectedCell = null;

async function getRecordForCell(master, time, date) {
    try {
        console.log('Запрос к API на получение записи...');
        const response = await fetch('/api/appointments');
        const data = await response.json();
        console.log('Полученные данные:', data);

        if (!Array.isArray(data.appointments) || !Array.isArray(data.masters)) {
            throw new Error('Некорректные данные: отсутствует массив записей или мастеров');
        }

        console.log('Поиск записи: мастер:', master, 'время:', time, 'дата:', date);

        // Получаем массив мастеров
        const masters = data.masters;

        const foundRecord = data.appointments.find(record => {
            console.log('Проверяем запись:', record);

            const recordDateTime = record.дата_время; // Например, "2025-02-27 10:30:00"
            const [recordDate, recordTimeFull] = recordDateTime.split(" ");
            const recordTime = recordTimeFull.slice(0, 5); // Убираем секунды ("10:30:00" → "10:30")

            // Найти имя мастера по ID
            const recordMasterObject = masters.find(m => m.мастер_id === record.мастер_id);
            const recordMaster = recordMasterObject ? recordMasterObject.имя : 'Неизвестный мастер';

            console.log(`Сравнение: мастер(${recordMaster} === ${master}), время(${recordTime} === ${time}), дата(${recordDate} === ${date})`);

            return recordMaster === master && recordTime === time && recordDate === date;
        });

        console.log('Найденная запись:', foundRecord);
        return foundRecord;
    } catch (error) {
        console.error('Ошибка при получении записи:', error);
        return null;
    }
}





async function openClientModal(cell, record = null) {
    console.log('Открытие модального окна для ячейки:', cell);

    selectedCell = cell;

    // Очищаем поля перед открытием
    clientNameInput.value = '';
    document.getElementById('clientPhoneInput').value = '';
    serviceSelect.value = '';
    otherServiceContainer.style.display = 'none';
    otherServiceInput.value = '';
    deleteClientButton.style.display = 'none';

    const masterName = cell.dataset.master;
    const time = cell.dataset.time;
    const date = cell.dataset.date;

    try {
        // Запросы к API
        const [mastersResponse, appointmentsResponse, servicesResponse] = await Promise.all([
            fetch('/api/masters'),
            fetch('/api/appointments'),
            fetch('/api/services')
        ]);

        const mastersData = await mastersResponse.json();
        const appointmentsData = await appointmentsResponse.json();
        const servicesData = await servicesResponse.json();

        const masters = Array.isArray(mastersData) ? mastersData : mastersData.masters;
        const services = Array.isArray(servicesData) ? servicesData : servicesData.services;

        // Найти мастера по имени
        const selectedMaster = masters.find(master => master.имя === masterName);
        if (!selectedMaster) {
            console.error('Мастер не найден:', masterName);
            return;
        }

        // Заполняем список услуг, доступных у мастера
        populateServiceSelect(selectedMaster.специализация);

        if (record) {
            console.log('Заполняем данные найденной записи...');
            const clientResponse = await fetch(`/api/clients/${record.клиент_id}`);
            const client = await clientResponse.json();

            const clientName = client ? `${client.имя} ${client.фамилия}` : '';
            const clientPhone = client ? client.телефон : '';

            // Найти услугу по ID
            const service = services.find(s => s.услуга_id === record.услуга_id);
            const serviceName = service ? service.название : '';

            clientNameInput.value = clientName;
            document.getElementById('clientPhoneInput').value = clientPhone;
            serviceSelect.value = service ? service.услуга_id : '';

            updateStatusStyles(record.статус);

            document.getElementById('recordStatus').textContent = record.статус || 'Неизвестно';
            document.getElementById('recordId').textContent = record.запись_id || '-';
            document.getElementById('recordDetails').style.display = 'block';

            deleteClientButton.style.display = 'inline-block';
        } else {
            console.log('Запись отсутствует. Открываем пустое модальное окно.');
            document.getElementById('recordDetails').style.display = 'none';
            document.getElementById('recordStatus').textContent = 'Новая запись';
        }

        clientModal.style.display = 'flex';
        clientNameInput.focus();
    } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
    }
}



function closeClientModal() {
    clientModal.style.display = 'none';
    selectedCell = null;
    errorMessage.style.display = 'none';
}

function updateStatusStyles(status) {
    const statusElement = document.getElementById('recordStatus');


    statusElement.classList.remove('status-awaiting', 'status-confirmed', 'status-rejected');


    if (status === 'Ожидает подтверждения') {
        statusElement.textContent = 'Ожидает подтверждения';
        statusElement.classList.add('status-awaiting');
    } else if (status === 'Подтверждена') {
        statusElement.textContent = 'Подтверждена';
        statusElement.classList.add('status-confirmed');
    } else if (status === 'Отклонена') {
        statusElement.textContent = 'Отклонена';
        statusElement.classList.add('status-rejected');
    } else {
        statusElement.textContent = 'Неизвестный статус';
    }
}

saveClientButton.addEventListener('click', () => {
    const clientName = clientNameInput.value.trim();
    const clientPhone = document.getElementById('clientPhoneInput').value.trim();
    const selectedService = serviceSelect.value === 'other' ? otherServiceInput.value.trim() : serviceSelect.options[serviceSelect.selectedIndex].text;
    const masterName = selectedCell.dataset.master;
    const time = selectedCell.dataset.time;

    if (clientName && clientPhone && selectedService && selectedCell) {
        if (!/^\+?\d{10,15}$/.test(clientPhone)) {
            errorMessage.textContent = 'Введите корректный номер телефона.';
            errorMessage.style.display = 'block';
            return;
        }

        if (selectedService === "-- Выберите услугу --") {
            errorMessage.textContent = 'Выберите услугу.';
            errorMessage.style.display = 'block';
            return;
        }

        const isEdit = !!getRecordForCell(masterName, time);

        saveRecord(masterName, time, clientName, clientPhone, selectedService, state.selectedDate, isEdit);

        closeClientModal();
        errorMessage.style.display = 'none';

        loadRecords(state.selectedDate);
    } else {
        errorMessage.textContent = 'Введите имя клиента, телефон и выберите услугу.';
        errorMessage.style.display = 'block';
    }
});



clientNameInput.addEventListener('input', () => {

    clientNameInput.value = clientNameInput.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');


    if (/[^a-zA-Zа-яА-Я\s]/.test(clientNameInput.value)) {
        errorMessage.textContent = 'Имя может содержать только буквы и пробелы.';
        errorMessage.style.display = 'block';
    } else {
        errorMessage.style.display = 'none';
    }
});


window.addEventListener('click', (event) => {
    if (event.target === clientModal) {
        closeClientModal();
    }
});

window.loadRecords = loadRecords;
document.addEventListener('DOMContentLoaded', () => {
    const today = new Date();
    const todayString = today.toISOString().split('T')[0];


    loadRecords(todayString);
});
