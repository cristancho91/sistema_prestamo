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
					  title: "La venta ha sido guardada correctamente",
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

		if(isset($_POST["editarVenta"])){

			/*=============================================
			FORMATEAR TABLA DE PRESTAMOS Y  CLIENTES
			=============================================*/
			$tabla = "prestamos";

			$item = "id_prestamo";
			$valor = $_POST["editarVenta"];

			$traerVenta = ModeloPrestamos::mdlMostrarPrestamos($tabla, $item, $valor);

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;


			}else{

				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}

			if($cambioProducto){

				$productos =  json_decode($traerVenta["productos"], true);

				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosComprados, $value["cantidad"]);
					
					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerCobros = ModeloCobros::mdlMostrarCobros($tablaProductos, $item, $valor, $orden);

					$item1a = "ventas";
					$valor1a = $traerCobros["ventas"] - $value["cantidad"];

					$nuevasVentas = ModeloCobros::mdlActualizarCobro($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerCobros["stock"];

					$nuevoStock = ModeloCobros::mdlActualizarCobro($tablaProductos, $item1b, $valor1b, $valor);

				}

				$tablaClientes = "clientes";

				$itemCliente = "id";
				$valorCliente = $_POST["seleccionarCliente"];

				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

				/*=============================================
				ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/

				$listaProductos_2 = json_decode($listaProductos, true);

				$totalProductosComprados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {

					array_push($totalProductosComprados_2, $value["cantidad"]);
					
					$tablaProductos_2 = "productos";

					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";

					$traerCobros_2 = ModeloCobros::mdlMostrarCobros($tablaProductos_2, $item_2, $valor_2, $orden);

					$item1a_2 = "ventas";
					$valor1a_2 = $value["cantidad"] + $traerCobros_2["ventas"];

					$nuevasVentas_2 = ModeloCobros::mdlActualizarcobro($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $traerCobros_2["stock"] - $value["cantidad"];

					$nuevoStock_2 = ModeloCobros::mdlActualizarCobro($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);

				}

				$tablaClientes_2 = "clientes";

				$item_2 = "id";
				$valor_2 = $_POST["seleccionarCliente"];

				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);

				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];

				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);

				$item1b_2 = "ultima_compra";

				date_default_timezone_set('America/Bogota');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha.' '.$hora;

				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);

			}

			/*=============================================
			GUARDAR CAMBIOS DEL PRESTAMO
			=============================================*/	

			$datos = array("id_vendedor"=>$_POST["idVendedor"],
						   "id_cliente"=>$_POST["seleccionarCliente"],
						   "codigo"=>$_POST["editarVenta"],
						   "productos"=>$listaProductos,
						   "impuesto"=>$_POST["nuevoPrecioImpuesto"],
						   "neto"=>$_POST["nuevoPrecioNeto"],
						   "total"=>$_POST["totalVenta"],
						   "metodo_pago"=>$_POST["listaMetodoPago"]);


			$respuesta = ModeloPrestamos::mdlEditarPrestamo($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}

		}

	}


	/*=============================================
	ELIMINAR VENTA
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
					  title: "La venta ha sido borrada correctamente",
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

			$Name = $_GET["reporte"].'.xls';

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
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>GANANCIA_VENTA</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($prestamos as $row => $item){

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_vendedor"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["codigo"]."</td> 
			 			<td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$productos =  json_decode($item["productos"], true);

			 	foreach ($productos as $key => $valueProductos) {
			 			
			 			echo utf8_decode($valueProductos["cantidad"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($productos as $key => $valueProductos) {
			 			
		 			echo utf8_decode($valueProductos["descripcion"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["impuesto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["neto"],2)."</td>	
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["ganancia_venta"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}

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