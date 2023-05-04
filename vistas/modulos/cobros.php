<?php

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar Cobros
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Cobros</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          
          Agregar abono

        </button> -->

        <button type="button" class="btn btn-success pull-right btnCobros" id="daterange-btnCobros">
           
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>
          
      </div>
      
      <!--=====================================
      LLEYENDA TABLA
      ======================================-->
      <div class="box-body">

      <div class="container">

        <div class="row">

         <div class="col-lg-4">
                <div class="col-lg-4" style="height: 30px; width:30px;border-radius:50%;background-color: green;">
                </div>

                <div class="col-lg-8">
                  <label for="">Pagadas</label>
                </div>

              

          </div>

          <div class="col-lg-4">

                <div class="col-lg-4" style="height: 30px; width:30px;border-radius:50%; background-color:darkgrey; " >
                </div>

                <div class="col-lg-8">
                  <label for="">pendientes de pago</label>
                </div>

              

          </div>

          <div class="col-lg-4">

                <div class="col-lg-4" style="display:flex;
                justify-content:center; align-items:center; height: 30px; width:30px;border-radius:50%;background-color:lawngreen; text-align:center;color:white;font:bold;" >A</div>

                <div class="col-lg-8">
                  <label for="">Abono</label>
                </div>

              

          </div>

        </div>

      </div>

      <!--=====================================
        TABLA
      ======================================-->
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre</th>
           <th>N° cuota</th>
           <th>Cuota a pagar</th>
           <th>Interés a pagar</th>
           <th>Capital a pagar</th>
           <th>Capital pendiente</th>
           <th>Estado</th>
           <th>Fecha cobro</th>
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

          $respuesta = ControladorCobros::ctrRangoFechasCobros($fechaInicial, $fechaFinal);

          foreach ($respuesta as $key => $value) {

            if($value["estado"]){
              $activo = "<button type='button' class='btn btn-success'>Activo</button>";
            }else{
              $activo = "<button type='button' class='btn btn-warning'>Pagado</button>";
            }
           
           echo '<tr>

                  <td>'.($key+1).'</td>
                  <td>'.$value["nombre_cliente"].'</td>
                  <td>'.$value["num_cuota"].'</td>

                  <td>$ '.number_format($value["monto_cuota"],0).'</td>

                  <td>$ '.number_format($value["interes_a_pagar"],0).'</td>
                  <td>'.$value["capital_a_pagar"].'</td>
                  <td>$ '.number_format($value["cantidad_pendiente"],0).'</td>
                  <td>'.$activo.'</td>';
                  
                  echo '

                  <td>'.$value["fecha_vencimiento"].'</td>

                  <td>

                    <div class="btn-group">

                      <button  class="btn btn-success btnCuotas" codigoPrestamo="'.$value["id_cuota"].'">

                      <i class="fa fa-tasks"></i>

                      </button>

                      <button class="btn btn-primary btnCancelarPrestamo" codigoPrestamo="'.$value["id_cuota"].'">

                      <i class="fa fa-money"></i>

                      </button>';

                    echo '</div>  

                  </td>

                </tr>';
            }

        ?>
               
        </tbody>

       </table>

       <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

      </div>

    </div>

  </section>

</div>




