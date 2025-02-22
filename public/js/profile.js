let formToSubmit = null;

function confirmDelete(formId) {
    formToSubmit = document.getElementById(formId);
    const modalOverlay = document.getElementById('modal-overlay');
    modalOverlay.classList.add('active');
}

function closeModal() {
    const modalOverlay = document.getElementById('modal-overlay');
    modalOverlay.classList.remove('active');
    formToSubmit = null;
}

document.getElementById('confirm-btn').addEventListener('click', function () {
    if (formToSubmit) {
        formToSubmit.submit();
    }
});
