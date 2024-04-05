<?php
if (isset($_POST["submit"])) {
  $status = 'error';
  if (!empty($_FILES["image"]["name"])) {
    // File info 
    $fileName = basename($_FILES["image"]["name"]);
    $imageUploadPath = $uploadPath . $fileName;
    $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);

    // Permitimos solo unas extensiones
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
      // Image temp source 
      $imageTemp = $_FILES["image"]["tmp_name"];

      // Comprimos el fichero
      $compressedImage = compressImage($imageTemp, $imageUploadPath, 12);

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
} 
?>