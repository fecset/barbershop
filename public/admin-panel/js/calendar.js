import { loadRecords, state } from './main.js';
import { generateScheduleBody, generateScheduleHeader } from './main.js';

let calA = new Calendar({
    id: "#calendar-a",
    theme: "glass",
    weekdayType: "long-upper",
    monthDisplayType: "long",
    headerColor: "black",
    headerBackgroundColor: "white",
    calendarSize: "medium",
    layoutModifiers: ["month-left-align"],
    eventsData: [],

    dateChanged: async (currentDate, events) => {
        if (currentDate instanceof Date && !isNaN(currentDate)) {
            state.selectedDate = new Date(currentDate.getTime() - currentDate.getTimezoneOffset() * 60000)
                .toISOString()
                .split('T')[0]; 
  
            // Очищаем таблицу
            const scheduleBody = document.getElementById('scheduleBody');
            const mastersRow = document.getElementById('mastersRow');
            scheduleBody.innerHTML = '';
            mastersRow.innerHTML = '';

            try {
                // Получаем актуальный список мастеров
                const response = await fetch('/api/masters');
                const masters = await response.json();
                const filteredMasters = masters.filter(master => master.специализация !== 'Уборка');
                
                // Пересоздаем заголовки и тело таблицы
                generateScheduleHeader(filteredMasters);
                generateScheduleBody(filteredMasters);
                
                // Загружаем записи для новой даты
                await loadRecords(state.selectedDate);
            } catch (error) {
                console.error('Ошибка при обновлении расписания:', error);
            }
        } else {
            console.error("Invalid date value:", currentDate);
        }
    }
});


