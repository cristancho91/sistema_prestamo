<?php

$item = null;
$valor = null;
// $orden = "id_prestamo";

$prestamos = ControladorPrestamos::ctrMostrarPrestamos($item, $valor);
// print_r($prestamos);

 ?>


<div class="box box-primary">

  <div class="box-header with-border">

    <h3 class="box-title">Prestamos Recientes</h3>

    <div class="box-tools pull-right">

      <button type="button" class="btn btn-box-tool" data-widget="collapse">

        <i class="fa fa-minus"></i>

      </button>

      <button type="button" class="btn btn-box-tool" data-widget="remove">

        <i class="fa fa-times"></i>

      </button>

    </div>

  </div>
  
  <div class="box-body">

    <ul class="products-list product-list-in-box">

    <?php

    if(count($prestamos) <= 10){
      foreach ($prestamos as $prestamo) {
        $cliente = ControladorClientes::ctrMostrarClientes("id", $prestamo["id_cliente"]);
        echo '<li class="item">

        <div class="product-img">

        <a href="" class="product-title">

            '.$cliente["nombre"].'

            <span class="label label-warning pull-right">$'.$prestamo["monto"].'</span>

          </a>

        </div>

        <div class="product-info">

          <a href="" class="product-title">

            '.$prestamo["fecha_prestamo"].'

            <span class="label label-warning pull-right">$'.$prestamo["saldo_pendiente"].'</span>

          </a>
    
       </div>

      </li>';
      }
    }else{

    

      for($i = 0; $i < 10; $i++){
        $cliente = ControladorClientes::ctrMostrarClientes("id", $prestamos[$i]["id_cliente"]);

        echo '<li class="item">

          <div class="product-img">

          <a href="" class="product-title">

              '.$prestamos[$i]["nombre"].'

              <span class="label label-warning pull-right">$'.$prestamos[$i]["monto"].'</span>

            </a>

          </div>

          <div class="product-info">

            <a href="" class="product-title">

              '.$prestamos[$i]["fecha_prestamo"].'

              <span class="label label-warning pull-right">$'.$prestamos[$i]["saldo_pendiente"].'</span>

            </a>
      
        </div>

        </li>';

      }

    }

    ?>

    </ul>

  </div>

  <div class="box-footer text-center">

    <a href="prestamos" class="uppercase">Ver todos los prestamos</a>
  
  </div>

</div>
