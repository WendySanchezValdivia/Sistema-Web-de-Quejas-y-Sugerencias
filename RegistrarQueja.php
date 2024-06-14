<!DOCTYPE html>
<html lang="en">
<head>
<title>Registrar Queja</title>
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
<script>
        function loadFile(event) {
            var output = document.getElementById('output');
            var files = event.target.files;
            output.innerHTML = "";
            for (var i = 0; i < files.length; i++) {
                var img = document.createElement("img");
                img.src = URL.createObjectURL(files[i]);
                img.width = 100;
                img.height = 100;
                output.appendChild(img);
            }
        }
    </script>

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

<body>
<h1>Registrar Queja o Sugerencia</h1>
<form id="uploadForm" action="upload_temp.php" method="POST" enctype="multipart/form-data" target="uploadFrame">
    <label for="tipo_solicitud">Tipo de Solicitud:</label>
    <select name="tipo_solicitud" id="tipo_solicitud">
        <option value="queja">Queja</option>
        <option value="sugerencia">Sugerencia</option>
    </select>
    <br><br>
    <label for="motivo">Motivo:</label>
    <textarea name="motivo" id="motivo" rows="4" cols="50"></textarea>
    <br><br>
    <label for="archivos">Cargar Archivos:</label>
    <input type="file" name="archivos[]" id="archivos" multiple onchange="loadFile(event)">
    <input type="submit" value="Cargar">
</form>
<iframe name="uploadFrame" style="display:none;"></iframe>
<br><br>
<div id="output"></div>
<br><br>
<form action="procesar_queja.php" method="POST">
    <input type="hidden" name="tipo_solicitud" id="tipo_solicitud_hidden">
    <input type="hidden" name="motivo" id="motivo_hidden">
    <input type="hidden" name="uploaded_files" id="uploaded_files">
    <input type="submit" value="Enviar">
</form>

<script>
    document.getElementById('uploadForm').onsubmit = function() {
        document.getElementById('tipo_solicitud_hidden').value = document.getElementById('tipo_solicitud').value;
        document.getElementById('motivo_hidden').value = document.getElementById('motivo').value;
    }
</script>

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
