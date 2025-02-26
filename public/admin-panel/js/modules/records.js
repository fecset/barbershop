export function initRecords() {
    const recordsTableBody = document.getElementById('recordsTableBody');
    const newRecordsTableBody = document.getElementById('newRecordsTableBody');
    const detailsModal = document.getElementById('detailsModal');
    const closeDetailsModal = document.getElementById('closeDetailsModal');

    // Сопоставление статуса с классами
    function getStatusClass(status) {
        const statusClasses = {
            'Подтверждена': 'status-confirmed',
            'Ожидает подтверждения': 'status-awaiting',
            'Отклонена': 'status-rejected'
        };
        return statusClasses[status] || '';
    }


    // Загрузка данных с сервера
    async function loadDataFromApi() {
        try {
            const response = await fetch('/api/appointments');  // Запрос на сервер
            if (!response.ok) throw new Error('Ошибка загрузки данных с сервера');
            const data = await response.json();

            const records = data.appointments;  // Записи о клиентах
            const clients = data.clients;      // Список клиентов
            const masters = data.masters;      // Список мастеров
            const services = data.services;    // Список услуг

            const clientMap = mapById(clients, 'клиент_id');
            const masterMap = mapById(masters, 'мастер_id');
            const serviceMap = mapById(services, 'услуга_id');

            return enrichRecords(records, clientMap, masterMap, serviceMap);
        } catch (error) {
            console.error(error);
            return [];
        }
    }


    // Создание сопоставлений по ID
    function mapById(data, idField) {
        return data.reduce((map, item) => {
            map[item[idField]] = item;
            return map;
        }, {});
    }

    // Обогащение записей
    function enrichRecords(records, clientMap, masterMap, serviceMap) {
        return records.map(record => ({
            ...record,
            клиент_имя: clientMap[record.клиент_id]?.имя || 'Неизвестный клиент',
            клиент_телефон: clientMap[record.клиент_id]?.телефон || '',
            клиент_email: clientMap[record.клиент_id]?.email || '',
            мастер_имя: masterMap[record.мастер_id]?.имя || 'Неизвестный мастер',
            услуга_название: serviceMap[record.услуга_id]?.название || 'Неизвестная услуга',
            услуга_цена: serviceMap[record.услуга_id]?.цена || ''
        }));
    }

    // Получение записей с сервера
    async function getRecordsFromServer() {
        const records = await loadDataFromApi();  // Загрузка данных с API
        return records;
    }

    // Заполнение таблицы записей
    async function populateRecords() {
        const records = await getRecordsFromServer();
        recordsTableBody.innerHTML = '';
        records.forEach(record => {
            const row = createRecordRow(record);
            recordsTableBody.appendChild(row);
        });
    }

    // Создание строки записи
    function createRecordRow(record) {
        const statusClass = getStatusClass(record.статус);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${record.запись_id}</td>
            <td>${record.клиент_имя}</td>
            <td>${record.дата_время}</td>
            <td class="status ${statusClass}">${record.статус}</td>
            <td>
                <div class="record-buttons">
                    <button class="record-button record-button--info" data-id="${record.запись_id}">
                        <img src="img/info.svg" alt="Info">
                    </button>
                    <button class="record-button record-button--confirm ${record.статус === 'Подтверждена' ? 'inactive' : ''}">
                        <img src="img/confirm.svg" alt="Confirm">
                    </button>
                    <button class="record-button record-button--reject ${record.статус === 'Отклонена' ? 'inactive' : ''}">
                        <img src="img/reject.svg" alt="Reject">
                    </button>
                    <button class="record-button record-button--delete">
                        <img src="img/delete-icon.svg" alt="Delete">
                    </button>
                </div>
            </td>
        `;
        return row;
    }

    // Инициализация записей
    async function initializeRecords() {
        populateRecords();
        populateNewRecords();
    }

    // Удаление записи из расписания клиента
    function deleteFromClientScheduleRecords(recordId) {
        let clientScheduleRecords = JSON.parse(localStorage.getItem('clientScheduleRecords')) || [];
        clientScheduleRecords = clientScheduleRecords.filter(record => record.запись_id !== recordId);
        localStorage.setItem('clientScheduleRecords', JSON.stringify(clientScheduleRecords));
    }

    // Обработчики событий
    function attachEventHandlers() {
        recordsTableBody.addEventListener('click', handleRecordTableClick);
        newRecordsTableBody.addEventListener('click', handleNewRecordTableClick);
    }

    // Обработка кликов по таблице записей
    function handleRecordTableClick(event) {
        const button = event.target.closest('button');
        if (!button) return;

        const row = button.closest('tr');
        const recordId = row.querySelector('td').textContent;
        const statusCell = row.querySelector('.status');

        if (button.classList.contains('record-button--info')) {
            showRecordDetails(recordId);
        } else if (button.classList.contains('record-button--delete')) {
            deleteRecord(recordId, row);
        } else if (button.classList.contains('record-button--confirm')) {
            updateRecordStatus(recordId, 'Подтверждена', statusCell);
        } else if (button.classList.contains('record-button--reject')) {
            updateRecordStatus(recordId, 'Отклонена', statusCell);
        }
    }

    // Обработка кликов по таблице новых записей
    function handleNewRecordTableClick(event) {
        const button = event.target.closest('button');
        if (!button) return;

        const row = button.closest('tr');
        const recordId = row.querySelector('.record-button--info').dataset.id;
        const statusCell = row.querySelector('.status');

        if (button.classList.contains('record-button--info')) {
            showRecordDetails(recordId);
        } else if (button.classList.contains('record-button--confirm')) {
            updateRecordStatus(recordId, 'Подтверждена', statusCell);
            row.remove();
        } else if (button.classList.contains('record-button--reject')) {
            updateRecordStatus(recordId, 'Отклонена', statusCell);
            row.remove();
        }
    }

    // Удаление записи
    async function deleteRecord(recordId, row) {
        row.remove(); // Удаляем строку из таблицы

        try {
            // Отправляем запрос на сервер для удаления записи из базы данных
            const response = await fetch(`/api/appointments/${recordId}`, {
                method: 'DELETE',
            });

            // Проверяем, успешен ли запрос
            if (!response.ok) {
                throw new Error("Не удалось удалить запись из базы данных");
            }

            // Ожидаем выполнения промиса и получения обновленных записей
            let records = await getRecordsFromServer();

            // Проверяем, что records является массивом
            if (!Array.isArray(records)) {
                console.error("Ошибка: records не является массивом", records);
                return;
            }

            // Фильтруем записи (хотя они уже удалены на сервере, можно обновить клиентские данные)
            records = records.filter(record => record.запись_id !== recordId);

            // Выполняем дальнейшие действия
            deleteFromClientScheduleRecords(recordId);
            refreshTables();
        } catch (error) {
            console.error("Ошибка при удалении записи:", error);
        }
    }




    async function updateRecordStatus(recordId, status, statusCell) {
        try {
            const response = await fetch(`/api/appointments/${recordId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ статус: status }),  // Используем 'статус' вместо 'status'
            });

            if (!response.ok) throw new Error('Ошибка обновления статуса записи');

            // Обновляем статус записи в ячейке таблицы
            statusCell.textContent = status;
            statusCell.className = `status ${getStatusClass(status)}`;

            // Обновляем таблицы
            refreshTables();
        } catch (error) {
            console.error('Ошибка при обновлении статуса:', error);
        }
    }




    async function showRecordDetails(recordId) {
        try {
            const records = await getRecordsFromServer();
            const record = records.find(r => r.запись_id === Number(recordId));  // Приводим к числу

            if (!record) {
                console.error("Запись не найдена");
                return;
            }

            document.getElementById('detailId').textContent = record.запись_id;
            document.getElementById('detailClientName').textContent = record.клиент_имя;
            document.getElementById('detailPhone').textContent = record.клиент_телефон;
            document.getElementById('detailEmail').textContent = record.клиент_email;
            document.getElementById('detailMaster').textContent = record.мастер_имя;
            document.getElementById('detailService').textContent = record.услуга_название;
            document.getElementById('detailPrice').textContent = `${record.услуга_цена} ₽`;
            document.getElementById('detailDate').textContent = record.дата_время;

            const statusElement = document.getElementById('detailStatus');
            statusElement.textContent = record.статус;
            statusElement.className = `status ${getStatusClass(record.статус)}`;

            detailsModal.style.display = 'flex';
        } catch (error) {
            console.error("Ошибка при получении данных с сервера:", error);
        }
    }



    // Закрытие модального окна с деталями
    closeDetailsModal.addEventListener('click', () => {
        detailsModal.style.display = 'none';
    });

    window.addEventListener('click', event => {
        if (event.target === detailsModal) {
            detailsModal.style.display = 'none';
        }
    });

    // Обновление таблиц
    function refreshTables() {
        populateRecords();
        populateNewRecords();
    }

// Заполнение таблицы новых записей
    async function populateNewRecords() {
        const records = await getRecordsFromServer();
        newRecordsTableBody.innerHTML = '';
        const awaitingRecords = records.filter(record => record.статус === 'Ожидает подтверждения');

        awaitingRecords.forEach(record => {
            const row = createNewRecordRow(record);
            // Преобразуем строку HTML в DOM-элемент
            const rowElement = document.createRange().createContextualFragment(row).firstChild;
            newRecordsTableBody.appendChild(rowElement);
        });
    }



    // Создание строки для новых записей
    function createNewRecordRow(record) {
        return `
            <tr>
                <td>${record.клиент_имя}</td>
                <td class="status status-awaiting">${record.статус}</td>
                <td>
                    <div class="record-buttons">
                        <button class="record-button record-button--info" data-id="${record.запись_id}">
                            <img src="img/info.svg" alt="Info">
                        </button>
                        <button class="record-button record-button--confirm">
                            <img src="img/confirm.svg" alt="Confirm">
                        </button>
                        <button class="record-button record-button--reject">
                            <img src="img/reject.svg" alt="Reject">
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    // Инициализация записей
    initializeRecords();
    attachEventHandlers();
}
