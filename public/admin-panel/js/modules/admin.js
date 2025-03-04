export function initAdmins() {
    // Проверяем, является ли пользователь суперадмином
    const isSuperadmin = document.querySelector('#adminTab') !== null;
    
    // Если пользователь не суперадмин, не инициализируем функционал
    if (!isSuperadmin) {
        return;
    }

    const adminsTableBody = document.getElementById('adminsTableBody');
    const addAdminButton = document.getElementById('addAdminButton');
    const addAdminModal = document.getElementById('addAdminModal');
    const closeAddAdminModal = document.getElementById('closeAddAdminModal');
    const adminSettingsModal = document.getElementById('adminSettingsModal');
    const closeAdminModal = document.getElementById('closeAdminModal');

    // Проверяем наличие всех необходимых элементов
    if (!adminsTableBody || !addAdminButton || !addAdminModal || !closeAddAdminModal || 
        !adminSettingsModal || !closeAdminModal) {
        console.log('Элементы управления администраторами не найдены');
        return;
    }

    let currentAdminRow;

    async function loadAdminsFromDatabase() {
        const response = await fetch('/api/admins');
        if (!response.ok) {
            throw new Error('Ошибка загрузки данных');
        }
        return await response.json();
    }

    async function saveAdminToDatabase(admin) {
        const response = await fetch('/api/admins', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(admin)
        });
        if (!response.ok) {
            throw new Error('Ошибка сохранения данных');
        }
        return await response.json();
    }

    async function updateAdminInDatabase(updatedAdmin) {
        const adminId = document.getElementById('adminId').textContent.trim();
        const response = await fetch(`/api/admins/${adminId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedAdmin)
        });

        if (!response.ok) {
            throw new Error('Ошибка обновления данных');
        }
        return await response.json();
    }

    async function deleteAdminFromDatabase(adminId) {
        const response = await fetch(`/api/admins/${adminId}`, {
            method: 'DELETE'
        });
        if (!response.ok) {
            throw new Error('Ошибка удаления администратора');
        }
    }

    function populateAdmins() {
        loadAdminsFromDatabase().then(admins => {
            adminsTableBody.innerHTML = '';
            admins.forEach(admin => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="admin-id">${admin.администратор_id}</td>
                    <td class="admin-name">${admin.имя}</td>
                    <td class="admin-login">${admin.логин}</td>
                    <td class="admin-actions">
                        <button class="admin-button admin-button--settings">Настройки</button>
                        <button class="admin-button admin-button--delete">
                            <img src="img/delete-icon.svg" alt="Delete">
                        </button>
                    </td>
                `;
                adminsTableBody.appendChild(row);
            });
            attachEventHandlers();
        }).catch(error => {
            console.error('Ошибка при загрузке администраторов:', error);
        });
    }

    function attachEventHandlers() {
        document.querySelectorAll('.admin-button--delete').forEach(button => {
            button.addEventListener('click', async function() {
                const row = this.closest('tr');
                const adminId = row.querySelector('.admin-id').textContent;
                try {
                    await deleteAdminFromDatabase(adminId);
                    row.remove();
                } catch (error) {
                    console.error('Ошибка удаления администратора:', error);
                }
            });
        });

        document.querySelectorAll('.admin-button--settings').forEach(button => {
            button.addEventListener('click', function() {
                currentAdminRow = this.closest('tr');
                document.getElementById('adminId').textContent = currentAdminRow.querySelector('.admin-id').textContent;
                document.getElementById('adminName').textContent = currentAdminRow.querySelector('.admin-name').textContent;
                document.getElementById('adminLogin').textContent = currentAdminRow.querySelector('.admin-login').textContent;
                adminSettingsModal.style.display = 'flex';
            });
        });
    }

    closeAdminModal.addEventListener('click', function() {
        adminSettingsModal.style.display = 'none';
    });

    document.getElementById('saveAdminSettings').addEventListener('click', async function() {
        const adminId = document.getElementById('adminId').textContent.trim();
        const adminName = document.getElementById('adminName').textContent.trim();
        const adminLogin = document.getElementById('adminLogin').textContent.trim();

        if (currentAdminRow && adminId && adminName && adminLogin) {
            currentAdminRow.querySelector('.admin-name').textContent = adminName;
            currentAdminRow.querySelector('.admin-login').textContent = adminLogin;

            try {
                await updateAdminInDatabase({ имя: adminName, логин: adminLogin });
                adminSettingsModal.style.display = 'none';
            } catch (error) {
                console.error('Ошибка обновления данных: ' + error.message);
            }
        }
    });

    addAdminButton.addEventListener('click', function() {
        document.getElementById('newAdminName').value = '';
        document.getElementById('newAdminLogin').value = '';
        document.getElementById('newAdminPassword').value = '';
        addAdminModal.style.display = 'flex';
    });

    closeAddAdminModal.addEventListener('click', function() {
        addAdminModal.style.display = 'none';
    });

    document.getElementById('saveNewAdmin').addEventListener('click', async function() {
        const newAdminName = document.getElementById('newAdminName').value.trim();
        const newAdminLogin = document.getElementById('newAdminLogin').value.trim();
        const newAdminPassword = document.getElementById('newAdminPassword').value.trim();

        if (!newAdminName || !newAdminLogin || !newAdminPassword) {
            console.error('Пожалуйста, заполните все поля.');
            return;
        }

        try {
            const savedAdmin = await saveAdminToDatabase({ имя: newAdminName, логин: newAdminLogin, пароль: newAdminPassword });
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="admin-id">${savedAdmin.администратор_id}</td>
                <td class="admin-name">${savedAdmin.имя}</td>
                <td class="admin-login">${savedAdmin.логин}</td>
                <td class="admin-actions">
                    <button class="admin-button admin-button--settings">Настройки</button>
                    <button class="admin-button admin-button--delete">
                        <img src="img/delete-icon.svg" alt="Delete">
                    </button>
                </td>
            `;
            adminsTableBody.appendChild(row);
            attachEventHandlers();
            addAdminModal.style.display = 'none';
        } catch (error) {
            console.error('Ошибка сохранения администратора: ' + error.message);
        }
    });

    populateAdmins();
}
