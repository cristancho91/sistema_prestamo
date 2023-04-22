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
      
      Crear prestamo
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Crear prestamo</li>
    
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

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
            
                <div class="form-group">
                
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idVendedor" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div> 

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                    <?php

                    $item = null;
                    $valor = null;

                    $ventas = ControladorPrestamos::ctrMostrarPrestamos($item, $valor);

                    if(!$ventas){

                      echo '<input type="text" class="form-control" id="codigoPrestamo" name="codigoPrestamo" value="10001" readonly>';
                  

                    }else{

                      foreach ($ventas as $key => $value) {
                        
                        
                      
                      }

                      $codigo = $value["id_prestamo"] + 1;



                      echo '<input type="text" class="form-control" id="codigoPrestamo" name="codigoPrestamo" value="'.$codigo.'" readonly>';
                  

                    }

                    ?>
                    
                    
                  </div>
                
                </div>

                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================--> 

                <div class="form-group">
                  
                  <div class="input-group">
                    
                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                    
                    <select class="form-control" id="seleccionarCliente" name="seleccionarCliente" required>

                    <option value="">Seleccionar cliente</option>

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

                  <input type ="number" class="form-control nuevoPrestamo"  name="nuevoPrestamo" value="" placeholder="introduce la cantidad a prestar..."  required>

                </div>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================--> 

                <!-- <div class="form-group row nuevoProducto">

                

                </div>

                <input type="hidden" id="listaProductos" name="listaProductos"> -->

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <!-- <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>-->

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
                            <option value="">Seleccione el plazo </option>
                            <option value="1">1 mes</option>
                            <option value="2">2 meses</option>
                            <option value="3">3 Meses</option>
                            <option value="4">4 Meses</option>
                            <option value="5">5 Meses</option>
                            <option value="6">6 Meses</option>                  
                            <option value="7">7 Meses</option>                  
                            <option value="8">8 Meses</option>                  
                            <option value="9">9 Meses</option>                  
                            <option value="10">10 Meses</option>                  
                            <option value="11">11 Meses</option>                  
                            <option value="12">1 Año</option>                  
                          </select>    

                        </div>
                      </div>
                      <div class="col-lg-6">
                        <label for="">Interes:</label>

                        <select class="form-control" id="interes" name="interes" required>
                            <option value="">Seleccione el interes </option>
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
                            <option value="">Seleccione la forma de pago</option>
                            <option value="diario">Diario</option>
                            <option value="semanal">Semanal</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="mensual">Mensual</option>                  
                          </select>    

                        </div>

                        
                    </div>
                    <div class="col-lg-6">
                      <label for="">Fecha de inicio:</label>
                      <input class="form-control" type="date" id="fechaPrestamo" name="fechaPrestamo">

                    </div>

                  </div>

                </div>
      
              </div>

          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Guardar prestamo</button>

          </div>

        </form>

        <?php

          $guardarVenta = new ControladorPrestamos();
          $guardarVenta -> ctrCrearPrestamo();
          
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
