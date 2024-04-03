<?php include("template/cabecera.php"); ?>

<?php include("administrador/config/bd.php");
//Mostrar todos los registros en tabla
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach ($listaLibros as $libro) {  ?>
<div class="col-md-3">
  <div class="card">
    <img class="card-img-top" src="./img/<?php echo $libro['imagen']; ?>" alt="<?php echo $libro['nombre']; ?>">
    <div class="card-body">
      <h4 class="card-title"><?php echo $libro['nombre']; ?></h4>
      <!--36:22 video: Sitio Web-->
      <a name="" id="" class="btn btn-primary" href="carrusel2.html" role="button">Ver más</a>
    </div>
  </div>
</div>
<?php }
//perfil como:Web Developer->Desarrollador web 31/03/24
?>

<br /><br /><br /><br /><br /><br /><br /><br /><br />

<h2>Carrusel Sencillo</h2>
<!--Aqui agregando el carrusel sencillo-->
<div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="img/1710291075_antitranspirante_c.jpg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/1710291330_cargador_sony_c.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="img/1710292202_antena_c.jpg" alt="Third slide">
    </div>
  </div>
</div>
<!--Agrege el codigo en local-->
<script src="./js/bootstrap.bundle.min.js" integrity="slide"></script>