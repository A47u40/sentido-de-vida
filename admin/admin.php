<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$editing_psy = null;

// Manejar la eliminación
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM psychologists WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Psicóloga eliminada con éxito.";
    } else {
        $message = "Error al eliminar: " . $stmt->error;
    }
    $stmt->close();
}

// Manejar la edición (carga de datos en el formulario)
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM psychologists WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editing_psy = $result->fetch_assoc();
    $stmt->close();
}

// Manejar la acción del formulario (agregar o actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $career = $_POST['career'];
    $about = $_POST['about'];
    $modal_about = $_POST['modal_about'];
    $education = $_POST['education'];
    $specialties_string = $_POST['specialties'];
    $experience = $_POST['experience'];
    $approach = $_POST['approach'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $psy_id = isset($_POST['psy_id']) ? $_POST['psy_id'] : null;

    $specialties = json_encode(explode(',', $specialties_string));
    
    // Subida de foto
    $photo_url = isset($editing_psy['photo_url']) ? $editing_psy['photo_url'] : 'https://em-content.zobj.net/source/apple/354/person-feeding-baby_1f469-200d-1f37c.png';

    // Procesar la nueva foto solo si se ha subido una
    // Lógica para manejar la foto
$photo_url = $_POST['existing_photo'] ?? 'https://em-content.zobj.net/source/apple/354/person-feeding-baby_1f469-200d-1f37c.png';

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["photo"]["tmp_name"]);

    if($check !== false) {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_url = $target_file;
        } else {
            $message = "Error al subir la imagen.";
        }
    } else {
        $message = "El archivo no es una imagen.";
    }
}
    if ($psy_id) {
        // Actualizar datos
        $stmt = $conn->prepare("UPDATE psychologists SET name=?, career=?, about=?, modal_about=?, education=?, specialties=?, experience=?, approach=?, photo_url=?, phone=?, email=? WHERE id=?");
        $stmt->bind_param("sssssssssssi", $name, $career, $about, $modal_about, $education, $specialties, $experience, $approach, $photo_url, $phone, $email, $psy_id);
    } else {
        // Insertar nuevos datos
        $stmt = $conn->prepare("INSERT INTO psychologists (name, career, about, modal_about, education, specialties, experience, approach, photo_url, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $name, $career, $about, $modal_about, $education, $specialties, $experience, $approach, $photo_url, $phone, $email);
    }
    
    if ($stmt->execute()) {
        $message = $psy_id ? "Psicóloga actualizada con éxito." : "Psicóloga agregada con éxito.";
        $editing_psy = null; // Reiniciar el formulario
        header("Location: admin.php"); // Redirigir para limpiar parámetros de URL
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Obtener todas las psicólogas para la lista
$all_psychologists_result = $conn->query("SELECT * FROM psychologists");
$all_psychologists = [];
if ($all_psychologists_result->num_rows > 0) {
    while($row = $all_psychologists_result->fetch_assoc()) {
        $all_psychologists[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="admin-panel">
    <div class="admin-panel-header">
        <h1>Panel de Administración</h1>
        <a href="logout.php">Cerrar Sesión</a>
    </div>

    <?php if ($message): ?>
        <p style="color: green; text-align: center;"><?php echo $message; ?></p>
    <?php endif; ?>

    <h2 class="admin-section-title"><?php echo $editing_psy ? 'Editar Psicóloga' : 'Agregar Nueva Psicóloga'; ?></h2>

    <form action="admin.php" method="post" enctype="multipart/form-data">
        <?php if ($editing_psy): ?>
            <input type="hidden" name="psy_id" value="<?php echo $editing_psy['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Nombre Completo:</label>
            <input type="text" id="name" name="name" value="<?php echo $editing_psy ? htmlspecialchars($editing_psy['name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="career">Carrera/Título:</label>
            <input type="text" id="career" name="career" value="<?php echo $editing_psy ? htmlspecialchars($editing_psy['career']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="about">Acerca de (Texto corto para la tarjeta):</label>
            <textarea id="about" name="about" required><?php echo $editing_psy ? htmlspecialchars($editing_psy['about']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="photo">Foto (Si no se selecciona una, se mantendrá la actual):</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <?php if (isset($editing_psy['photo_url'])): ?>
                <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($editing_psy['photo_url']); ?>">
            <?php endif; ?>
            <?php if ($editing_psy && $editing_psy['photo_url']): ?>
                <div class="current-photo">
                    <label for="photo">Foto Actual:</label>
                    <img src="<?php echo htmlspecialchars($editing_psy['photo_url']); ?>" alt="Foto actual" style="max-width: 150px; max-height: 150px;">
                </div>
            <?php endif; ?>
        </div>

        <h2 class="admin-section-title">Detalles del Modal</h2>
        <div class="form-group">
            <label for="modal_about">Acerca de Mí (Para el modal):</label>
            <textarea id="modal_about" name="modal_about" required><?php echo $editing_psy ? htmlspecialchars($editing_psy['modal_about']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="education">Formación Académica (Separar cada elemento con un salto de línea):</label>
            <textarea id="education" name="education" required><?php echo $editing_psy ? htmlspecialchars($editing_psy['education']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="specialties">Especialidades (Separar por comas, ej: Ansiedad, Depresión):</label>
            <input type="text" id="specialties" name="specialties" value="<?php echo $editing_psy ? htmlspecialchars(implode(',', json_decode($editing_psy['specialties']))) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="experience">Experiencia:</label>
            <textarea id="experience" name="experience" required><?php echo $editing_psy ? htmlspecialchars($editing_psy['experience']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="approach">Mi Enfoque Terapéutico:</label>
            <textarea id="approach" name="approach" required><?php echo $editing_psy ? htmlspecialchars($editing_psy['approach']) : ''; ?></textarea>
        </div>

        <h2 class="admin-section-title">Datos de Contacto</h2>
        <div class="form-group">
            <label for="phone">Teléfono:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $editing_psy ? htmlspecialchars($editing_psy['phone']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="text" id="email" name="email" value="<?php echo $editing_psy ? htmlspecialchars($editing_psy['email']) : ''; ?>">
        </div>

        <button type="submit" class="btn-submit"><?php echo $editing_psy ? 'Actualizar Datos' : 'Guardar Psicóloga'; ?></button>
    </form>

    <hr style="margin: 40px 0; border: 1px dashed var(--border-color);">

    <div class="therapist-list">
        <h2 class="admin-section-title">Psicólogas Registradas</h2>
        <?php if (empty($all_psychologists)): ?>
            <p>No hay psicólogas registradas.</p>
        <?php else: ?>
            <?php foreach ($all_psychologists as $psy): ?>
                <div class="therapist-list-item">
                    <div class="therapist-info">
                        <div class="therapist-photo">
                            <img src="<?php echo htmlspecialchars($psy['photo_url']); ?>" alt="Foto">
                        </div>
                        <div class="therapist-details">
                            <h3><?php echo htmlspecialchars($psy['name']); ?></h3>
                            <p><?php echo htmlspecialchars($psy['career']); ?></p>
                        </div>
                    </div>
                    <div class="therapist-actions">
                        <a href="admin.php?edit_id=<?php echo $psy['id']; ?>" class="btn-edit">Editar</a>
                        <a href="admin.php?delete_id=<?php echo $psy['id']; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar a <?php echo htmlspecialchars($psy['name']); ?>?');">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="admin-panel">
        
        <div style="margin: 40px 0; border: 1px dashed var(--border-color);"></div>

        <h2 class="admin-section-title">Gestionar Citas</h2>
        <form id="admin-appointment-form" action="appointment_handler.php" method="POST">
            <input type="hidden" name="action" value="create_appointment">
            <div class="form-group">
                <label for="admin_psychologist_select">Psicólogo/a</label>
                <select class="form-control" id="admin_psychologist_select" name="psychologist_id" required>
                    <option value="">Selecciona un terapeuta</option>
                    <?php foreach ($all_psychologists as $psy): ?>
                        <option value="<?php echo htmlspecialchars($psy['id']); ?>">
                            <?php echo htmlspecialchars($psy['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="admin_patient_name">Nombre del Paciente</label>
                <input type="text" class="form-control" id="admin_patient_name" name="patient_name" required>
            </div>
            <div class="form-group">
                <label for="admin_contact_number">Número de Contacto</label>
                <input type="tel" class="form-control" id="admin_contact_number" name="contact_number" required>
            </div>
            <div class="form-group">
                <label for="admin_email">Correo Electrónico</label>
                <input type="email" class="form-control" id="admin_email" name="email" required>
            </div>
            <div class="form-group">
                <label for="admin_appointment_date">Día de la Consulta</label>
                <input type="date" class="form-control" id="admin_appointment_date" name="appointment_date" required>
            </div>
            <div class="form-group">
                <label for="admin_appointment_time">Hora</label>
                <input type="time" class="form-control" id="admin_appointment_time" name="appointment_time" required>
            </div>
            <button type="submit" class="btn btn-primary">Agendar Cita (Admin)</button>
        </form>

        <h3 class="admin-section-title mt-5">Citas Registradas</h3>
        <div class="appointment-list">
            <?php
            $sql_appointments = "SELECT a.*, p.name AS psychologist_name FROM appointments a JOIN psychologists p ON a.psychologist_id = p.id ORDER BY a.appointment_date DESC, a.appointment_time DESC";
            $result_appointments = $conn->query($sql_appointments);

            if ($result_appointments->num_rows > 0) {
                while($appointment = $result_appointments->fetch_assoc()) {
                    ?>
                    <div class="appointment-list-item">
                        <div class="appointment-info">
                            <strong>Psicólogo/a:</strong> <?php echo htmlspecialchars($appointment['psychologist_name']); ?><br>
                            <strong>Paciente:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?><br>
                            <strong>Teléfono:</strong> <?php echo htmlspecialchars($appointment['contact_number']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?><br>
                            <strong>Fecha:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?><br>
                            <strong>Hora:</strong> <?php echo htmlspecialchars(date('H:i', strtotime($appointment['appointment_time']))); ?><br>
                            <strong>Estado:</strong> <?php echo htmlspecialchars($appointment['status']); ?>
                        </div>
                        <div class="appointment-actions">
                            <a href="appointment_handler.php?action=cancel&id=<?php echo $appointment['id']; ?>" class="btn btn-delete">Cancelar</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No hay citas registradas.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>