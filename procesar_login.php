<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matricula = $_POST['matricula'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE matricula = ? AND contrasena = ?");
    $stmt->bind_param("ss", $matricula, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $_SESSION['usuario'] = $usuario;

        if ($usuario['tipo'] == 'admin') {
            header("Location: indexAdmin.php");
        } else {
            header("Location: indexUsuario.php");
        }
    } else {
        echo "Matricula o contraseña incorrecta.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
