<?php

class ControladorCobros{

	/*=============================================
	MOSTRAR CUOTAS
	=============================================*/

	static public function ctrMostrarCobros($item, $valor){

		$tabla = "cuotas";

		$respuesta = ModeloCobros::mdlMostrarCobros($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR CUOTAS
	=============================================*/

	static public function ctrCrearCobro(){


		if(isset($_POST["nombreCliente"])){

			
				$nombreCliente = $_POST["nombreCliente"];
				$numCuota = $_POST["numCouta"];
				$idPrestamo = $_POST["idPrestamo"];
				$idCuota = $_POST["id_cuota"];
				$monto = $_POST["cantidad"];
				$interes = $_POST["interesPagar"];
				$capital = $_POST["capitalPagar"];
				$capitalPendiente = $_POST["capitalPendiente"];
				$fechaCobro = $_POST["fechaCobro"];

				$tabla = "pagos";
				$datos = array(
							   "nombre_cliente" => $nombreCliente,
							   "numCuota" => $numCuota,
							   "idPrestamo" => $idPrestamo,
							   "idCuota" => $idCuota,
							   "monto" => $monto,
							   "interes" => $interes,
								"capital" => $capital,
								"capitalPendiente" => $capitalPendiente,
								"fechaCobro" => $fechaCobro);

				$respuesta = ModeloCobros::mdlIngresarCobro($tabla, $datos);

				// var_dump($respuesta);
				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El cobro de ha generado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "cobros";

										}
									})

						</script>';

				}else{

					echo'<script>
	
						swal({
							  type: "error",
							  title: "¡El pago no se realizó correctamente!",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
								if (result.value) {
	
								window.location = "cobros";
	
								}
							})
							</script>';
					}
	


			
		}

	}

	/*=============================================
	EDITAR COBRO
	=============================================*/

	static public function ctrEditarCobro(){

		if(isset($_POST["editarDescripcion"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"]) &&
			   preg_match('/^[0-9]+$/', $_POST["editarStock"]) &&	
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioCompra"]) &&
			   preg_match('/^[0-9.]+$/', $_POST["editarPrecioVenta"])){

		   		/*=============================================
				VALIDAR IMAGEN
				=============================================*/

			   	$ruta = $_POST["imagenActual"];

			   	if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["editarCodigo"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/anonymous.png"){

						unlink($_POST["imagenActual"]);

					}else{

						mkdir($directorio, 0755);	
					
					}
					
					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);						

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "productos";

				$datos = array("id_categoria" => $_POST["editarCategoria"],
							   "codigo" => $_POST["editarCodigo"],
							   "descripcion" => $_POST["editarDescripcion"],
							   "stock" => $_POST["editarStock"],
							   "precio_compra" => $_POST["editarPrecioCompra"],
							   "precio_venta" => $_POST["editarPrecioVenta"],
							   "imagen" => $ruta);

				$respuesta = ModeloCobros::mdlEditarCobro($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

						swal({
							  type: "success",
							  title: "El producto ha sido editado correctamente",
							  showConfirmButton: true,
							  confirmButtonText: "Cerrar"
							  }).then(function(result){
										if (result.value) {

										window.location = "productos";

										}
									})

						</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}

	

	/*=============================================
	BORRAR COBRO, CUOTA
	=============================================*/
	static public function ctrEliminarCobro($item,$valor){

		$tabla="cuotas";
		$respuesta =ModeloCobros::mdlEliminarCobro($tabla,$item,$valor);
		return $respuesta; 


	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	static public function ctrMostrarSumaVentas(){

		$tabla = "productos";

		$respuesta = ModeloCobros::mdlMostrarSumaVentas($tabla);

		return $respuesta;

	}

	/*=============================================
	RANGO FECHAS CUOTAS
	=============================================*/	

	static public function ctrRangoFechasCobros($fechaInicial, $fechaFinal){

		$tabla = "cuotas";

		$respuesta = ModeloCobros::mdlRangoFechasCobros($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

}