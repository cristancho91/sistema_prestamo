<?php

require_once "conexion.php";

class ModeloGanancia{
    /*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function mdlRangoFechasGanancia($tabla, $fechaInicial, $fechaFinal){

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
    static public function mdlSumaGananciaFecha($tabla, $fechaInicial, $fechaFinal){

        if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT $tabla.*, clientes.nombre AS nombre_cliente, usuarios.nombre AS nombre_usuario FROM $tabla JOIN clientes ON prestamos.id_cliente = clientes.id JOIN usuarios ON prestamos.id_prestador = usuarios.id  ORDER BY id_prestamo ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT SUM(ganancia) AS ganancias FROM `ganancia` WHERE fecha_ganancia LIKE '%$fechaFinal%'");

			$stmt -> execute();

			return $stmt -> fetch();

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
}