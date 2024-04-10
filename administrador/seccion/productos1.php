<?php include("../template/cabecera.php"); ?>
<!--Primero salir de seccion-->

<?php
/*SI EXISTE UN VALOR en txtID sera=$txtID, SI NO, SERA VACIO*/
$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
//$Type = (isset($_FILES['txtImagen']['type'])) ? $_FILES['txtImagen']['type'] : "";
include("../config/bd.php");

switch ($accion) {

  case "Agregar":
    $Type = $_FILES['txtImagen']['type'];
    if ($_FILES['txtImagen']['size'] <= 5120000) {
      if ($Type = 'jpg' or $Type = 'png' or $Type = 'jpeg' or $Type = 'gif') {

        $sentenciaSQL = $conexion->prepare("INSERT INTO libros (nombre, imagen) VALUES (:nombre, :imagen);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "sin_imagen.jpg";

        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if ($txtImagen != "") {
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
          // Ruta subida
          $uploadPath = "../../img/";

          // Si el fichero se ha enviado
          // $status = $statusMsg = '';

          if (isset($_POST["accion"])) {
            // if ($_FILES["txtImagen"]["size"] <= 5120000) {

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
                  //$status = 'success';
                  $mensaje = "La imagen se ha subido satisfactoriamente.";
                } else {
                  $mensaje = "La compresion de la imagen ha fallado";
                }
              } else {
                $mensaje = 'Lo sentimos, solo se permiten imágenes con estas extensiones: JPG, JPEG, PNG, & GIF.';
              }
            } else {
              $mensaje = 'Por favor, selecciona una imagen.';
            }

            //}/*fin de size */ else {
            //  echo "Demasiado grande la imagen";
            //}
          } //fin del post

          // Mostrar el estado de la imagen 
          // echo $statusMsg; 
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();
        //header("Location:productos1.php"); //al suspender esto, salen $statusMsg
      } //fin de type
      else {
        $mensaje = 'Solo se permiten imágenes con estas extensiones: JPG, JPEG, PNG, & GIF.';
      }
    } //fin de [size] 
    else {
      $mensaje = "El archivo es demasiado grande. El tamaño máximo permitido es de 5 MB.";
    }

    break;

  case "Modificar": //AQUI TAMBIEN AGREGAR TAMANO Y COMPRIMIR IMAGEN
    //Aqui solo modificamos el registro
    $sentenciaSQL = $conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
    $sentenciaSQL->bindParam(':nombre', $txtNombre);
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();

    //Agregaremos la condicion para la imagen---su registro
    if ($txtImagen != "") {
      //Este dato es importante para modificar la imagen
      $fecha = new DateTime();
      $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "sin_imagen.jpg";
      $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

      move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
      //Buscamos la imagen
      $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
      $sentenciaSQL->bindParam(':id', $txtID);
      $sentenciaSQL->execute();
      $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
      //Borramos imagen vieja
      if (isset($libro["imagen"]) && $libro["imagen"] != "sin_imagen.jpg") {
        if (file_exists("../../img/" . $libro["imagen"])) {
          unlink("../../img/" . $libro["imagen"]);
        }
      } //Actualizamos
      $sentenciaSQL = $conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
      $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
      $sentenciaSQL->bindParam(':id', $txtID);
      $sentenciaSQL->execute(); //2:25:20
    }
    header("Location:productos1.php"); //2:39:24 Pruebas de redireccionar
    //echo "Presionado botón: Modificar";
    break;

  case "Cancelar":
    header("Location:productos1.php");
    //echo "Presionado botón: Cancelar";
    break;

  case "Seleccionar":
    $sentenciaSQL = $conexion->prepare("SELECT * FROM libros WHERE id=:id");
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY); //2:01:39

    $txtNombre = $libro['nombre'];
    $txtImagen = $libro['imagen'];
    //echo "Presionado botón: Seleccionar";
    break;

  case "Borrar":
    //ahora agregaremos el borrar archivo
    $sentenciaSQL = $conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

    if (isset($libro["imagen"]) && $libro["imagen"] != "sin_imagen.jpg") {
      if (file_exists("../../img/" . $libro["imagen"])) {
        unlink("../../img/" . $libro["imagen"]);
      }
    }
    //Esto solo borra el registro en BD
    $sentenciaSQL = $conexion->prepare("DELETE FROM libros WHERE id=:id");
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    header("Location:productos1.php");
    //echo "Presionado botón: Borrar";
    break;
}

//Mostrar todos los registros en tabla
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!--Formulario de Captura de libros -->
<div class="col-md-5">

  <div class="card">
    <div class="card-header">
      Datos de libro
    </div>

    <div class="card-body">

      <?php if (isset($mensaje)) { ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $mensaje; ?>
      </div>
      <?php } ?>

      <!--Agregue:action,prueba     action="recepcion.php"-->
      <form action="" method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <label for="txtID">ID:</label>
          <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID"
            id="txtID" placeholder="ID">
        </div>

        <div class="form-group">
          <label for="txtNombre">Nombre:</label>
          <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre"
            id="txtNombre" placeholder="Nombre de libro">
        </div>

        <div class="form-group">
          <label for="txtImagen">Imagen:</label>
          <br>
          <!--<?php echo $txtImagen; // 2:00:39 //Ahora mostrar imagen en form 2:28:39
              ?>-->

          <?php //Ahora preguntamos
          if ($txtImagen != "") { ?>
          <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; //copiado, pegado y modificado 2:29:43  
                                                              ?>" width="50px" alt="" srcset="">
          <?php } ?>

          <input type="hidden" name="getimagesize" value="5120000">
          <input type="file" required class="form-control" name="txtImagen" id="txtImagen" accept="image/*">
        </div>
        <!--01:21:20-->
        <div class="btn-group" role="group" aria-label="">
          <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?>
            value="Agregar" class="btn btn-success">Agregar</button>
          <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?>
            value="Modificar" class="btn btn-warning">Modificar</button>
          <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?>
            value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>

      </form>

    </div>
  </div>
</div>


<!--tABLA DONDE SE MUESTRAN LOS LIBROS: Lista-->
<div class="col-md-7">

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Imagen</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>

      <?php foreach ($listaLibros as $libro) { ?>
      <tr>
        <td><?php echo $libro['id']; ?></td>
        <td><?php echo $libro['nombre']; ?></td>
        <td>
          <img class="img-thumbnail rounded" src="../../img/<?php echo $libro['imagen']; //2:28:03 
                                                              ?>" width="50px" alt="" srcset="">
        </td>

        <td>
          <!--1:52:25  action="" puede funcionar si este, y CUIDADO "post" sin espacionHEEE-->
          <form method="post">
            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>" />

            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />
            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />
          </form>
        </td>

      </tr>
      <?php } ?>

    </tbody>
  </table>

</div>


<?php include("../template/pie.php"); ?>