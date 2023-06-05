<?php


  $fechaInicial = date('Y-m');

  $ganancia = ControladorGanancias::ctrSumaGananciaFecha($fechaInicial, $fechaInicial);
  // var_dump($ganancia);

  // $colores = array("red","green","yellow","aqua","purple","blue","cyan","magenta","orange","gold");
  $item = null;
  $valor=null;

  $totaldineroPrestado = ControladorPrestamos::ctrMostrarPrestamos($item,$valor);
  $dinero = 0;
  // var_dump($totaldineroPrestado);

  foreach ($totaldineroPrestado as $key => $value) {
    
    $dinero += $value["saldo_pendiente"];

  }

  setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer configuración regional en español
  $nombreMes = date('F');
?>

<!--=====================================
GANANCIAS DEL MES
======================================-->

<div class="col-lg-6 col-xs-12">

  <div class="small-box bg-aqua">
    
    <div class="inner">
      
      <h3>$<?php echo number_format($ganancia["ganancias"]) ; ?></h3>

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

<!--=====================================
DINERO EN PRESTAMO
======================================-->
<div class="col-lg-6 col-xs-12">

  <div class="small-box bg-green">
    
    <div class="inner">
      
      <h3>$<?php echo number_format($dinero) ; ?></h3>

      <p>Dinero en prestamo </p>
    
    </div>
    
    <div class="icon">
      
      <i class="ion ion-social-usd"></i>
    
    </div>
    
    <a href="prestamos" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>