export function initTabSwitching() {
    const links = document.querySelectorAll('.sidebar__link');
    const servicesSection = document.getElementById('servicesSection');
    const mastersSection = document.getElementById('mastersSection');
    const recordsSection = document.getElementById('recordsSection');
    const mainContentSection = document.getElementById('main-content');
    const adminSection = document.getElementById('adminSection');

    function showTab(tabName) {
        const role = localStorage.getItem('role');
        const servicesSection = document.getElementById('servicesSection');
        const mastersSection = document.getElementById('mastersSection');
        const recordsSection = document.getElementById('recordsSection');
        const mainContentSection = document.getElementById('main-content');
        const adminSection = document.getElementById('adminSection');

        if (servicesSection) servicesSection.style.display = 'none';
        if (mastersSection) mastersSection.style.display = 'none';
        if (recordsSection) recordsSection.style.display = 'none';
        if (mainContentSection) mainContentSection.style.display = 'none';
        if (adminSection) adminSection.style.display = 'none';

        let activeLink;
        switch(tabName) {
            case 'services':
                if (servicesSection) servicesSection.style.display = 'block';
                activeLink = document.querySelector('[data-icon="services"]');
                break;
            case 'masters':
                if (mastersSection) mastersSection.style.display = 'block';
                activeLink = document.querySelector('[data-icon="masters"]');
                break;
            case 'records':
                if (recordsSection) recordsSection.style.display = 'block';
                activeLink = document.querySelector('[data-icon="records"]');
                break;
            case 'admin':
                if (adminSection) adminSection.style.display = 'block';
                activeLink = document.querySelector('[data-icon="admin"]');
                break;
            default:
                if (mainContentSection) mainContentSection.style.display = 'flex';
                activeLink = document.querySelector('[data-icon="home"]');
        }

        document.querySelectorAll('.sidebar__link').forEach(link => link.classList.remove('sidebar__link--active'));
        if (activeLink) {
            activeLink.classList.add('sidebar__link--active');
            const img = activeLink.querySelector('img');
            if (img) {
                img.src = `img/${activeLink.getAttribute('data-icon')}-active.svg`;
            }
        }

        localStorage.setItem('activeTab', tabName);
    }

    window.showTab = showTab;

    links.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            let tabName;
            if (this.textContent.includes('Управление услугами')) {
                tabName = 'services';
            } else if (this.textContent.includes('Мастера')) {
                tabName = 'masters';
            } else if (this.textContent.includes('Записи')) {
                tabName = 'records';
            } else if (this.textContent.includes('Администраторы')) {
                tabName = 'admin';
            } else if (this.textContent.includes('Главная')) {
                tabName = 'home';
            }

            localStorage.setItem('activeTab', tabName);
            showTab(tabName);
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const activeTab = localStorage.getItem('activeTab') || 'home';
        showTab(activeTab);
    });
}
