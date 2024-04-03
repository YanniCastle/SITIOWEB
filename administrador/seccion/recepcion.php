<?php
if (isset($_POST["accion"])) {
 echo $_POST['txtID'];
 echo $_POST['txtNombre'];
echo $_FILES['txtImagen']['name'];
}
?>