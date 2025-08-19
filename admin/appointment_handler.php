<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logic for creating an appointment from admin panel
    $action = $_POST['action'] ?? '';
    if ($action === 'create_appointment') {
        $psychologist_id = $_POST['psychologist_id'];
        $patient_name = $_POST['patient_name'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];

        $sql = "INSERT INTO appointments (psychologist_id, patient_name, contact_number, email, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $psychologist_id, $patient_name, $contact_number, $email, $appointment_date, $appointment_time);

        if ($stmt->execute()) {
            header("Location: admin.php?message=Cita agendada con éxito");
        } else {
            header("Location: admin.php?error=Error al agendar la cita");
        }
        $stmt->close();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Logic for canceling an appointment
    $action = $_GET['action'] ?? '';
    if ($action === 'cancel' && isset($_GET['id'])) {
        $appointment_id = $_GET['id'];
        $sql = "UPDATE appointments SET status = 'cancelada' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            header("Location: admin.php?message=Cita cancelada con éxito");
        } else {
            header("Location: admin.php?error=Error al cancelar la cita");
        }
        $stmt->close();
    }
}
$conn->close();
?>