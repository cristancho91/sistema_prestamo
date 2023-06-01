<?php


// $fechaInicial = date('Y-m');
$fechaInicial = "2023-05";
// var_dump($fecha);


$ganancia = ControladorGanancias::ctrSumaGananciaFecha($fechaInicial, $fechaInicial);
// var_dump($ganancia);

// $colores = array("red","green","yellow","aqua","purple","blue","cyan","magenta","orange","gold");

// $totalVentas = ControladorProductos::ctrMostrarSumaVentas();

  setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer configuración regional en español
  $nombreMes = date('F');
?>

<!--=====================================
GANANCIAS DEL MES
======================================-->

<div class="col-lg-12 col-xs-12">

  <div class="small-box bg-aqua">
    
    <div class="inner">
      
      <h3><?php echo $ganancia["ganancias"]; ?></h3>

      <p>Ganancia de <?php echo $nombreMes; ?> Del <?php echo date('Y'); ?></p>
    
    </div>
    
    <div class="icon">
      
      <i class="ion ion-social-usd"></i>
    
    </div>
    
    <a href="ganancias" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>