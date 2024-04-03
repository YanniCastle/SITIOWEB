<?php
$host = "localhost";
$bd = "sitio";
$usuario = "root";
$contrasenia = "";
//01:35:12   //01:43:32
try { //PDO me comunica directamente con BD  01:36:10
  $conexion = new PDO("mysql:host=$host; dbname=$bd", $usuario, $contrasenia);
  if ($conexion) ;//echo "Conectado... a sitio<br/>";
} catch (Exception $ex) {
  echo $ex->getMessage(); //Muestrame el error de conexion
}
?>