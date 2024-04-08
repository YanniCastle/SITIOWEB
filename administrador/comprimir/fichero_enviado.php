<?php
if (isset($_POST["submit"])) {
  if ($_FILES["txtImagen"]["size"] <= 5120000) {
  $status = 'error';
  if (!empty($_FILES["txtImagen"]["name"])) {

    $fecha = new DateTime();
    $nombreArchivo = ($_FILES["txtImagen"]["name"] != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "sin_imagen.jpg";

    $fileName = basename($_FILES["txtImagen"]["name"]);
    $imageUploadPath = $uploadPath . $nombreArchivo;
    $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);

    // Permitimos solo unas extensiones
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        // Image temp source 
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

      // Comprimos el fichero
      $compressedImage = compressImage($tmpImagen, $imageUploadPath, 12);

      if ($compressedImage) {
        $status = 'success';
        $statusMsg = "La imagen se ha subido satisfactoriamente.";
      } else {
        $statusMsg = "La compresion de la imagen ha fallado";
      }
    } else {
      $statusMsg = 'Lo sentimos, solo se permiten imágenes con estas extensiones: JPG, JPEG, PNG, & GIF.';
    }
  } else {
    $statusMsg = 'Por favor, selecciona una imagen.';
  }
  }/*fin de size */ else {
    echo "Demasiado grande la imagen";
  }
}//fin del post
?>