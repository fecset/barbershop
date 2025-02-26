export function initMasters() {
    const mastersTableBody = document.getElementById('mastersTableBody');
    const addMasterButton = document.getElementById('addMasterButton');
    const addMasterModal = document.getElementById('addMasterModal');
    const closeAddMasterModal = document.getElementById('closeAddMasterModal');
    const masterSettingsModal = document.getElementById('masterSettingsModal');
    const closeMasterModal = document.getElementById('closeMasterModal');

    let currentMasterRow;

    // Загрузка мастеров с сервера
    async function loadMastersFromDatabase() {
        const response = await fetch('/api/masters');
        if (!response.ok) {
            throw new Error('Ошибка загрузки данных');
        }
        const masters = await response.json();
        return masters;
    }

    // Сохранение нового мастера на сервер
    async function saveMasterToDatabase(master) {
        const response = await fetch('/api/masters', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(master)
        });

        if (!response.ok) {
            throw new Error('Ошибка сохранения данных');
        }
        return await response.json();
    }

    // Обновление данных мастера на сервере
    async function updateMasterInDatabase(updatedMaster) {
        const masterId = document.getElementById('masterId').textContent.trim();
        const response = await fetch(`/api/masters/${masterId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(updatedMaster),
        });

        if (!response.ok) {
            throw new Error('Ошибка обновления данных');
        }

        const data = await response.json();
        return data;
    }

    async function deleteMasterFromDatabase(masterId) {
        const response = await fetch(`/api/masters/${masterId}`, {
            method: 'DELETE'
        });

        if (!response.ok) {
            throw new Error('Ошибка удаления мастера');
        }
    }

    // Заполнение таблицы мастерами
    function populateMasters() {
        loadMastersFromDatabase().then(masters => {
            mastersTableBody.innerHTML = '';
            masters.forEach(master => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="master-id">${master.мастер_id}</td>
                    <td class="master-name">${master.имя}</td>
                    <td class="master-specialization">${master.специализация}</td>
                    <td class="master-schedule">${master.график_работы}</td>
                    <td class="master-actions">
                        <button class="master-button master-button--settings">Настройки</button>
                        <button class="master-button master-button--delete">
                            <img src="img/delete-icon.svg" alt="Delete">
                        </button>
                    </td>
                `;
                mastersTableBody.appendChild(row);
            });
            attachEventHandlers();
        }).catch(error => {
            alert('Ошибка при загрузке мастеров: ' + error.message);
        });
    }

    // Привязка обработчиков событий
    function attachEventHandlers() {
        // Удаление мастера
        document.querySelectorAll('.master-button--delete').forEach(button => {
            button.addEventListener('click', async function() {
                const row = this.closest('tr');
                const masterId = row.querySelector('.master-id').textContent;

                try {
                    await deleteMasterFromDatabase(masterId);
                    row.remove();
                } catch (error) {
                    alert('Ошибка удаления мастера: ' + error.message);
                }
            });
        });

        // Открытие модального окна для редактирования мастера
        document.querySelectorAll('.master-button--settings').forEach(button => {
            button.addEventListener('click', function() {
                currentMasterRow = this.closest('tr');
                const masterId = currentMasterRow.querySelector('.master-id').textContent;
                const masterName = currentMasterRow.querySelector('.master-name').textContent;
                const masterSpecialization = currentMasterRow.querySelector('.master-specialization').textContent || 'Стрижка и укладка';
                const masterSchedule = currentMasterRow.querySelector('.master-schedule').textContent;

                document.getElementById('masterId').textContent = masterId;
                document.getElementById('masterName').textContent = masterName;
                document.getElementById('editMasterSpecialization').value = masterSpecialization;
                document.getElementById('masterSchedule').textContent = masterSchedule;

                masterSettingsModal.style.display = 'flex';
            });
        });
    }

    // Закрытие модального окна для редактирования мастера
    closeMasterModal.addEventListener('click', function() {
        masterSettingsModal.style.display = 'none';
    });

    // Обработчик события сохранения данных мастера
    document.getElementById('saveMasterSettings').addEventListener('click', async function() {
        const masterId = document.getElementById('masterId').textContent.trim();
        const masterName = document.getElementById('masterName').textContent.trim();
        const masterSpecialization = document.getElementById('editMasterSpecialization').value;
        const masterSchedule = document.getElementById('masterSchedule').textContent.trim();

        if (currentMasterRow && masterId && masterName && masterSpecialization && masterSchedule) {
            currentMasterRow.querySelector('.master-name').textContent = masterName;
            currentMasterRow.querySelector('.master-specialization').textContent = masterSpecialization;
            currentMasterRow.querySelector('.master-schedule').textContent = masterSchedule;

            const updatedMaster = {
                имя: masterName,
                специализация: masterSpecialization,
                график_работы: masterSchedule
            };

            try {
                await updateMasterInDatabase(updatedMaster);
                masterSettingsModal.style.display = 'none';
            } catch (error) {
                alert('Ошибка обновления данных: ' + error.message);
            }
        }
    });

    // Открытие модального окна для добавления нового мастера
    addMasterButton.addEventListener('click', function() {
        document.getElementById('newMasterName').value = '';
        document.getElementById('newMasterSpecialization').value = 'Стрижка и укладка';
        document.getElementById('newMasterSchedule').value = '';
        addMasterModal.style.display = 'flex';
    });

    // Закрытие модального окна для добавления нового мастера
    closeAddMasterModal.addEventListener('click', function() {
        addMasterModal.style.display = 'none';
    });

    // Сохранение нового мастера
    document.getElementById('saveNewMaster').addEventListener('click', async function() {
        const newMasterName = document.getElementById('newMasterName').value.trim();
        const newMasterSpecialization = document.getElementById('newMasterSpecialization').value.trim();
        const newMasterSchedule = document.getElementById('newMasterSchedule').value.trim();

        if (!newMasterName || !newMasterSpecialization || !newMasterSchedule) {
            alert("Пожалуйста, заполните все поля.");
            return;
        }

        const newMaster = {
            имя: newMasterName,
            специализация: newMasterSpecialization,
            график_работы: newMasterSchedule
        };

        try {
            const savedMaster = await saveMasterToDatabase(newMaster);

            // Добавление мастера в таблицу
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="master-id">${savedMaster.master.мастер_id}</td>
            <td class="master-name">${savedMaster.master.имя}</td>
            <td class="master-specialization">${savedMaster.master.специализация}</td>
            <td class="master-schedule">${savedMaster.master.график_работы}</td>
            <td class="master-actions">
                <button class="master-button master-button--settings">Настройки</button>
                <button class="master-button master-button--delete">
                    <img src="img/delete-icon.svg" alt="Delete">
                </button>
            </td>
        `;
            mastersTableBody.appendChild(row);
            attachEventHandlers();

            // Закрытие модального окна
            addMasterModal.style.display = 'none';
        } catch (error) {
            console.error('Ошибка сохранения мастера:', error);
            alert('Ошибка сохранения данных: ' + error.message);
        }
    });




    // Загрузка мастеров при инициализации страницы
    populateMasters();
}
