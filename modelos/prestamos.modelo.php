<?php

require_once "conexion.php";

class ModeloPrestamos{

	/*=============================================
	MOSTRAR VENTAS
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

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_cliente,  id_prestador,codigo_prestamo, monto, tasa_interes, fecha_prestamo, tiempo_en_meses,forma_pago,saldo_pendiente, estado_prestamo) VALUES ( :id_cliente, :id_prestador,:codigo_prestamo, :monto, :tasa_interes, :fecha_prestamo, :tiempo_en_meses,:forma_pago, :saldo_pendiente, :estado_prestamo)");

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

			return "ok";

		}else{

			return "error";
		
		}
		$stmt = null;

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

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

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

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha like '%$fechaFinal%'");

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

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal'");

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

	
}