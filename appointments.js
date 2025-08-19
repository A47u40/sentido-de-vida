document.addEventListener('DOMContentLoaded', () => {
    const psychologistSelect = document.getElementById('psychologist_select');
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeSelect = document.getElementById('appointment_time');
    const appointmentForm = document.getElementById('appointment-form');
    const formMessage = document.getElementById('form-message');

    // Función para obtener las horas disponibles
    const getAvailableHours = async () => {
        const psychologistId = psychologistSelect.value;
        const date = appointmentDateInput.value;

        if (psychologistId && date) {
            const formData = new FormData();
            formData.append('action', 'check_availability');
            formData.append('psychologist_id', psychologistId);
            formData.append('date', date);

            try {
                const response = await fetch('appointment_processor.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                appointmentTimeSelect.innerHTML = '<option value="">Selecciona una hora</option>';
                if (data.success) {
                    data.available_hours.forEach(hour => {
                        const option = document.createElement('option');
                        option.value = hour;
                        option.textContent = hour;
                        appointmentTimeSelect.appendChild(option);
                    });
                } else {
                    formMessage.textContent = 'Error al cargar las horas: ' + data.message;
                    formMessage.className = 'alert alert-danger';
                }
            } catch (error) {
                formMessage.textContent = 'Error de conexión.';
                formMessage.className = 'alert alert-danger';
            }
        }
    };

    psychologistSelect.addEventListener('change', getAvailableHours);
    appointmentDateInput.addEventListener('change', getAvailableHours);

    // Lógica para enviar el formulario
    appointmentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(appointmentForm);
        formData.append('action', 'book_appointment');

        try {
            const response = await fetch('appointment_processor.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.success) {
                formMessage.textContent = data.message;
                formMessage.className = 'alert alert-success';
                appointmentForm.reset();
                getAvailableHours(); // Recargar horas disponibles
            } else {
                formMessage.textContent = data.message;
                formMessage.className = 'alert alert-danger';
            }
        } catch (error) {
            formMessage.textContent = 'Error de conexión.';
            formMessage.className = 'alert alert-danger';
        }
    });
});