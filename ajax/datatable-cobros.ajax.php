<?php

require_once "../controladores/cobros.controlador.php";
require_once "../modelos/cobros.modelo.php";



class TablaCobros{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/ 

	public function mostrarTablaCobros(){


		if(isset($_GET["fechaInicial"])){

            $fechaInicial = $_GET["fechaInicial"];
            $fechaFinal = $_GET["fechaFinal"];

          }else{

            $fechaInicial = null;
            $fechaFinal = null;

          }

		//   var_dump($fechaFinal,$fechaInicial);

  		$cobros = ControladorCobros::ctrRangoFechasCobros($fechaInicial, $fechaFinal);


  		if(count($cobros) == 0){

  			echo '{"data": []}';

		  	return;
  		}
		
  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($cobros); $i++){

			if($cobros[$i]["estado"]){
				$activo = "<button type='button' class='btn btn-success'>Activo</button>";
			}else{
				$activo = "<button type='button' class='btn btn-warning'>Pagado</button>";
			}

		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

  			if(isset($_GET["perfilOculto"]) && $_GET["perfilOculto"] == "Especial"){

  				$botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$cobros[$i]["id_cuota"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button></div>"; 

  			}else{

  				 $botones =  "<div class='btn-group'><button class='btn btn-warning btnEditarProducto' idProducto='".$cobros[$i]["id_cuota"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button class='btn btn-danger btnEliminarProducto' idProducto='".$cobros[$i]["id_cuota"]."' codigo='".$cobros[$i]["id_prestamo"]."'><i class='fa fa-times'></i></button></div>"; 

  			}

		 
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$cobros[$i]["nombre_cliente"].'",
			      "'.$cobros[$i]["num_cuota"].'",
			      "'.$cobros[$i]["monto_cuota"].'",
			      "'.$cobros[$i]["interes_a_pagar"].'",
			      "'.$cobros[$i]["capital_a_pagar"].'",
			      "'.$cobros[$i]["cantidad_pendiente"].'",
			      "'.$activo.'",
			      "'.$cobros[$i]["fecha_vencimiento"].'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   '] 

		 }';
		
		echo $datosJson;


	}


}

/*=============================================
ACTIVAR TABLA DE PRODUCTOS
=============================================*/ 
$activarProductos = new TablaCobros();
$activarProductos -> mostrarTablaCobros();

