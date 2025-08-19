<?php
$servername = "localhost";
$username = "root"; // Ejemplo: root
$password = ""; // Ejemplo: vacío o root
$dbname = "sentidovidajuan"; // Ejemplo: psicologas_db

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>