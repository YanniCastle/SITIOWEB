<?php 
 include("compressImage.php");
 
// Ruta subida
$uploadPath = "../../img/"; 
 
// Si el fichero se ha enviado
$status = $statusMsg = ''; 
include("fichero_enviado.php");
 
// Mostrar el estado de la imagen 
echo $statusMsg; 
 
?>