const myModal = new bootstrap.Modal(document.getElementById('productionModal'));

document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('productionDate_inicio').value = today;

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,
        locale: 'es',
        dateClick: function (info) {
            const clickedDate = info.dateStr;

            if (clickedDate < today) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha no válida',
                    text: 'No puedes seleccionar fechas pasadas.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            document.getElementById('productionDate_fin').value = clickedDate;
            myModal.show();
        }
    });

    calendar.render();
});
