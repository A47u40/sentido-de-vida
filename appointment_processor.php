<?php
include 'admin/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'check_availability') {
        $date = $_POST['date'] ?? '';
        $psychologist_id = $_POST['psychologist_id'] ?? '';

        if (empty($date) || empty($psychologist_id)) {
            echo json_encode(['success' => false, 'message' => 'Fecha o psicólogo no especificado.']);
            exit;
        }

        $all_hours = [];
        $start_time = 8; // 8 AM
        $end_time = 18;  // 6 PM

        for ($i = $start_time; $i < $end_time; $i++) {
            $all_hours[] = sprintf('%02d:00', $i);
        }

        $sql = "SELECT appointment_time FROM appointments WHERE appointment_date = ? AND psychologist_id = ? AND status = 'Agendada - Pendiente'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $date, $psychologist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booked_hours = [];
        while ($row = $result->fetch_assoc()) {
            $booked_hours[] = date('H:i', strtotime($row['appointment_time']));
        }
        $stmt->close();

        $available_hours = array_diff($all_hours, $booked_hours);
        echo json_encode(['success' => true, 'available_hours' => array_values($available_hours)]);
        exit;

    } elseif ($action === 'book_appointment') {
        $psychologist_id = $_POST['psychologist_id'] ?? '';
        $patient_name = $_POST['patient_name'] ?? '';
        $contact_number = $_POST['contact_number'] ?? '';
        $email = $_POST['email'] ?? '';
        $appointment_date = $_POST['appointment_date'] ?? '';
        $appointment_time = $_POST['appointment_time'] ?? '';

        if (empty($psychologist_id) || empty($patient_name) || empty($contact_number) || empty($email) || empty($appointment_date) || empty($appointment_time)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // Check for existing appointment for the same patient
        $sql_check_patient = "SELECT id FROM appointments WHERE patient_name = ? AND status = 'Agendada - Pendiente'";
        $stmt_check_patient = $conn->prepare($sql_check_patient);
        $stmt_check_patient->bind_param("s", $patient_name);
        $stmt_check_patient->execute();
        $result_check_patient = $stmt_check_patient->get_result();
        if ($result_check_patient->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'El paciente ya tiene una cita vigente.']);
            $stmt_check_patient->close();
            exit;
        }
        $stmt_check_patient->close();

        // Check if the time slot is still available
        $sql_check_slot = "SELECT id FROM appointments WHERE psychologist_id = ? AND appointment_date = ? AND appointment_time = ? AND status = 'Agendada - Pendiente'";
        $stmt_check_slot = $conn->prepare($sql_check_slot);
        $stmt_check_slot->bind_param("iss", $psychologist_id, $appointment_date, $appointment_time);
        $stmt_check_slot->execute();
        $result_check_slot = $stmt_check_slot->get_result();
        if ($result_check_slot->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'La hora seleccionada ya no está disponible.']);
            $stmt_check_slot->close();
            exit;
        }
        $stmt_check_slot->close();

        $sql_insert = "INSERT INTO appointments (psychologist_id, patient_name, contact_number, email, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("isssss", $psychologist_id, $patient_name, $contact_number, $email, $appointment_date, $appointment_time);

        if ($stmt_insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cita agendada con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agendar la cita.']);
        }
        $stmt_insert->close();
    }
}
$conn->close();
?>