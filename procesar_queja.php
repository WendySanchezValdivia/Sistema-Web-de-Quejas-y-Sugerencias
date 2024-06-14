<?php
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo_solicitud = $_POST['tipo_solicitud'];
    $motivo = $_POST['motivo'];
    $uploaded_files = isset($_SESSION['uploaded_files']) ? $_SESSION['uploaded_files'] : [];

    if (isset($_SESSION['usuario']['id'])) {
        $usuario_id = $_SESSION['usuario']['id'];
        $nombre_usuario = $_SESSION['usuario']['nombre_completo'];
        $matricula = $_SESSION['usuario']['matricula'];

        $stmt = $conn->prepare("INSERT INTO solicitudes (usuario_id, tipo_solicitud, motivo, estado, fecha_registro, nombre_usuario, matricula) VALUES (?, ?, ?, 'activa', NOW(), ?, ?)");
        $stmt->bind_param("issss", $usuario_id, $tipo_solicitud, $motivo, $nombre_usuario, $matricula);

        if ($stmt->execute()) {
            $solicitud_id = $stmt->insert_id;

            foreach ($uploaded_files as $file_path) {
                $final_path = 'uploads/' . basename($file_path);
                rename($file_path, $final_path);

                $stmt_img = $conn->prepare("INSERT INTO solicitud_imagenes (solicitud_id, ruta_imagen) VALUES (?, ?)");
                $stmt_img->bind_param("is", $solicitud_id, $final_path);
                $stmt_img->execute();
                $stmt_img->close();
            }

            // Limpiar archivos temporales de la sesión
            $_SESSION['uploaded_files'] = [];
            echo "Solicitud registrada con éxito.";
        } else {
            echo "Error al registrar la solicitud: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: No se pudo obtener la información del usuario.";
    }
} else {
    header("Location: indexUsuario.php");
    exit();
}
?>
