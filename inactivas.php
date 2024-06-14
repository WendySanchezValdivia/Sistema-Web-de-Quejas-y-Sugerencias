<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener solicitudes inactivas
$sql_inactivas = "SELECT * FROM solicitudes WHERE estado = 'inactiva'";
$result_inactivas = $conn->query($sql_inactivas);

// Obtener solicitudes en espera (si es necesario para otros propósitos)
$sql_en_espera = "SELECT * FROM solicitudes WHERE estado = 'en espera'";
$result_en_espera = $conn->query($sql_en_espera);

// Actualizar contador de solicitudes no procesadas
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
    $contadores[$row['nombre']] = $row['valor'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
        body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
        body {font-size:16px;}
        .w3-half img{margin-bottom:-6px;margin-top:16px;opacity:0.8;cursor:pointer}
        .w3-half img:hover{opacity:1}
    </style>
</head>
<body>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3;width:300px;font-weight:bold;" id="mySidebar"><br>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width:100%;font-size:22px">Close Menu</a>
    <div class="w3-container">
        <h3 class="w3-padding-64"><b>Facultad de ciencias de la computación</b></h3>
    </div>
    <div class="w3-bar-block">
  <li><a href="IndexAdmin.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Inicio</a></li>
    <li><a href="noprocesada.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Aun no se ha procesado la queja [<?php echo $contadores['no_procesadas']; ?>]</a></li>
    <li><a href="enespera.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Queja EN ESPERA [<?php echo $contadores['en_espera']; ?>]</a></li>
    <li><a href="inactivas.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Quejas inactivas [<?php echo $contadores['inactivas']; ?>]</a></li>
    <li><a href="RevisarSolicitudes.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Revisar Solicitudes</a></li>
    <li><a href="usuarios.php"onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Usuarios</a></li>
    <li><a href="notificaciones.php" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Notificaciones</a></li>
    <a href="cerrar_sesion.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Cerrar Sesión</a> 
 
</div>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()"></a>
    <span>Facultad de ciencias de la computación</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">
    <h1>Quejas Inactivas</h1>
    <table>
        <tr>
            <th>Tipo de Queja</th>
            <th>Nombre del Usuario</th>
            <th>Matrícula</th>
            <th>Fecha de Registro</th>
            <th>Estado</th>
            <th>Respuesta</th>
        </tr>
        <?php while($row = $result_inactivas->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['tipo_solicitud']; ?></td>
            <td><?php echo $row['nombre_usuario']; ?></td>
            <td><?php echo $row['matricula']; ?></td>
            <td><?php echo $row['fecha_registro']; ?></td>
            <td><?php echo $row['estado']; ?></td>
            <td><?php echo $row['respuesta']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="indexAdmin.php">Volver</a>

<!-- W3.CSS Container -->
<div class="w3-light-grey w3-container w3-padding-32" style="margin-top:75px;padding-right:58px"><p class="w3-right">Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-opacity">w3.css</a></p></div>

<script>
// Script to open and close sidebar
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}
 
function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

// Modal Image Gallery
function onClick(element) {
  document.getElementById("img01").src = element.src;
  document.getElementById("modal01").style.display = "block";
  var captionText = document.getElementById("caption");
  captionText.innerHTML = element.alt;
}
</script>

</body>
</html>
