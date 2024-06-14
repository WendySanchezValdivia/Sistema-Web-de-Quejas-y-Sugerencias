<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: RevisarSolicitudes.php");
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
$sql = "SELECT * FROM solicitudes WHERE estado = 'en espera'";
$result = $conn->query($sql);

$sql_actualizar_no_procesadas = "UPDATE contadores SET valor = (SELECT COUNT(*) FROM solicitudes WHERE estado = 'activa') WHERE nombre = 'no_procesadas'";
$conn->query($sql_actualizar_no_procesadas);

// Actualizar contador de solicitudes en espera
$sql_actualizar_en_espera = "UPDATE contadores SET valor = (SELECT COUNT(*) FROM solicitudes WHERE estado = 'en espera') WHERE nombre = 'en_espera'";
$conn->query($sql_actualizar_en_espera);

// Actualizar contador de solicitudes inactivas
$sql_actualizar_inactivas = "UPDATE contadores SET valor = (SELECT COUNT(*) FROM solicitudes WHERE estado = 'inactiva') WHERE nombre = 'inactivas'";
$conn->query($sql_actualizar_inactivas);

// Recuperar contadores desde la base de datos
$sql_recuperar_contadores = "SELECT * FROM contadores";
$result_contadores = $conn->query($sql_recuperar_contadores);

$contadores = [];
while ($row = $result_contadores->fetch_assoc()) {
    $contadores[$row['nombre']] = $row['valor'];}

$conn->close();

header("Location: RevisarSolicitudes.php");
exit();
?>
