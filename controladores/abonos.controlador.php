<?php

class ControladorAbonos{


	/*=============================================
	MOSTRAR Abonos
	=============================================*/

	static public function ctrMostrarAbonos($item, $valor){

		$tabla = "abonos";

		$respuesta = ModeloAbonos::mdlMostrarAbonos($tabla, $item, $valor);

		return $respuesta;
	
	}

	/*=============================================
	CREAR ABONOS EN LAS CUOTAS
	=============================================*/
	static public function ctrCrearAbonos(){

		if(isset($_POST['nombreCliente2'])){

			$tabla = "abonos";

			$num_cuota = $_POST['numCouta2'];
			$nombre_cliente = $_POST['nombreCliente2'];
			$id_prestamo = $_POST['idPrestamo2'];
			$tasaInteres= $_POST["interesPrincipal"];
			$id_cuota = $_POST['id_cuota2'];
			$cantidad_abono = $_POST['cantidadAbono'];
			$interesA_pagar = $_POST["interesPagar2"];
			$capital_pagar = $_POST["capitalPagar2"];
			$capital_pendiente = $_POST["capitalPendiente2"];
			$formaPago = $_POST["formaPago"];
			$tiempoMeses = $_POST["tiempo"];
			$fecha_cobro = $_POST["fechaCobro2"];
			$ganancia = 0;

			if($cantidad_abono > 0 && $cantidad_abono <= $interesA_pagar){

				$ganancia = $cantidad_abono;

			}else{

				$capital_pendiente = $capital_pendiente -($cantidad_abono-$interesA_pagar);
				
				$ganancia = $interesA_pagar;
				
			}

			$datos= array("nombre_cliente" => $nombre_cliente,
				"numCuota" => $num_cuota,
				"idPrestamo" => $id_prestamo,
				"idCuota" => $id_cuota,
				"montoAbono" => $cantidad_abono,
				"interesApagar" => $interesA_pagar,
				 "capital" => $capital_pagar,
				 "tasaInteres" => $tasaInteres,
				 "capitalPendiente" => $capital_pendiente,
				 "ganancia"=> $ganancia,
				 "formaPago" => $formaPago,
				 "tiempoMeses" => $tiempoMeses,
				 "fechaCobro" => $fecha_cobro);
			
				
			$respuesta = ModeloAbonos::mdlCrearAbono($tabla,$datos);

			if($respuesta == "ok"){
				
				echo'<script>

				swal({
					  type: "success",
					  title: "El abono ha sido guardada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "abonos";

								}
							})

				</script>';

		


		}else{

			echo'<script>

				swal({
					  type: "error",
					  title: "¡El abono no pudo ser creado!",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
						if (result.value) {

						window.location = "abonos";

						}
					})

			  </script>';

		}
			
		}

	}

	/*=============================================
	RANGO FECHAS ABONOS
	=============================================*/	

	static public function ctrRangoFechasAbonos($fechaInicial, $fechaFinal){

		$tabla = "abonos";

		$respuesta = ModeloAbonos::mdlRangoFechasAbonos($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	/*=============================================
	EDITAR CATEGORIA
	=============================================*/

	static public function ctrEditarCategoria(){

		if(isset($_POST["editarCategoria"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){

				$tabla = "categorias";

				$datos = array("categoria"=>$_POST["editarCategoria"],
							   "id"=>$_POST["idCategoria"]);

				$respuesta = ModeloAbonos::mdlEditarCategoria($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido cambiada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "categorias";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	static public function ctrBorrarCategoria(){

		if(isset($_GET["idCategoria"])){

			$tabla ="Categorias";
			$datos = $_GET["idCategoria"];

			$respuesta = ModeloAbonos::mdlBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
			}
		}
		
	}
}
