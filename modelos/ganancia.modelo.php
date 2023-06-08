<?php

require_once "conexion.php";

class ModeloGanancia{
    /*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasGanancia($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT g.*,p.*,c.nombre AS nombre_cliente
            FROM $tabla g
			INNER JOIN prestamos p ON g.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id ORDER BY fecha_ganancia ASC");

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT g.*,p.*,c.nombre AS nombre_cliente
            FROM $tabla g
			INNER JOIN prestamos p ON g.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id  WHERE fecha_ganancia like '%$fechaFinal%'");

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT g.*,p.*,c.nombre AS nombre_cliente
            FROM $tabla g
			INNER JOIN prestamos p ON g.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id  WHERE fecha_ganancia BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT g.*,p.*,c.nombre AS nombre_cliente
            FROM $tabla g
			INNER JOIN prestamos p ON g.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id  WHERE fecha_ganancia BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		}

	}
    static public function mdlSumaGananciaFecha($tabla, $fechaInicial, $fechaFinal){

        if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancias FROM $tabla ");

			$stmt -> execute();

			return $stmt -> fetch(PDO::FETCH_ASSOC);	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancias FROM $tabla WHERE fecha_ganancia LIKE '%$fechaFinal%'");

			$stmt -> execute();

			return $stmt -> fetch(PDO::FETCH_ASSOC);

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancias FROM $tabla WHERE fecha_ganancia BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SSELECT SUM(ganancia) AS ganancias FROM $tabla WHERE fecha_ganancia BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetch(PDO::FETCH_ASSOC);

        }
    }


	static public function mdlSumarGananciasPorPrestamo($tabla,$item,$valor){
		if($item == null){

			$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancia FROM $tabla WHERE id_prestamo = :id_prestamo");
			$stmt -> bindParam(":id_prestamo", $valor, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);	


		}else{
			$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancia FROM $tabla WHERE $item = :$item AND id_pago= 0");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetchAll(PDO::FETCH_ASSOC);
		}
	}
}