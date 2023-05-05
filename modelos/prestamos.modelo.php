<?php

require_once "conexion.php";

class ModeloPrestamos{

	/*=============================================
	MOSTRAR PRESTMOS
	=============================================*/

	static public function mdlMostrarPrestamos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id_prestamo ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id_prestamo ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PRESTAMO
	=============================================*/

	static public function mdlIngresarPrestamo($tabla, $datos){



		$pdo = Conexion::conectar();
		$pdo->beginTransaction();
		try {
			

			

			$stmt = $pdo->prepare("INSERT INTO $tabla(id_cliente,  id_prestador,codigo_prestamo, monto, tasa_interes, fecha_prestamo, tiempo_en_meses,forma_pago,saldo_pendiente, estado_prestamo) VALUES ( :id_cliente, :id_prestador,:codigo_prestamo, :monto, :tasa_interes, :fecha_prestamo, :tiempo_en_meses,:forma_pago, :saldo_pendiente, :estado_prestamo)");

			$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
			$stmt->bindParam(":id_prestador", $datos["id_vendedor"], PDO::PARAM_INT);
			$stmt->bindParam(":codigo_prestamo", $datos["codigo"], PDO::PARAM_STR);
			$stmt->bindParam(":monto", $datos["nuevoPrestamo"], PDO::PARAM_INT);
			$stmt->bindParam(":tasa_interes", $datos["interes"], PDO::PARAM_INT);
			$stmt->bindParam(":fecha_prestamo", $datos["fechaPrestamo"], PDO::PARAM_STR);
			$stmt->bindParam(":tiempo_en_meses", $datos["nuevoMetodoPago"], PDO::PARAM_INT);
			$stmt->bindParam(":forma_pago", $datos["formaPago"], PDO::PARAM_STR);
			$stmt->bindParam(":saldo_pendiente", $datos["saldo_pendiente"], PDO::PARAM_INT);
			$stmt->bindParam(":estado_prestamo", $datos["estado"], PDO::PARAM_INT);

			
			if($stmt->execute()){

				// Obtener el ID del préstamo recién creado
				$id_prestamo = $pdo->lastInsertId();

				$cantidad_compras = $datos["cantidadCompra"] + 1;
				// Actualización de campos de tabla clientes

				$stmt2 = $pdo->prepare("UPDATE clientes SET compras = :compras,ultima_compra = NOW() WHERE id = :id");
				$stmt2->bindParam(":id", $datos["id_cliente"], PDO::PARAM_INT);
				$stmt2->bindParam(":compras", $cantidad_compras, PDO::PARAM_INT);

				$stmt2->execute();

				

				// Obtenemos el método de pago para el nuevo préstamo
				$forma_pago = $datos["formaPago"];

				$fecha_vencimiento = $datos["nuevoMetodoPago"];

				// Calculamos la cantidad de cuotas a crear según el método de pago
				$tipoPago = "";

				switch ($forma_pago) {
					case 'diario':
						$cuotas_a_crear = $fecha_vencimiento * 30;
						$tipoPago = "day";
						break;
					case 'semanal':
						$cuotas_a_crear = $fecha_vencimiento * 4;
						$tipoPago = "week";
						break;
					case 'quincenal':
						$cuotas_a_crear = $fecha_vencimiento * 2;
						$tipoPago = "week";
						break;
					case 'mensual':
						$cuotas_a_crear = $fecha_vencimiento;
						$tipoPago = "month";
						break;
				}


				// Calcular el monto de la cuota (capital + interés)
				$capital = $datos["nuevoPrestamo"];
				$interes = $datos["interes"] / 100;
				$cuota = $capital * ($interes / (1 - pow(1 + $interes, -$cuotas_a_crear)));

				

				// Insertar las cuotas
				$stmt3 = $pdo->prepare("INSERT INTO cuotas (id_prestamo, num_cuota, monto_cuota, interes_a_pagar, capital_a_pagar, cantidad_pendiente, estado, fecha_vencimiento) VALUES (:id_prestamo, :num_cuota, :monto_cuota, :interes_a_pagar, :capital_a_pagar, :cantidad_pendiente, :estado, :fecha_vencimiento)");
				$quincena = 0;
				for ($i = 1; $i <= $cuotas_a_crear; $i++) {
					// Calcular el interés y capital a pagar para esta cuota
					$interes_a_pagar = $datos["saldo_pendiente"] * $interes;
					$capital_a_pagar = $cuota - $interes_a_pagar;
					$cantidad_pendiente = $datos["saldo_pendiente"] - $capital_a_pagar;

					// Insertar la cuota en la tabla
					$stmt3->bindParam(":id_prestamo", $id_prestamo, PDO::PARAM_INT);
					$stmt3->bindParam(":num_cuota", $i, PDO::PARAM_INT);
					$stmt3->bindParam(":monto_cuota", $cuota, PDO::PARAM_INT);
					$stmt3->bindParam(":interes_a_pagar", $interes_a_pagar, PDO::PARAM_INT);
					$stmt3->bindParam(":capital_a_pagar", $capital_a_pagar, PDO::PARAM_INT);
					$stmt3->bindParam(":cantidad_pendiente", $cantidad_pendiente, PDO::PARAM_INT);
					$stmt3->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);

					if($forma_pago == "quincenal"){
						$quincena +=2 ;
						
						$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaPrestamo"] . "+ $quincena $tipoPago"));
					}else{
						$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaPrestamo"] . "+ $i $tipoPago"));
					}
					
					$stmt3->bindParam(":fecha_vencimiento", $fecha_vencimiento, PDO::PARAM_STR);

					$stmt3->execute();

					// Actualizar el saldo pendiente
					$datos["saldo_pendiente"] = $cantidad_pendiente;
				}

				// Confirmar la transacción
				$pdo->commit();
						
				return "ok";

			}else{

				return "error";
			
			}

			$stmt = null;
	
		} catch (PDOException $e) {
				
			$pdo->rollBack();
			return "error".$e->getMessage();
		}

	}

	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function mdlEditarPrestamo($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET  id_cliente = :id_cliente, id_vendedor = :id_vendedor, productos = :productos, impuesto = :impuesto, neto = :neto, total= :total, metodo_pago = :metodo_pago WHERE codigo = :codigo");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_INT);
		$stmt->bindParam(":id_cliente", $datos["id_cliente"], PDO::PARAM_INT);
		$stmt->bindParam(":id_vendedor", $datos["id_vendedor"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
		$stmt->bindParam(":neto", $datos["neto"], PDO::PARAM_STR);
		$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":metodo_pago", $datos["metodo_pago"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
		$stmt = null;

	}

	/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function mdlEliminarPrestamo($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_prestamo = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt = null;

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasPrestamos($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente, usuarios.nombre AS nombre_usuario FROM $tabla JOIN clientes ON prestamos.id_cliente = clientes.id JOIN usuarios ON prestamos.id_prestador = usuarios.id  ORDER BY id_prestamo ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente, usuarios.nombre AS nombre_usuario FROM $tabla JOIN clientes ON prestamos.id_cliente = clientes.id JOIN usuarios ON prestamos.id_prestador = usuarios.id WHERE fecha_prestamo like '%$fechaFinal%'");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente, usuarios.nombre AS nombre_usuario FROM $tabla JOIN clientes ON prestamos.id_cliente = clientes.id JOIN usuarios ON prestamos.id_prestador = usuarios.id WHERE fecha_prestamo BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente, usuarios.nombre AS nombre_usuario FROM $tabla JOIN clientes ON prestamos.id_cliente = clientes.id JOIN usuarios ON prestamos.id_prestador = usuarios.id WHERE fecha_prestamo BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

	/*=============================================
	SUMAR EL TOTAL DE PRESTAMOS
	=============================================*/

	static public function mdlSumaTotalPrestamos($tabla,$item,$valor){	

		$stmt = Conexion::conectar()->prepare("SELECT SUM(id_prestamo) as prestamos_cliente FROM $tabla WHERE id_cliente = :$item");

		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt = null;

	}


	/*=============================================
	CONTAR LA CANTIDAD DE PRESTAMOS POR CLIENTE Y TOTAL DE PRESTAMOS
	=============================================*/

	static public function mdlContarPrestamos($tabla,$item,$valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT COUNT(*) AS cantidad_prestamos FROM $tabla WHERE id_cliente = :$item AND estado_prestamo = 1 ");


			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch();
		}else{

			$stmt = Conexion::conectar()->prepare("SELECT COUNT(*) AS total_prestamos FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetch();


		}
		$stmt = null;
	}

	/*=============================================
	CONTAR LA CANTIDAD DE PRESTAMOS ACTIVOS
	=============================================*/

	static public function mdlContarPrestamosActivos($tabla){	

		$stmt = Conexion::conectar()->prepare("SELECT COUNT(*) AS cantidad_prestamos FROM $tabla WHERE estado_prestamo = 1 ");

		$stmt -> execute();

		return $stmt -> fetch();
		
		$stmt = null;
	}

	
}