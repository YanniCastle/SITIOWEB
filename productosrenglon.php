<?php include("template/cabecera.php"); ?>

<?php include("administrador/config/bd.php");
$sentenciaSQL = $conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach ($listaLibros as $libro) {  ?>
<!--class="col-md-3"-->
<div class="col-md-3">

  <div class="card">

    <img class="card-img-top" src="./img/<?php echo $libro['imagen']; ?>" alt="<?php echo $libro['nombre']; ?>">

  </div>

</div>
<?php } ?>



//PENDIENTE SINTAXIS DE BOOTSTRAP
<div class="row">
  <div class="col-sm"><img class="w-100" src="img/1710291075_antitranspirante_c.jpg" alt="First slide"></div>
  <div class="col-sm"><img class="w-100" src="img/1710291330_cargador_sony_c.jpg" alt="Second slide"></div>
  <div class="col-sm"><img class="w-100" src="img/1710292202_antena_c.jpg" alt="Third slide"></div>
</div>




<script src="./js/bootstrap.bundle.min.js" integrity="slide"></script>