<?php

require_once "conexion.php";

class ModeloAbonos{

	/*=============================================
	CREAR Abono
	=============================================*/

	static public function mdlCrearAbono($tabla, $datos){
		
		$pdo = Conexion::conectar();
		$pdo->beginTransaction();

		try {

			$stmt = $pdo->prepare("INSERT INTO $tabla(id_prestamo,id_cuota,cantidad_abonada) VALUES (:id_prestamo,:id_cuota,:cantidad_abonada)");

			$stmt->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
			$stmt->bindParam(":id_cuota", $datos["idCuota"], PDO::PARAM_INT);
			$stmt->bindParam(":cantidad_abonada", $datos["montoAbono"], PDO::PARAM_INT);

			$stmt->execute();

			// Obtener el ID del Abono recién creado
			$id_abono = $pdo->lastInsertId();

			//obtenemos la cantidad de cuotas a editar
			$stmt2 = $pdo->prepare("SELECT COUNT(estado) AS contar_cuotas FROM cuotas WHERE id_prestamo = :id_prestamo AND estado = 1");

			$stmt2->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);

			$stmt2->execute();

			$cuotas = $stmt2->fetch(PDO::FETCH_ASSOC);


			$cantidad_cuotas= $cuotas["contar_cuotas"];

			//obtenemos los numeros de las cuotas
			$stmt4 = $pdo->prepare("SELECT num_cuota FROM cuotas WHERE id_prestamo = :id_prestamo AND estado = 1");

			$stmt4->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);

			$stmt4->execute();

			$numcuotas = $stmt4->fetchAll(PDO::FETCH_ASSOC);


			// Obtenemos el método de pago para el nuevo préstamo
			$forma_pago = $datos["formaPago"];

			$fecha_vencimiento = $datos["fechaCobro"];

			// Calculamos la cantidad de cuotas a crear según el método de pago
			$tipoPago = "";

			switch ($forma_pago) {
				case 'diario':
					$tipoPago = "day";
					break;
				case 'semanal':
					$tipoPago = "week";
					break;
				case 'quincenal':
					$tipoPago = "week";
					break;
				case 'mensual':
					$tipoPago = "month";
					break;
			}


			// Calcular el monto de la cuota (capital + interés)
			$capital = $datos["capitalPendiente"] ;	
						
			
			$interes = $datos["tasaInteres"] / 100;
			
			$cuota = $capital * ($interes / (1 - pow(1 + $interes, -$cantidad_cuotas)));
			// $cuota = ($capital/$cantidad_cuotas)+ ($capital*$interes);


			//verificamos si solo pago interes o interes y algo de capital

