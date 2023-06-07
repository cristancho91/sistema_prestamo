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
      
      Administrar Ganancias
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Ganancias</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          
          Agregar abono

        </button> -->

        <button type="button" class="btn btn-success pull-right btnCobros" id="daterange-btnGanancias">
           
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

      </div>

      <!--=====================================
        TABLA
      ======================================-->
        
       <table class="table table-bordered table-striped dt-responsive tablas tablaGanancias" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Fecha</th>
           <th>Nombre</th>
           <th>Codigo Prestamo</th>
           <th>Concepto Ganancia</th>
           <th>Monto Ganancia</th>
           <!-- <th>Acciones</th> -->
           
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

          $respuesta = ControladorGanancias::ctrRangoFechasganancia($fechaInicial, $fechaFinal);

          
          

          foreach ($respuesta as $key => $value) {

            $itemPrestamo = "id_prestamo";
            $valorCuota = $value["id_prestamo"];
            $respuestaPrestamo = ControladorPrestamos::ctrMostrarPrestamos($itemPrestamo, $valorCuota);

            $itemCliente = "id";
            $valorCliente = $respuestaPrestamo["id_cliente"];
            $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

            if($value["id_pago"] == null ||$value["id_pago"]== 0 ){
              $tipoPago = "Abono";

            
            }else{
              $tipoPago = "Pago Cuota";
            }


            // $itemCuota = "id_cuota";
            // $itemValor = $respuesta["id_pago"];
            // $respuestaPagos = ControladorCobros::ctrMostrarCobros($itemCuota,$itemValor);
            // var_dump($respuestaPagos);

            // $itemMonto = "id_cuota";
            // $itemValor = $respuestaPagos["id_cuota"];

            // $respuestaCuotas = ControladorCobros::ctrMostrarCobros($itemCuota,$itemValor);
            // var_dump($respuestaCuotas);


            // if($respuestaCuotas["id_cuota"] == 0 || $respuestaCuotas["id_cuota"] == null ){
            //   $tipoPago = "<button type='button' class='btn btn-success'>Abono</button>";
            // }else{
            //   $tipoPago = "<button type='button' class='btn btn-warning'>Pago Cuota</button>";
              
             
            // }

            $fechaGancia = substr($value["fecha_ganancia"],0,10);

            // if($respuestaCuotas["num_cuota"] == null ||$respuestaCuotas["num_cuota"] == 0 ){
            //   $numCuota = 0;

            // }else{
            //   $numCuota =$respuestaCuotas["num_cuota"] ;
            // }
           
           echo '<tr>

                  <td>'.($key+1).'</td>
                  <td>'.$fechaGancia.'</td>
                  <td>'.$respuestaCliente["nombre"].'</td>

                  <td> '.$respuestaPrestamo["codigo_prestamo"].'</td>

                  <td> '.$tipoPago.'</td>
                  <td>$ '.number_format($value["ganancia"],0).'</td>';

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




