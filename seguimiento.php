<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION['usuario'];
$usuario_id = $usuario['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quejasySugerencias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM solicitudes WHERE usuario_id = $usuario_id";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Seguimiento</title>
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
</head>
<body>

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3;width:300px;font-weight:bold;" id="mySidebar"><br>
  <a href="javascript:void(0)" onClick="w3_close()" class="w3-button w3-hide-large w3-display-topleft" style="width:100%;font-size:22px">Close Menu</a>
  <div class="w3-container">
    <h3 class="w3-padding-64"><b>Facultad de ciencias de la computación</b></h3>
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
  <span>Facultad de ciencias de la computación</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onClick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">
  <!-- Modal for full size images on click-->
  <div id="modal01" class="w3-modal w3-black" style="padding-top:0" onClick="this.style.display='none'">
    <span class="w3-button w3-black w3-xxlarge w3-display-topright">�</span>
    <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
      <img id="img01" class="w3-image">
      <p id="caption"></p>
    </div>
  </div>

  <h1>Seguimiento de Solicitudes</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Fecha de Registro</th>
            <th>Estado</th>
            <th>Detalles</th>
            <th>Eliminar</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['fecha_registro']; ?></td>
            <td><?php echo $row['estado']; ?></td>
            <td><a href="detalles.php?id=<?php echo $row['id']; ?>">Ver Detalles</a></td>
            <td><a href="eliminarQueja.php?id=<?php echo $row['id']; ?>" onclick="return confirm('¿Está seguro de que desea eliminar esta queja?');">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="indexUsuario.php">Volver</a>
<!-- End page content -->
</div>

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
</script>

</body>
</html>