			if($datos["montoAbono"] > $datos["interesApagar"]){				

				//editamos las cuotas
				$stmt3 = $pdo->prepare("UPDATE cuotas SET monto_cuota = :monto_cuota, interes_a_pagar= :interes_a_pagar, capital_a_pagar= :capital_a_pagar, cantidad_pendiente = :cantidad_pendiente, fecha_vencimiento= :fecha_vencimiento WHERE id_prestamo = :id_prestamo AND estado = 1 AND num_cuota = :num_cuota");
				

				$quincena = 0;

				for ($i = 1; $i <= $cantidad_cuotas; $i++) {

					$indexCuota = $i-1;
					
					
					// Calcular el interés y capital a pagar para esta cuota
					$interes_a_pagar = $capital * $interes;
					$capital_a_pagar = $cuota - $interes_a_pagar;
					$cantidad_pendiente = $capital - $capital_a_pagar;

					// MODIFICAR LA CUOTA
					$stmt3->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
					$stmt3->bindParam(":num_cuota", $numcuotas[$indexCuota]["num_cuota"], PDO::PARAM_INT);
					$stmt3->bindParam(":monto_cuota", $cuota, PDO::PARAM_INT);
					$stmt3->bindParam(":interes_a_pagar", $interes_a_pagar, PDO::PARAM_INT);
					$stmt3->bindParam(":capital_a_pagar", $capital_a_pagar, PDO::PARAM_INT);
					$stmt3->bindParam(":cantidad_pendiente", $cantidad_pendiente, PDO::PARAM_INT);

					if($forma_pago == "quincenal"){
						$quincena +=2 ;
						
						$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $quincena $tipoPago"));
					}else{
						$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $i $tipoPago"));
					}
					
					$stmt3->bindParam(":fecha_vencimiento", $fecha_vencimiento, PDO::PARAM_STR);

					$stmt3->execute();

					// Actualizar el saldo pendiente
					$capital= $cantidad_pendiente;
				}
	

				//actualizamos el capital pendiente
				$stmt6 = $pdo->prepare("UPDATE prestamos SET saldo_pendiente = :capital_pendiente WHERE id_prestamo = :id_prestamo");

				$stmt6->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
				$stmt6->bindParam(":capital_pendiente", $datos["capitalPendiente"], PDO::PARAM_INT);

				$stmt6->execute();

				
			
			}else if($datos["montoAbono"] > 0 && $datos["montoAbono"] <= $datos["interesApagar"]){

				if($datos["montoAbono"] < $datos["interesApagar"]){

					

					//si el interes a abonar es menor que el que debe pagar sumamos el restante a la primera cuota a recalcular
					$stmt3 = $pdo->prepare("UPDATE cuotas SET interes_a_pagar= :interes_a_pagar, fecha_vencimiento= :fecha_vencimiento WHERE id_prestamo = :id_prestamo AND estado = 1 AND num_cuota = :num_cuota");

					$interesPendiente =$datos["interesApagar"] +( $datos["interesApagar"] - $datos["montoAbono"]) ;
					

					$quincena = 0;

					for ($i = 1; $i <= $cantidad_cuotas; $i++) {

						$indexCuota = $i-1;

						$interes_a_pagar = $capital * $interes;
						$capital_a_pagar = $cuota - $interes_a_pagar;
						$cantidad_pendiente = $capital - $capital_a_pagar;
						
						// MODIFICAR LA CUOTA
						$stmt3->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
						$stmt3->bindParam(":num_cuota", $numcuotas[$indexCuota]["num_cuota"], PDO::PARAM_INT);

						if($i == 1){
							$stmt3->bindParam(":interes_a_pagar",$interesPendiente , PDO::PARAM_INT);
						}else{
							$stmt3->bindParam(":interes_a_pagar", $interes_a_pagar, PDO::PARAM_INT);
						}
						

						if($forma_pago == "quincenal"){
							$quincena +=2 ;
							
							$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $quincena $tipoPago"));
						}else{
							$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $i $tipoPago"));
						}
						
						$stmt3->bindParam(":fecha_vencimiento", $fecha_vencimiento, PDO::PARAM_STR);

						$stmt3->execute();
						// Actualizar el saldo pendiente
						$capital= $cantidad_pendiente;
					}

				}else{

					//si el interes a abononar es igual que el que debe pagar editamos solo modificamos la fecha
					$stmt3 = $pdo->prepare("UPDATE cuotas SET fecha_vencimiento= :fecha_vencimiento WHERE id_prestamo = :id_prestamo AND estado = 1 AND num_cuota = :num_cuota");
					

					$quincena = 0;

					for ($i = 1; $i <= $cantidad_cuotas; $i++) {

						$indexCuota = $i-1;
						
						// MODIFICAR LA CUOTA
						$stmt3->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
						$stmt3->bindParam(":num_cuota", $numcuotas[$indexCuota]["num_cuota"], PDO::PARAM_INT);

						if($forma_pago == "quincenal"){
							$quincena +=2 ;
							
							$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $quincena $tipoPago"));
						}else{
							$fecha_vencimiento = date('Y-m-d', strtotime($datos["fechaCobro"] . "+ $i $tipoPago"));
						}
						
						$stmt3->bindParam(":fecha_vencimiento", $fecha_vencimiento, PDO::PARAM_STR);

						$stmt3->execute();
					}
				}

				

			}

			//guardamos la ganancia
			$stmt5 = $pdo->prepare("INSERT INTO ganancia(id_prestamo, ganancia) VALUES (:id_prestamo, :ganancia)");

			$stmt5->bindParam(":id_prestamo", $datos["idPrestamo"], PDO::PARAM_INT);
			$stmt5->bindParam(":ganancia", $datos["ganancia"], PDO::PARAM_INT);

			$stmt5->execute();



			// Confirmar la transacción
			$pdo->commit();

			return "ok";

			
			$stmt = null;

		} catch (PDOException $e) {
					
			$pdo->rollBack();
			return "error".$e->getMessage();
		}

	}

	/*=============================================
	MOSTRAR ABONOS
	=============================================*/

	static public function mdlMostrarAbonos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

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
	MOSTRAR ABONOS POR FECHA
	=============================================*/

	static public function mdlRangoFechasAbonos($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
			INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id
			INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota  ORDER BY fecha ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();	


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
			INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
			INNER JOIN clientes c ON p.id_cliente = c.id
			INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha like '%$fechaFinal%'");

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
				INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT a.*,p.*,c.nombre AS nombre_cliente,cu.num_cuota  FROM $tabla a
				INNER JOIN prestamos p ON a.id_prestamo = p.id_prestamo 
				INNER JOIN clientes c ON p.id_cliente = c.id
				INNER JOIN cuotas cu ON a.id_cuota = cu.id_cuota WHERE fecha BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}
		
			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

	/*=============================================
	EDITAR CATEGORIA
	=============================================*/

	static public function mdlEditarCategoria($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET categoria = :categoria WHERE id = :id");

		$stmt -> bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}
		$stmt = null;

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	static public function mdlBorrarCategoria($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt = null;

	}

}

