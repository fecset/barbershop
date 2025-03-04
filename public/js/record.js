document.addEventListener('DOMContentLoaded', function () {
    let calendar;
    let masterSchedule = {};
    
    function initializeCalendar() {
        if (calendar) {
            calendar.destroy(); 
        }

        calendar = flatpickr("#date-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    longhand: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"]
                },
                months: {
                    shorthand: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
                    longhand: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
                },
                time_24hr: true,
            },
            disable: [],
            onChange: function (selectedDates, dateStr, instance) {
                updateAvailableTimes(selectedDates[0]); 
            }
        });
    }

    initializeCalendar();

    function fetchMasterSchedule(masterId) {
        fetch(`/api/master-schedule/${masterId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error || !data.length) {
                    alert("Ошибка: нет данных о графике мастера");
                    return;
                }

                masterSchedule = data[0]; 
                updateCalendarForMaster();

                if (calendar.selectedDates.length > 0) {
                    updateAvailableTimes(calendar.selectedDates[0]);
                }
            })
            .catch(error => {
                console.error('Ошибка при загрузке рабочего графика:', error);
            });
    }

    function updateCalendarForMaster() {
        if (!masterSchedule.days) return;

        const workingDays = masterSchedule.days; 
        const startHour = parseInt(masterSchedule.startTime.split(':')[0]);
        const endHour = parseInt(masterSchedule.endTime.split(':')[0]);

        if (calendar) {
            calendar.destroy(); 
        }

        calendar = flatpickr("#date-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    longhand: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"]
                },
                months: {
                    shorthand: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
                    longhand: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"]
                },
                time_24hr: true,
            },
            disable: [
                function (date) {
                    return !workingDays.includes(date.getDay()); 
                }
            ],
            minTime: `${startHour}:00`,  
            maxTime: `${endHour}:00`,    
            onChange: function (selectedDates) {
                updateAvailableTimes(selectedDates[0]); 
            }
        });
    }

    function updateAvailableTimes(selectedDate) {
        if (!selectedDate || !masterSchedule || !masterSchedule.days) {
            return;
        }
        const startHour = parseInt(masterSchedule.startTime.split(':')[0]);
        const endHour = parseInt(masterSchedule.endTime.split(':')[0]);

        calendar.config.minTime = `${startHour}:00`; 
        calendar.config.maxTime = `${endHour}:00`;   
    }

    document.getElementById('master').addEventListener('change', function (event) {
        const masterId = event.target.value;
        if (masterId) {
            document.getElementById('date-picker').disabled = false; 
            fetchMasterSchedule(masterId); 
        } else {
            document.getElementById('date-picker').disabled = true; 
        }
    });

    document.getElementById('service').addEventListener('change', function (event) {
        const serviceId = event.target.value;
        if (serviceId) {
            document.getElementById('date-picker').disabled = false; 
        } else {
            document.getElementById('date-picker').disabled = true; 
        }
    });
});

document.addEventListener('DOMContentLoaded', function(){
    var serviceSelect = document.getElementById('service');
    if (!serviceSelect) {
        console.error('Элемент с id "service" не найден');
        return;
    }

    serviceSelect.addEventListener('change', function(){
        var serviceId = this.value;
        var masterSelect = document.getElementById('master');
        if (!masterSelect) {
            console.error('Элемент с id "master" не найден');
            return;
        }
        if (serviceId) {
            fetch('/masters-by-service/' + serviceId)
                .then(response => response.json())
                .then(data => {
                    masterSelect.innerHTML = '<option value="">Выберите мастера</option>';
                    data.forEach(function(master) {
                        var option = document.createElement('option');
                        option.value = master.мастер_id; 
                        option.textContent = master.имя; 
                        masterSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Ошибка при получении мастеров:', error);
                });
        } else {
            masterSelect.innerHTML = '<option value="">Выберите мастера</option>';
        }
    });
});



