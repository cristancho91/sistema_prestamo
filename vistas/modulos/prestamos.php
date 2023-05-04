<?php

if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar Prestamos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Prestamos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <a href="crear-prestamo">

          <button class="btn btn-primary">
            
            Agregar Prestamo

          </button>

        </a>

         <button type="button" class="btn btn-default pull-right" id="daterange-btn">
           
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Código</th>
           <th>Nombre cliente</th>
           <!-- <th>Nombre Prestamista</th> -->
           <th>Prestamo</th>
           <th>Interés</th>
           <th>Tiempo (meses)</th>
           <th>Forma de pago</th> 
           <th>Saldo Pendiente</th> 
           <th>Estado Prestamo</th> 
           <th>Fecha</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>

        <?php

          if(isset($_GET["fechaInicial"])){

            $fechaInicial = $_GET["fechaInicial"];
            $fechaFinal = $_GET["fechaFinal"];

          }else{

            $fechaInicial = null;
            $fechaFinal = null;

          }

          $respuesta = ControladorPrestamos::ctrRangoFechasPrestamos($fechaInicial, $fechaFinal);

          foreach ($respuesta as $key => $value) {
           
           echo '<tr>

                  <td>'.($key+1).'</td>
                  <td>'.$value["codigo_prestamo"].'</td>
                  <td>'.$value["nombre_cliente"].'</td>

                  <td>$ '.number_format($value["monto"],0).'</td>

                  <td>'.number_format($value["tasa_interes"],0).'%</td>
                  <td>'.$value["tiempo_en_meses"].'</td>
                  <td>'.$value["forma_pago"].'</td>
                  <td>$ '.number_format($value["saldo_pendiente"],0).'</td>';

                  if($value["estado_prestamo"]){
                    echo '<td ><button type="button" class="btn btn-success">Activo</button></td>';
                  }else{
                    echo '<td ><button type="button" class="btn btn-danger">Pagado</button></td>';
                  }

                  echo '

                  <td>'.$value["fecha_prestamo"].'</td>

                  <td>

                    <div class="btn-group">

                      <button class="btn btn-success btnCuotas" codigoPrestamo="'.$value["id_prestamo"].'">

                      <i class="fa fa-tasks"></i>

                      </button>

                      <button class="btn btn-primary btnCancelarPrestamo" codigoPrestamo="'.$value["id_prestamo"].'">

                      <i class="fa fa-money"></i>

                      </button>
                        
                      <button class="btn btn-info btnImprimirFactura" codigoPrestamo="'.$value["codigo_prestamo"].'">

                        <i class="fa fa-print"></i>

                      </button>';

                      if($_SESSION["perfil"] == "Administrador"){

                      echo '<button class="btn btn-warning btnEditarPrestamo" idPrestamo="'.$value["id_prestamo"].'"><i class="fa fa-pencil"></i></button>

                      <button class="btn btn-danger btnEliminarPrestamo" idPrestamo="'.$value["id_prestamo"].'"><i class="fa fa-times"></i></button>';

                    }

                    echo '</div>  

                  </td>

                </tr>';
            }

        ?>
               
        </tbody>

       </table>

       <?php

      $eliminarPrestamo = new ControladorPrestamos();
      $eliminarPrestamo -> ctrEliminarPrestamo();

      ?>
       

      </div>

    </div>

  </section>

</div>




