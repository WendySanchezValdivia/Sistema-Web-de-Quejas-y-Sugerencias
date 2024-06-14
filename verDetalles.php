<?php

$solicitud_id = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT id, usuario_id FROM solicitudes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $solicitud_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuario_id = $row['usuario_id']; // Obtenemos el usuario_id de la fila
} else {
    echo "No se encontró la solicitud.";
    exit();
}

// Ahora que tenemos el usuario_id, podemos obtener los detalles de la solicitud
$sql_solicitud = "SELECT * FROM solicitudes WHERE id = ? AND usuario_id = ?";
$stmt_solicitud = $conn->prepare($sql_solicitud);
$stmt_solicitud->bind_param("ii", $solicitud_id, $usuario_id);
$stmt_solicitud->execute();
$result_solicitud = $stmt_solicitud->get_result();

if ($result_solicitud->num_rows > 0) {
    $solicitud = $result_solicitud->fetch_assoc();
} else {
    echo "No se encontró la solicitud para el usuario.";
    exit();
}

// Obtener imágenes/videos asociadas a la solicitud
$sql_files = "SELECT ruta_imagen FROM solicitud_imagenes WHERE solicitud_id = ?";
$stmt_files = $conn->prepare($sql_files);
$stmt_files->bind_param("i", $solicitud_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
$archivos = [];
while ($row = $result_files->fetch_assoc()) {
    $archivos[] = $row['ruta_imagen'];
}

$stmt->close();
$stmt_solicitud->close();
$stmt_files->close();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
        body, h1, h2, h3, h4, h5 {font-family: "Poppins", sans-serif}
        body {font-size: 16px;}
        .w3-half img {margin-bottom: -6px; margin-top: 16px; opacity: 0.8; cursor: pointer}
        .w3-half img:hover {opacity: 1}
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index: 3; width: 300px; font-weight: bold;" id="mySidebar"><br>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width: 100%; font-size: 22px">Close Menu</a>
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
    <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onClick="w3_open()"></a>
    <span>Facultad de ciencias de la computación</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onClick="w3_close()" style="cursor: pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left: 340px; margin-right: 40px">
    <h1>Detalles de la Queja</h1>
    <table>
        <tr>
            <th>ID de Queja</th>
            <td><?php echo $solicitud['id']; ?></td>
        </tr>
        <tr>
            <th>Tipo de Queja</th>
            <td><?php echo $solicitud['tipo_solicitud']; ?></td>
        </tr>
        <tr>
            <th>Motivo</th>
            <td><?php echo $solicitud['motivo']; ?></td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td><?php echo $solicitud['fecha_registro']; ?></td>
        </tr>
        <tr>
            <th>Estado</th>
            <td><?php echo $solicitud['estado']; ?></td>
        </tr>
        <tr>
            <th>Respuesta</th>
            <td><?php echo $solicitud['respuesta']; ?></td>
        </tr>
    </table>

    <?php if (count($archivos) > 0): ?>
    <div class="w3-content w3-display-container">
        <?php foreach ($archivos as $archivo): ?>
            <?php $ext = pathinfo($archivo, PATHINFO_EXTENSION); ?>
            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img class="mySlides" src="<?php echo htmlspecialchars($archivo); ?>">
            <?php elseif (in_array($ext, ['mp4', 'webm', 'ogg'])): ?>
                <video class="mySlides" controls>
                    <source src="<?php echo htmlspecialchars($archivo); ?>" type="video/<?php echo htmlspecialchars($ext); ?>">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
        <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
    </div>
    
    <script>
          var slideIndex = 1;
          showDivs(slideIndex);

          function plusDivs(n) {
              showDivs(slideIndex += n);
          }

          function showDivs(n) {
              var i;
              var x = document.getElementsByClassName("mySlides");
              if (n > x.length) {slideIndex = 1}
              if (n < 1) {slideIndex = x.length}
              for (i = 0; i < x.length; i++) {
                 x[i].style.display = "none";
              }
              x[slideIndex-1].style.display = "block";
          }
      </script>
  <?php else: ?>
      <p>No hay imágenes asociadas a esta solicitud.</p>
  <?php endif; ?>

<!-- W3.CSS Container -->
<div class="w3-light-grey w3-container w3-padding-32" style="margin-top:75px;padding-right:58px"><p class="w3-right">Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-opacity">w3.css</a></p></div>

<script>

<!-- W3.CSS Container -->
<div class="w3-light-grey w3-container w3-padding-32" style="margin-top: 75px; padding-right: 58px">
    <p class="w3-right">Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank" class="w3-hover-opacity">w3.css</a></p>
</div>

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

// Slideshow
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    if (n > x.length) {slideIndex = 1}
    if (n < 1) {slideIndex = x.length}
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    x[slideIndex-1].style.display = "block";  
}
</script>

</body>
</html>
