<?php

require_once "conexion.php";

class ModeloPagos{

    //mostrar los pagos por rangos de pagos
    static public function mdlRangoFechasPagos($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  
            FROM $tabla a
			INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id
			INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota  ORDER BY fecha_pago ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
			INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id
			INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha_pago like '%$fechaFinal%'");

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

				$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
				INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
				INNER JOIN clientes c ON p.id_cliente = c.id
				INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha_pago BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
				INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
				INNER JOIN clientes c ON p.id_cliente = c.id
				INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha_pago BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}
}