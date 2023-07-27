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
      
      Prestamos Activos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Prestamos Activos</li>
    
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

         <button type="button" class="btn btn-default pull-right" id="prestamos-activos-btn">
           
            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas tablaPrestamos" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Código</th>
           <th>Nombre cliente</th>
           <th>Nombre Codeudor</th>
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

            if($value["estado_prestamo"] ==1){

           
           echo '<tr>

                  <td>'.($key+1).'</td>
                  <td>'.$value["codigo_prestamo"].'</td>
                  <td>'.$value["nombre_cliente"].'</td>
                  <td>'.$value["nombre_codeudor"].'</td>

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

                    <div class="btn-group">';

                    if($value["estado_prestamo"]){
                      echo '
                      <button class="btn btn-primary btnCancelarPrestamo" id="btnCancelarPrestamo" data-toggle="modal" data-target="#CancelarPrestamo"codigoPrestamo="'.$value["id_prestamo"].'">

                      <i class="fa fa-money"></i>

                      </button>';

                    }
                        echo '
                      <button class="btn btn-info btnImprimirFactura" codigoPrestamo="'.$value["id_prestamo"].'">

                        <i class="fa fa-print"></i>

                      </button>';

                      if($_SESSION["perfil"] == "Administrador" && $value["estado_prestamo"]){

                      echo '<button class="btn btn-warning btnEditarPrestamo" idPrestamo="'.$value["id_prestamo"].'"><i class="fa fa-pencil"></i></button>

                      <button class="btn btn-danger btnEliminarPrestamo" idPrestamo="'.$value["id_prestamo"].'"><i class="fa fa-times"></i></button>';

                    }

                    echo '</div>  

                  </td>

                </tr>';
                  }
            }

        ?>
               
        </tbody>

       </table>

       
       

      </div>

    </div>

    <!--=====================================
    MODAL RECOGER PRESTAMO
    ======================================-->

    <div id="CancelarPrestamo" class="modal fade" role="dialog">
      
      <div class="modal-dialog">

        <div class="modal-content">

          <form role="form" method="post">

            <!--=====================================
            CABEZA DEL MODAL
            ======================================-->

            <div class="modal-header" style="background:#3c8dbc; color:white">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title text-center">Recoger Prestamo</h4>

            </div>

            <!--=====================================
            CUERPO DEL MODAL
            ======================================-->

            <div class="modal-body">

              <div class="box-body">

                <!-- ENTRADA PARA EL NOMBRE -->
                
                <div class="form-group col-lg-6">
                  <label for="">Nombre Cliente:</label>
                  <div class="input-group">
                    
                  
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control input-xs" id="nombreCliente" name="nombreCliente" value="" readonly  required>

                  </div>

                </div>

                <!-- ENTRADA PARA NUMERO DE CUOTA -->
                
                <div class="form-group col-lg-6">
                <!-- <label for="">Número Cuota:</label> -->
                  
                  <div class="input-group">
                  
                    <!-- <span class="input-group-addon"><i class="fa fa-key"></i></span>  -->

                    <!-- <input type="number" class="form-control input-xs" id="numCouta" name="numCouta" readonly required> -->
                    <input type="hidden" class="form-control input-xs" id="idPrestamo" name="idPrestamo" value="" readonly required>
                    <input type="hidden" class="form-control input-xs" id="cantidad_prestamo" name="cantidad_prestamo"value="" readonly required>
                    

                  </div>

                </div>

                <!-- ENTRADA PARA Cantidad a pagar -->
                <?php
                
                ?>
                <div class="form-group col-lg-6">
                <label for="">Cantidad a Pagar:</label>
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                    <input type="number"  class="form-control input-xs" id="cantidad" name="cantidad"value="" readonly required>

                  </div>

                </div>

                <!-- ENTRADA PARA EL INTERÉS A PAGAR -->
                
                <div class="form-group col-lg-6">
                <label for="">Interés a Pagar:</label>
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                    <input type="number" class="form-control input-xs" id="interesPagar" name="interesPagar"value=""  readonly required>

                  </div>

                </div>

                <!-- ENTRADA PARA EL CAPITAL A PAGAR -->
                
                <div class="form-group col-lg-6">
                <label for="">Capital a Pagar:</label>
                  
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                    <input type="number" class="form-control input-xs" id="capitalPagar" name="capitalPagar" value="" readonly required>

                  </div>

                </div>

      
              </div>

            </div>

            <!--=====================================
            PIE DEL MODAL
            ======================================-->

            <div class="modal-footer">

              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>

              <button type="submit" class="btn btn-primary">Pagar</button>

            </div>

          </form>

          <?php

            $crearPago = new ControladorPrestamos();
            $crearPago -> ctrRecogerPrestamo();

           

            $eliminarPrestamo = new ControladorPrestamos();
            $eliminarPrestamo -> ctrEliminarPrestamo();

    

          ?>

        </div>

      </div>

    </div>
  </section>

</div>




