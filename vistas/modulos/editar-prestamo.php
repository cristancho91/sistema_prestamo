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
      
      Editar prestamo
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Editar prestamo</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->
      
      <div class="col-lg-5 col-xs-12">
        
        <div class="box box-success">
          
          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioVenta">

            <div class="box-body">
  
              <div class="box">

                <?php

                  $item = "id_prestamo";
                  $valor = $_GET["idPrestamo"];

                  $prestamo = ControladorPrestamos::ctrMostrarPrestamos($item, $valor);

                  $itemUsuario = "id";
                  $valorUsuario = $prestamo["id_prestador"];

                  $vendedor = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                  $itemCliente = "id";
                  $valorCliente = $prestamo["id_cliente"];

                  $cliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);


                ?>

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
            
                <div class="form-group">
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $vendedor["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $vendedor["id"]; ?>">

                  </div>

                </div> 

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group">
                  
                <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="nuevoPrestamo" name="nuevoPrestamo" value="<?php echo $prestamo["codigo_prestamo"]; ?>" readonly>
               
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control" id="seleccionarCliente" name="seleccionarCliente" required>

                    <option value="<?php echo $cliente["id"]; ?>"><?php echo $cliente["nombre"]; ?></option>

                    <?php

                      $item = null;
                      $valor = null;

                      $categorias = ControladorClientes::ctrMostrarClientes($item, $valor);

                       foreach ($categorias as $key => $value) {

                         echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';

                       }

                    ?>

                    </select>
                    
                    <span class="input-group-addon"><button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar cliente</button></span>
                  
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR prestamo
                ======================================-->
                <div class="input-group">
      
                  <span class="input-group-addon"><i class="fa fa-money"></i></span>

                  <input type ="number" class="form-control editarMonto"  name="editarMonto" value="<?php echo $prestamo["monto"];?>" placeholder="introduce la cantidad a prestar..."  required>

                </div>

                <hr> 

                <div class="row">

                  <!--=====================================
                  ENTRADAS
                  ======================================-->
                  
                  <div class="col-xs-12 col-lg-12 ">
                    
                      <div class="form-group col-lg-6">
                        
                        <div class="input-group">
                          <label for="">Plazo:</label>
                          <select class="form-control" id="nuevoMetodoPago" name="nuevoMetodoPago" required>
                          

                          <?php 
                          if($prestamo["tiempo_en_meses"] == 12){
                            echo '<option value="'.$prestamo["tiempo_en_meses"].'">1 año</option>';
                          }else{
                            echo '<option value="'.$prestamo["tiempo_en_meses"].'">'.$prestamo["tiempo_en_meses"].' meses</option>';
                          }

                          

                          for ($i=1; $i <= 12 ; $i++) { 
                            if($i == 12){
                              echo '<option value="'.$i.'">1 año</option>'; 

                            }else{
                              echo '<option value="'.$i.'">'.$i.' meses</option>'; 
                            }
                          }

                          ?>                  
                          </select>    

                        </div>
                      </div>
                      <div class="col-lg-6">
                        <label for="">Interes:</label>

                        <select class="form-control" id="interes" name="interes" required>
                        <?php
                              echo '<option value="'.$prestamo["tasa_interes"].'">'.$prestamo["tasa_interes"].'%</option>';

                            ?>
                            <option value="10">10%</option>
                            <option value="5">5%</option>                 
                          </select> 
                          <!-- <input class="form-control" type="date" id="fechaPlazo" name="fechaPlazo"> -->
                          
                      </div>
                    

                  </div>

                  <hr>

                  <div class="col-xs-12 col-lg-12 ">
                    
                      <div class="form-group col-lg-6">
                        
                        <div class="input-group">

                        <label for="">Forma de pago:</label>
                      
                          <select class="form-control" id="formaPago" name="formaPago" required>
                            <?php
                              echo '<option value="'.$prestamo["forma_pago"].'">'.strtoupper($prestamo["forma_pago"]).'</option>';

                            ?>
                            
                            <option value="diario">DIARIO</option>
                            <option value="semanal">SEMANAL</option>
                            <option value="quincenal">QUINCENAL</option>
                            <option value="mensual">MENSUAL</option>                  
                          </select>    

                        </div>

                        
                    </div>
                    <div class="col-lg-6">
                      <label for="">Fecha de inicio:</label>
                      <input class="form-control" type="date" id="fechaPrestamo" name="fechaPrestamo" value="<?php echo $prestamo["fecha_prestamo"];  ?>" required>

                    </div>

                  </div>

                </div>
      
              </div>

          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Guardar Cambios</button>

          </div>

        </form>

        <?php

          $guardarPrestamoEditado = new ControladorPrestamos();
          $guardarPrestamoEditado -> ctrEditarPrestamo();
          
        ?>

        </div>
            
      </div>

    </div>
   
  </section>

</div>

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalAgregarCliente" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

      </form>

      <?php

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente();

      ?>

    </div>

  </div>

</div>
