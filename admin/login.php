<?php
session_start();
include 'db.php';

// Si el usuario ya ha iniciado sesión, redirigirlo al panel de administración
if (isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Usar consultas preparadas para prevenir inyecciones SQL
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verificar la contraseña hasheada
        if (password_verify($password, $user['password'])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            header('Location: admin.php');
            exit();
        } else {
            $message = "Contraseña incorrecta.";
        }
    } else {
        $message = "Usuario no encontrado.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión Admin</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <h1>Iniciar Sesión Admin</h1>
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Ingresar</button>
    </form>
</div>

</body>
</html>