<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION['usuario'];
$usuario_id = $usuario['id'];
$solicitud_id = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM solicitudes WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $solicitud_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $solicitud = $result->fetch_assoc();
} else {
    echo "No se encontró la solicitud.";
    exit();
}

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
$stmt_files->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Detalles de Solicitud</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
    <style>
        body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
        body {font-size:16px;}
        .w3-half img{margin-bottom:-6px;margin-top:16px;opacity:0.8;cursor:pointer}
        .w3-half img:hover{opacity:1}
    </style>
    <link rel="stylesheet" href="styles.css">

    <title>Upload Multiple Images</title>
</head>
<body>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3;width:300px;font-weight:bold;" id="mySidebar"><br>
  <a href="javascript:void(0)" onClick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width:100%;font-size:22px">Close Menu</a>
  <div class="w3-container">
    <h3 class="w3-padding-64"><b>Facultad de Ciencias de la Computación</b></h3>
  </div>
  <div class="w3-bar-block">
    <a href="indexUsuario.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Inicio</a> 
    <a href="panel.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Panel</a> 
    <a href="RegistrarQueja.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Realizar Solicitud</a> 
    <a href="seguimiento.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Seguimiento de Q/S</a> 
    <a href="cerrar_sesion.php" onClick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Cerrar Sesión</a> 
  </div>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-top w3-hide-large w3-red w3-xlarge w3-padding">
  <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onClick="w3_open()"></a>
  <span>Facultad de Ciencias de la Computación</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onClick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

  <h1>Detalles de la Solicitud</h1>
  <p><strong>Tipo de Solicitud:</strong> <?php echo htmlspecialchars($solicitud['tipo_solicitud']); ?></p>
  <p><strong>Motivo:</strong> <?php echo htmlspecialchars($solicitud['motivo']); ?></p>
  <p><strong>Estado:</strong> <?php echo htmlspecialchars($solicitud['estado']); ?></p>
  <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($solicitud['fecha_registro']); ?></p>
  
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
