<?php


$solicitud_id = $_GET['id'];
$estado = 'en espera';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "UPDATE solicitudes SET estado='$estado' WHERE id=$solicitud_id";

if ($conn->query($sql) === TRUE) {
    echo "Solicitud puesta en espera con éxito";
    header("Location: indexAdmin.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
