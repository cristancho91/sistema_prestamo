<?php

class ControladorPrestamos{

	/*=============================================
	MOSTRAR PRESTAMOS
	=============================================*/

	static public function ctrMostrarPrestamos($item, $valor){

		$tabla = "prestamos";

		$respuesta = ModeloPrestamos::mdlMostrarPrestamos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR PRESTAMOS
	=============================================*/

	static public function ctrCrearPrestamo(){

		if(isset($_POST["nuevoPrestamo"])){

			/*=============================================
			
			=============================================*/

			if($_POST["nuevoPrestamo"] == ""){

					echo'<script>

				swal({
					  type: "error",
					  title: "el prestamo no se pudo realizar.",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "prestamos";

								}
							})

				</script>';

				return;
			}

			$tablaPrestamos = "prestamos";
			$itemSumaPrestamas = "id_cliente";
			$valorSumaPrestamos = $_POST["seleccionarCliente"];

			$sumaPrestamosCliente =ControladorPrestamos::ctrSumaTotalPrestamos($tablaPrestamos,$itemSumaPrestamas,$valorSumaPrestamos);

			
			$tablaClientes = "clientes";

			$item = "id";
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valorSumaPrestamos);

			$item1a = "compras";
			if(is_array($sumaPrestamosCliente) && $sumaPrestamosCliente > 0){
				$valor1a = $sumaPrestamosCliente[0] +1;
			}else{
				$valor1a = 1;
			}
			

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorSumaPrestamos);

			$item1b = "ultima_compra";

