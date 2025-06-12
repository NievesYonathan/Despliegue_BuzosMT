document.addEventListener('DOMContentLoaded', initAjaxForms);

function initAjaxForms() {
    document.querySelectorAll('.ajax-form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
}

function handleFormSubmit(e) {
    e.preventDefault();

    const form = e.currentTarget;
    const formData = new FormData(form);
    const url = form.getAttribute('action');
    const method = formData.get('_method') || form.method;
    const emptyInputs = disableEmptyFields(form);

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => handleSuccess(data))
    .catch(error => handleError(error))
    .finally(() => enableFields(emptyInputs));
}

function disableEmptyFields(form) {
    const emptyInputs = form.querySelectorAll('input[value=""], select:not(:checked)');
    emptyInputs.forEach(input => input.disabled = true);
    return emptyInputs;
}

function enableFields(inputs) {
    inputs.forEach(input => input.disabled = false);
}

function handleSuccess(data) {
    if (data.status === 200 || data.status === 201) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: data.message || 'Actualización exitosa',
            timer: 1500
        }).then(() => location.reload());
    } else {
        throw new Error(data.message || 'Error en la actualización');
    }
}

function handleError(error) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.message
    });
}
