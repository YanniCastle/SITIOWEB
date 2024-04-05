<?php
function compressImage($source, $destination, $quality)
{
  // Obtenemos la información de la imagen
  $imgInfo = getimagesize($source);
  $mime = $imgInfo['mime'];

  // Creamos una imagen
  switch ($mime) {
    case 'image/jpeg':
      $image = imagecreatefromjpeg($source);
      break;
    case 'image/png':
      $image = imagecreatefrompng($source);
      break;
    case 'image/gif':
      $image = imagecreatefromgif($source);
      break;
    default:
      $image = imagecreatefromjpeg($source);
  }

  // Guardamos la imagen
  imagejpeg($image, $destination, $quality);

  // Devolvemos la imagen comprimida
  return $destination;
} 
?>