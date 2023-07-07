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
      
      Administrar Cuotas
    
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

        <!-- <div class="row">

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

        </div> -->

      </div>

      <!--=====================================
        TABLA
      ======================================-->
        
       <table class="table table-bordered table-striped dt-responsive tablas tablaCobros" width="100%">
         
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
                  <td>$ '.$value["capital_a_pagar"].'</td>
                  <td>$ '.number_format($value["cantidad_pendiente"],0).'</td>
                  <td>'.$activo.'</td>';
                  
                  echo '

                  <td>'.$value["fecha_vencimiento"].'</td>

                  <td>

                    <div class="btn-group">';

                    if($value["estado"]){
                      echo '
                      <button  class="btn btn-success btnPagarCuota" data-toggle="modal" data-target="#modalPagarCuota" data-id_cuota="'.$value["id_cuota"].'">

                      <i class="fa fa-tasks"></i>

                      </button>

                      <button class="btn btn-primary btnAbonoPrestamo" data-toggle="modal" data-target="#modalAbono"  data-id_cuota="'.$value["id_cuota"].'">

                      <i class="fa fa-money"></i>

                      </button>';
                    }

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

    <!--=====================================
    MODAL PAGAR CUOTA
    ======================================-->

<div id="modalPagarCuota" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title text-center">Pagar Cuota</h4>

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

                <input type="text" class="form-control input-xs" id="nombreCliente" name="nombreCliente" readonly  required>

              </div>

            </div>

            <!-- ENTRADA PARA NUMERO DE CUOTA -->
            
            <div class="form-group col-lg-6">
            <label for="">Número Cuota:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" class="form-control input-xs" id="numCouta" name="numCouta" readonly required>
                <input type="hidden" class="form-control input-xs" id="idPrestamo" name="idPrestamo" readonly required>
                <input type="hidden" class="form-control input-xs" id="id_cuota" name="id_cuota" readonly required>
                

              </div>

            </div>

            <!-- ENTRADA PARA Cantidad a pagar -->
            
            <div class="form-group col-lg-6">
            <label for="">Cantidad a Pagar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number"  class="form-control input-xs" id="cantidad" name="cantidad" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA EL INTERÉS A PAGAR -->
            
            <div class="form-group col-lg-6">
            <label for="">Interés a Pagar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="interesPagar" name="interesPagar"  readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CAPITAL A PAGAR -->
            
            <div class="form-group col-lg-6">
            <label for="">Capital a Pagar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="capitalPagar" name="capitalPagar" readonly required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA y cantidad pendiente -->
            
            <div class="form-group col-lg-6">
            <label for="">Capital Pendiente:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="capitalPendiente" name="capitalPendiente" readonly  required>

                <input type="hidden" class="form-control input-xs" id="fechaCobro" name="fechaCobro">

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

        $crearPago = new ControladorCobros();
        $crearPago -> ctrCrearCobro();

      ?>

    </div>

  </div>

</div>
    <!--=====================================
    MODAL ABONO A CUOTA
    ======================================-->

<div id="modalAbono" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title text-center">Abono a cuota</h4>

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

                <input type="text" class="form-control input-xs" id="nombreCliente2" name="nombreCliente2" readonly  required>

              </div>

            </div>

            <!-- ENTRADA PARA NUMERO DE CUOTA -->
            
            <div class="form-group col-lg-6">
            <label for="">Número Cuota:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" class="form-control input-xs" id="numCouta2" name="numCouta2" readonly required>
                <input type="hidden" class="form-control input-xs" id="idPrestamo2" name="idPrestamo2" readonly required>
                <input type="hidden" class="form-control input-xs" id="id_cuota2" name="id_cuota2" readonly required>
                <input type="hidden" class="form-control input-xs" id="interesPrincipal" name="interesPrincipal" readonly required>
                

              </div>

            </div>

            <!-- ENTRADA PARA Cantidad a pagar -->
            
            <div class="form-group col-lg-6">
            <label for="">Cantidad a Abonar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" min="0"  class="form-control input-xs" id="cantidadAbono" name="cantidadAbono" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL INTERÉS A PAGAR -->
            
            <div class="form-group col-lg-6">
            <label for="">Interés a Pagar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="interesPagar2" name="interesPagar2"  readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA EL CAPITAL A PAGAR -->
            
            <div class="form-group col-lg-6">
            <label for="">Capital a Pagar:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="capitalPagar2" name="capitalPagar2" readonly required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA y cantidad pendiente -->
            
            <div class="form-group col-lg-6">
            <label for="">Capital Pendiente:</label>
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span> 

                <input type="number" class="form-control input-xs" id="capitalPendiente2" name="capitalPendiente2" readonly  required>

                <input type="hidden" class="form-control input-xs" id="fechaCobro2" name="fechaCobro2">
                <input type="hidden" class="form-control input-xs" id="formaPago" name="formaPago">
                <input type="hidden" class="form-control input-xs" id="tiempo" name="tiempo">

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>

          <button type="submit" class="btn btn-primary">Abonar</button>

        </div>

      </form>

      <?php

        $crearAbono = new ControladorAbonos();
        $crearAbono -> ctrCrearAbonos();

      ?>

    </div>

  </div>

</div>

  </section>

</div>




