<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $matricula = $_POST['matricula'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena']; // Asegúrate de almacenar la contraseña encriptada en un entorno de producción
    $tipo = 'solicitante'; // Puedes cambiar esto según sea necesario

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "quejasySugerencias";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    try {
        $stmt = $conn->prepare("CALL insertar_usuario(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre_completo, $matricula, $usuario, $contrasena, $tipo);

        if ($stmt->execute()) {
            echo "Registro exitoso";
            header("Location: login.php");
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        echo "Error al registrar el usuario: " . $e->getMessage();
    }

    $conn->close();
}
?>
