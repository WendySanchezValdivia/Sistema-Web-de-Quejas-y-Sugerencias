<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: seguimiento.php");
    exit();
}

$queja_id = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Eliminar imágenes asociadas
$sql_delete_images = "DELETE FROM solicitud_imagenes WHERE solicitud_id = ?";
$stmt_delete_images = $conn->prepare($sql_delete_images);
$stmt_delete_images->bind_param("i", $queja_id);
$stmt_delete_images->execute();
$stmt_delete_images->close();

// Eliminar la queja
$sql_delete_queja = "DELETE FROM solicitudes WHERE id = ?";
$stmt_delete_queja = $conn->prepare($sql_delete_queja);
$stmt_delete_queja->bind_param("i", $queja_id);
$stmt_delete_queja->execute();
$stmt_delete_queja->close();

$conn->close();

header("Location: seguimiento.php");
exit();
?>
