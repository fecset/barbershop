export async function initServices() {
    const modal = document.getElementById('settingsModal');
    const servicesTableBody = document.getElementById('servicesTableBody');
    const addServiceModal = document.getElementById('addServiceModal');

    async function fetchServices() {
        try {
            const response = await fetch('/api/services');
            const data = await response.json();

            // Преобразуем данные с кириллическими ключами в английские
            return data.map(service => ({
                id: service.услуга_id,
                name: service.название,
                price: service.цена,
                specialization: service.специализация
            }));
        } catch (error) {
            console.error('Ошибка при загрузке данных об услугах:', error);
            return [];
        }
    }


    async function populateServices() {
        const services = await fetchServices();

        const servicesContainer = document.querySelector('#servicesTableBody');
        if (!servicesContainer) {
            console.error('Element #servicesTableBody not found!');
            return;
        }

        servicesContainer.innerHTML = ''; // Очищаем контейнер перед добавлением новых данных

        services.forEach(service => {
            const serviceRow = document.createElement('tr'); // Создаем строку для таблицы
            serviceRow.innerHTML = `
                <td class="service__id">${service.id}</td>
                <td class="service__name">${service.name}</td>
                <td class="service__specialization">${service.specialization}</td>
                <td class="service__price">${service.price} ₽</td>
                <td class="service__actions">
                    <button class="service__button service__button--settings" data-id="${service.id}">Настройки</button>
                    <button class="service__button service__button--delete" data-id="${service.id}"><img src="img/delete-icon.svg" alt="Delete"></button>
                </td>
            `;
            servicesContainer.appendChild(serviceRow); // Добавляем строку в таблицу
        });

        attachEventHandlers();
    }

    function attachEventHandlers() {
        document.querySelectorAll('.service__button--delete').forEach(button => {
            button.addEventListener('click', async function() {
                const row = this.closest('tr');
                const serviceId = row.querySelector('.service__id').textContent;
                try {
                    await deleteService(serviceId);
                    row.remove();
                } catch (error) {
                    console.error('Ошибка удаления услуги:', error);
                }
            });
        });

        document.querySelectorAll('.service__button--settings').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const serviceId = row.querySelector('.service__id').textContent;
                const serviceName = row.querySelector('.service__name').textContent;
                const servicePrice = row.querySelector('.service__price').textContent.replace(' ₽', '');
                const serviceSpecialization = row.querySelector('.service__specialization').textContent;

                document.getElementById('serviceId').textContent = serviceId;
                document.getElementById('serviceName').textContent = serviceName;
                document.getElementById('priceValue').textContent = servicePrice;
                document.getElementById('serviceSpecialization').value = serviceSpecialization;

                modal.style.display = 'flex';
            });
        });
        document.getElementById('saveSettings').addEventListener('click', async function() {
            const serviceId = document.getElementById('serviceId').textContent;
            const serviceName = document.getElementById('serviceName').textContent;
            const priceValue = document.getElementById('priceValue').textContent;
            const serviceSpecialization = document.getElementById('serviceSpecialization').value;

            const updatedService = {
                название: serviceName,
                цена: priceValue,
                специализация: serviceSpecialization
            };

            try {
                const response = await fetch(`/api/services/${serviceId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updatedService),
                });

                if (response.ok) {
                    populateServices(); // Обновляем список услуг
                    modal.style.display = 'none'; // Закрываем модальное окно
                } else {
                    console.error('Ошибка при сохранении изменений:', response.statusText);
                }
            } catch (error) {
                console.error('Ошибка при отправке данных на сервер:', error);
            }
        });


        document.querySelectorAll('.edit-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                const fieldToEdit = this.closest('td').previousElementSibling.querySelector('span');

                if (fieldToEdit) {
                    fieldToEdit.contentEditable = 'true';
                    fieldToEdit.focus();

                    fieldToEdit.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            this.contentEditable = 'false';
                        }
                    });
                }
            });
        });
    }

    populateServices().catch(error => {
        console.error('Ошибка при загрузке услуг:', error);
    });


    document.getElementById('closeAddServiceModal').addEventListener('click', function() {
        document.getElementById('addServiceModal').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    const priceValueField = document.getElementById('priceValue');
    const serviceNameField = document.getElementById('serviceName');

    priceValueField.addEventListener('keydown', handleKeyDown);
    serviceNameField.addEventListener('keydown', handleAlphabeticKey);

    function handleKeyDown(event) {
        const maxLength = getMaxLength(this.id);
        if (event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab' || event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            return;
        }

        if (!isNumberKey(event) || this.textContent.length >= maxLength) {
            event.preventDefault();
        }
        if (this.id === 'priceValue' && this.textContent.trim() === '') {
            this.textContent = '0';
        }
    }

    function handleAlphabeticKey(event) {
        const maxLength = getMaxLength(this.id);
        if (event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab' || event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            return;
        }

        if (!isAlphabeticKey(event) || this.textContent.length >= maxLength) {
            event.preventDefault();
        }
    }

    function getMaxLength(id) {
        switch (id) {
            case 'serviceName': return 30;
            case 'priceValue': return 10;
            default: return Infinity;
        }
    }

    function isNumberKey(event) {
        const charCode = event.which || event.keyCode;
        return (
            (charCode >= 48 && charCode <= 57) ||
            (charCode >= 96 && charCode <= 105) ||
            charCode === 8 ||
            charCode === 46 ||
            charCode === 9 ||
            (charCode >= 37 && charCode <= 40)
        );
    }

    function isAlphabeticKey(event) {
        const charCode = event.which || event.keyCode;
        return (
            (charCode >= 65 && charCode <= 90) ||
            (charCode >= 97 && charCode <= 122) ||
            charCode === 32 ||
            charCode === 8 ||
            charCode === 46 ||
            charCode === 9 ||
            (charCode >= 37 && charCode <= 40)
        );
    }


     document.getElementById('addServiceButton').addEventListener('click', function() {
        document.getElementById('newServiceName').value = '';
        document.getElementById('newServicePrice').value = '';
        document.getElementById('addServiceModal').style.display = 'flex';
    });

    document.getElementById('closeAddServiceModal').addEventListener('click', function() {
        document.getElementById('addServiceModal').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('addServiceModal')) {
            addServiceModal.style.display = 'none';
        }
    });

    document.getElementById('saveNewService').addEventListener('click', async function() {
        const newServiceName = document.getElementById('newServiceName').value.trim();
        const newServicePrice = document.getElementById('newServicePrice').value.trim();
        const newServiceSpecialization = document.getElementById('newServiceSpecialization').value;

        if (!newServiceName || !newServicePrice || !newServiceSpecialization) {
            console.error('Пожалуйста, заполните все поля.');
            return;
        }

        if (!/^[a-zA-Zа-яА-Я\s]+$/.test(newServiceName)) {
            console.error('Название услуги может содержать только буквы.');
            return;
        }

        if (isNaN(newServicePrice) || newServicePrice <= 0) {
            console.error('Введите корректную цену.');
            return;
        }

        const newService = {
            название: newServiceName,
            цена: newServicePrice,
            специализация: newServiceSpecialization
        };

        try {
            const response = await fetch('/api/services', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(newService)
            });

            if (response.ok) {
                populateServices(); // Обновляем список услуг
            } else {
                console.error('Ошибка при добавлении услуги:', response.statusText);
            }
        } catch (error) {
            console.error('Ошибка при отправке данных на сервер:', error);
        }

        // Очистить форму и скрыть модальное окно
        document.getElementById('newServiceName').value = '';
        document.getElementById('newServicePrice').value = '';
        document.getElementById('newServiceSpecialization').value = '';
        document.getElementById('addServiceModal').style.display = 'none';
    });

    document.getElementById('newServiceName').addEventListener('input', function() {
        const nameError = document.getElementById('nameError');
        const value = this.value;
        if (!/^[a-zA-Zа-яА-Я\s]*$/.test(value)) {
            nameError.textContent = 'Название услуги может содержать только буквы.';
            this.value = value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
        } else {
            nameError.textContent = '';
        }
    });

    document.getElementById('newServicePrice').addEventListener('input', function() {
        const maxValue = 1000000;
        const priceError = document.getElementById('priceError');

        if (this.value > maxValue) {
            priceError.textContent = `Цена не может превышать ${maxValue} ₽.`;
            this.value = maxValue;
        } else {
            priceError.textContent = '';
        }

        if (this.value < 0) {
            priceError.textContent = 'Цена не может быть отрицательной.';
            this.value = '';
        }
    });


    document.getElementById('closeSettingsModal').addEventListener('click', function() {
        document.getElementById('settingsModal').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    populateServices();
}
