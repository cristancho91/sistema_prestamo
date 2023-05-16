<?php

require_once "conexion.php";

class ModeloCobros{

	/*=============================================
	MOSTRAR CUOTAS
	=============================================*/

	static public function mdlMostrarCobros($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE COBROS
	=============================================*/
	static public function mdlIngresarCobro($tabla, $datos){

		$pdo = Conexion::conectar();
		$pdo->beginTransaction();

		try {

			$stmt = $pdo->prepare("INSERT INTO $tabla(id_prestamo, id_cuota, fecha_pago, monto_pagado, saldo_pendiente) VALUES (:id_prestamo, :id_cuota, NOW(), :monto_pagado, :saldo_pendiente)");

			$stmt->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
			$stmt->bindParam(":id_cuota", $datos["idCuota"], PDO::PARAM_INT);
			$stmt->bindParam(":monto_pagado", $datos["monto"], PDO::PARAM_INT);
			$stmt->bindParam(":saldo_pendiente", $datos["capitalPendiente"], PDO::PARAM_INT);

			if($stmt->execute()){

				// Obtener el ID del préstamo recién creado
				$idPagoCuota = $pdo->lastInsertId();
				$tablaGanancia = "ganancia";

				//llenamos la tabla de ganancias.
				$stmt2 = $pdo->prepare("INSERT INTO $tablaGanancia(id_pago, ganancia) VALUES (:id_pago, :ganancia)");

				$stmt2->bindParam(":id_pago", $idPagoCuota, PDO::PARAM_INT);
				$stmt2->bindParam(":ganancia", $datos["interes"], PDO::PARAM_INT);

				$stmt2->execute();
				//cambiamos los estados de la cuota y vemos que el estado del prestamo
				$stmt4 = $pdo->prepare("UPDATE cuotas SET estado = 0 WHERE id_cuota = :id_cuota");

				$stmt4->bindParam(":id_cuota", $datos["idCuota"], PDO::PARAM_INT);

				$stmt4->execute();

				$stmt5 = $pdo->prepare("UPDATE prestamos SET saldo_pendiente = :saldo_pendiente WHERE id_prestamo = :id_prestamo");

				$stmt5->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
				$stmt5->bindParam(":saldo_pendiente", $datos["capitalPendiente"], PDO::PARAM_INT);

				$stmt5->execute();

				
				if($_POST["capitalPendiente"] == 0){

					$stmt3 = $pdo->prepare("UPDATE prestamos SET estado_prestamo = 0 WHERE id_prestamo = :id_prestamo");

					$stmt3->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);

					$stmt3->execute();

				}

				

				$pdo->commit();

				return "ok";

			}else{

				return "error";
			
			}
		
			$stmt = null;
		}catch (PDOException $e) {
				
			$pdo->rollBack();
			return "error".$e->getMessage();
		}
		

	}

	/*=============================================
	EDITAR Cobro
	=============================================*/
	static public function mdlEditarCobro($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET id_categoria = :id_categoria, descripcion = :descripcion, imagen = :imagen, stock = :stock, precio_compra = :precio_compra, precio_venta = :precio_venta WHERE codigo = :codigo");

		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_INT);
		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_compra", $datos["precio_compra"], PDO::PARAM_STR);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt = null;

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarCobro($tabla,$item, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE $item = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarCobro($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}


		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/	

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();


		$stmt = null;
	}

	/*=============================================
	MOSTRAR Rango de frechas
	=============================================*/	

	static public function mdlRangoFechasCobros($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente FROM $tabla JOIN prestamos ON cuotas.id_prestamo = prestamos.id_prestamo JOIN clientes ON prestamos.id_cliente = clientes.id  ORDER BY fecha_vencimiento ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente FROM $tabla JOIN prestamos ON cuotas.id_prestamo = prestamos.id_prestamo JOIN clientes ON prestamos.id_cliente = clientes.id WHERE fecha_vencimiento like '%$fechaFinal%'");

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

				$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente FROM $tabla JOIN prestamos ON cuotas.id_prestamo = prestamos.id_prestamo JOIN clientes ON prestamos.id_cliente = clientes.id WHERE fecha_vencimiento BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente FROM $tabla JOIN prestamos ON cuotas.id_prestamo = prestamos.id_prestamo JOIN clientes ON prestamos.id_cliente = clientes.id WHERE fecha_vencimiento BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}


}