			date_default_timezone_set('America/Bogota');

			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$valor1b = $fecha.' '.$hora;

			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valorSumaPrestamos);

			$estado = 1;


			/*=============================================
			GUARDAR EL PRESTAMOS
			=============================================*/	

			$tabla = "prestamos";

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_cliente"=>$_POST["seleccionarCliente"],
						   "id_codeudor"=>$_POST["seleccionarCodeudor"],
						   "codigo"=>$_POST["codigoPrestamo"],
						   "nuevoPrestamo"=>$_POST["nuevoPrestamo"],
						   "nuevoMetodoPago"=>$_POST["nuevoMetodoPago"],
						   "interes"=>$_POST["interes"],
						   "saldo_pendiente"=>$_POST["nuevoPrestamo"],
						   "formaPago"=>$_POST["formaPago"],
						   "estado"=>$estado,
						   "fechaPrestamo"=>$_POST["fechaPrestamo"],
							"cantidadCompra" => $traerCliente["compras"]);


			$respuesta = ModeloPrestamos::mdlIngresarPrestamo($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "El prestamo ha sido guardada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "prestamos";

								}
							})

				</script>';

			}

		}

	}

	/*=============================================
	EDITAR PRESTAMO
	=============================================*/

	static public function ctrEditarPrestamo(){

		if(isset($_POST["idPrestamo"])){

			/*=============================================
			FORMATEAR TABLA DE PRESTAMOS Y  CLIENTES
			=============================================*/
			$tabla = "prestamos";

			$item = "id_prestamo";
			$valor = $_POST["idPrestamo"];

			$traerPrestamo = ModeloPrestamos::mdlMostrarPrestamos($tabla, $item, $valor);
			$saldoPendiente = $traerPrestamo["saldo_pendiente"];
			//cliente nuevo
			$tablaClientes = "clientes";
			$itemCliente = "id";
			$valorCliente = $_POST["seleccionarCliente"];
			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);
			$compraCliente = $traerCliente["compras"];
			//cliente anterior
			$valorCliente2 = $_POST["idClienteViejo"];
			$traerCliente2 = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente2);
			$compraCliente2 = $traerCliente2["compras"];
			

			if($_POST["idClienteViejo"] != $_POST["seleccionarCliente"]){

				$compraCliente +=1;
				$compraCliente2 -=1;

			
			}

			/*=============================================
			GUARDAR CAMBIOS DEL PRESTAMO
			=============================================*/	

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_cliente"=>$_POST["seleccionarCliente"],
						   "id_clienteViejo"=>$_POST["idClienteViejo"],
						   "id_prestamo"=>$_POST["idPrestamo"],
						   "codigo"=>$_POST["nuevoPrestamo"],
						   "comprasClienteNuevo"=>$compraCliente,
						   "comprasClienteViejo"=>$compraCliente2,
						   "codeudor"=>$_POST["seleccionarCodeudor"],
						   "monto"=>$_POST["editarMonto"],
						   "montoAnterior"=>$_POST["montoAnterior"],
						   "plazo"=>$_POST["nuevoMetodoPago"],
						   "tasa_interes"=>$_POST["interes"],
						   "fecha_prestamo"=>$_POST["fechaPrestamo"],
						   "saldoPendiente"=>$saldoPendiente,
						   "formaPago"=>$_POST["formaPago"]);


			$respuesta = ModeloPrestamos::mdlEditarPrestamo($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "El prestamo ha sido editado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "prestamos";

								}
							})

				</script>';

			}else{

				echo'<script>
	
			swal({
				  type: "error",
				  title: "el prestamo no se pudo Actualizar.",
				  showConfirmButton: true,
				  confirmButtonText: "Cerrar"
				  }).then(function(result){
							if (result.value) {
	
							window.location = "prestamos";
	
							}
						})
	
				</script>';
		
				return;
			}

		}

			

	}



	/*=============================================
	ELIMINAR PRESTAMO
	=============================================*/

	static public function ctrEliminarPrestamo(){

		if(isset($_GET["idPrestamo"])){

			$tabla = "prestamos";

			$item = "id_prestamo";
			$valor = $_GET["idPrestamo"];

			$traerVenta = ModeloPrestamos::mdlMostrarPrestamos($tabla, $item, $valor);

			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/

			$tablaClientes = "clientes";

			$itemVentas = null;
			$valorVentas = null;

			$traerVentas = ModeloPrestamos::mdlMostrarPrestamos($tabla, $itemVentas, $valorVentas);

			$guardarFechas = array();

			foreach ($traerVentas as $key => $value) {
				
				if($value["id_cliente"] == $traerVenta["id_cliente"]){

					array_push($guardarFechas, $value["fecha_prestamo"]);

				}

			}

			if(count($guardarFechas) > 1){

				if($traerVenta["fecha_prestamo"] > $guardarFechas[count($guardarFechas)]){

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}else{

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-1];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}


			}else{

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdCliente = $traerVenta["id_cliente"];

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

			}

			/*=============================================
			FORMATEAR TABLA  DE CLIENTES
			=============================================*/


			$tablaClientes = "clientes";

			$itemCliente = "id";
			$valorCliente = $traerVenta["id_cliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

			$item1a = "compras";
			$valor1a = $traerCliente["compras"] - 1;

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

			/*=============================================
			ELIMINAR CUOTAS
			=============================================*/
			
			$itemCuota = "id_prestamo";
			$valorCouta = $_GET["idPrestamo"];
			$traerCouta = ControladorCobros::ctrEliminarCobro($itemCuota, $valorCouta);


			/*=============================================
			ELIMINAR PRESTAMO
			=============================================*/

			$respuesta = ModeloPrestamos::mdlEliminarPrestamo($tabla, $_GET["idPrestamo"]);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El Prestamo ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "prestamos";

								}
							})

				</script>';

			}		
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasPrestamos($fechaInicial, $fechaFinal){

		$tabla = "prestamos";

		$respuesta = ModeloPrestamos::mdlRangoFechasPrestamos($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

	

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = "prestamos";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$prestamos = ModeloPrestamos::mdlRangoFechasPrestamos($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$prestamos = ModeloPrestamos::mdlMostrarPrestamos($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/
			$fecha = date('Y-m-d'); // Obtener la fecha actual en formato AñoMesDía (por ejemplo, 20230528)
			$Name = $_GET["reporte"] . '_' . $fecha . '.xlsx'; // Concatenar la fecha a la variable $Name


			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'> 

					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>MONTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>INTERES</td>
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA PRESTAMO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>TIEMPO EN MESES</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>FORMA DE PAGO</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>SALDO PENDIENTE</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>ESTADO PRESTAMO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($prestamos as $row => $item){

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_prestador"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["codigo_prestamo"]."</td> 
			 			<td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>

			 	");
				// 		<td style='border:1px solid #eee;'>
			 	// $productos =  json_decode($item["productos"], true);

			 	// foreach ($productos as $key => $valueProductos) {
			 			
			 	// 		echo utf8_decode($valueProductos["cantidad"]."<br>");
			 	// 	}

			 	// echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		// foreach ($productos as $key => $valueProductos) {
			 			
		 		// 	echo utf8_decode($valueProductos["descripcion"]."<br>");
				if($item["estado_prestamo"]){
					$estado = "ACTIVO";
				}else{
					$estado="PAGADO";
				}
		 		
		 		// }

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["monto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["tasa_interes"],2)."</td>	
					<td style='border:1px solid #eee;'>$ ".$item["fecha_prestamo"]."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["tiempo_en_meses"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["forma_pago"]."</td>
					<td style='border:1px solid #eee;'>".number_format($item["saldo_pendiente"])."</td>
					<td style='border:1px solid #eee;'>".$estado."</td>

					<td style='border:1px solid #eee;'>".substr($item["fecha_ultima_actualizacion"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}

	}
	/*=============================================
	RECOGER PRESTAMO
	=============================================*/

	static public function ctrRecogerPrestamo(){
		if(isset($_POST["idPrestamo"])){

			$id_prestamo = $_POST["idPrestamo"];
			$tabla = "pagos";

			$datos = array(
				"id_prestamo" => $id_prestamo,
				"montoPagado" => $_POST["cantidad"],
				"cantiad_prestada" =>$_POST["cantidad_prestamo"],
				"iteresPagar"=> $_POST["interesPagar"],
				"capitalPagar" =>$_POST["capitalPagar"]
			);

			$respuesta =ModeloPrestamos::mdlRecogerPrestamo($tabla,$datos);
			var_dump($respuesta);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "El prestamo se recogió correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "prestamos";

								}
							})

				</script>';

			}

		};
	}

	/*=============================================
	SUMA TOTAL prestamos
	=============================================*/

	static public function ctrSumaTotalPrestamos($item,$valor){

		$tabla = "prestamos";
		$respuesta = ModeloPrestamos::mdlSumaTotalPrestamos($tabla,$item,$valor);

		return $respuesta;

	}

	/*=============================================
	CONTAR LA CANTIDAD DE PRESTAMOS POR CLIENTE Y TOTAL DE PRESTAMOS
	=============================================*/
	static public function ctrContarPrestamos($item,$valor){
		$tabla = "prestamos";
		$respuesta = ModeloPrestamos::mdlContarPrestamos($tabla,$item,$valor);
		return $respuesta;
	}

	/*=============================================
	CONTAR LA CANTIDAD DE PRESTAMOS ACTIVOS
	=============================================*/
	static public function ctrContarPrestamosActivos($tabla){
		$respuesta = ModeloPrestamos::mdlContarPrestamosActivos($tabla);
		return $respuesta;
	}

}