<?php


require_once "../../controladores/prestamos.controlador.php";
require_once "../../modelos/prestamos.modelo.php";

require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";

require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";

require_once "../../controladores/cobros.controlador.php";
require_once "../../modelos/cobros.modelo.php";

require_once "../../controladores/abonos.controlador.php";
require_once "../../modelos/abonos.modelo.php";

require_once "../../controladores/ganancias.controlador.php";
require_once "../../modelos/ganancia.modelo.php";

require_once "../../controladores/codeudores.controlador.php";
require_once "../../modelos/codeudores.modelo.php";

class imprimirFactura{

	public $codigo;

	public function traerImpresionFactura(){


		//TRAEMOS LA INFORMACIÓN DE LA VENTA

		$itemPrestamo = "id_prestamo";
		$valorPrestamo = $this->codigo;

		$respuestaPrestamo = ControladorPrestamos::ctrMostrarPrestamos($itemPrestamo, $valorPrestamo);

		$idCliente = $respuestaPrestamo["id_cliente"];
		$idCodeudor = $respuestaPrestamo["id_codeudor"];
		$idPrestador = $respuestaPrestamo["id_prestador"];
		$codigoPrestamo = $respuestaPrestamo["codigo_prestamo"];
		$monto = $respuestaPrestamo["monto"];
		$tasaInteres = $respuestaPrestamo["tasa_interes"];
		$fechaPrestamo = $respuestaPrestamo["fecha_prestamo"];
		$tiempoMeses = $respuestaPrestamo["tiempo_en_meses"];
		$formaPago = $respuestaPrestamo["forma_pago"];
		$estado = $respuestaPrestamo["estado_prestamo"];
		$saldoPendiente = $respuestaPrestamo["saldo_pendiente"];
		// ---------------------------------------------------------
		
		//TRAEMOS LA INFORMACIÓN DEL CLIENTE
		$itemCuotas = null;
		$valorCuota = null;;

		$respuestaCuotas = ControladorCobros::ctrMostrarCobros($itemCuotas,$valorCuota);
		// ---------------------------------------------------------


		$itemCliente = "id";
		$valorCliente = $idCliente;

		$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);
		// ---------------------------------------------------------

		//TRAEMOS LA INFORMACIÓN DEL PRESTADOR

		$itemVendedor = "id";
		$valorVendedor = $idPrestador;

		$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);
		

		$nombreImagen = "logocolor.png";
		$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));
		// ---------------------------------------------------------
		$itemAbono = null;
		$valorAbono = $this->codigo;
		$respuestaAbonos = ControladorAbonos::ctrMostrarAbonos($itemAbono,$valorAbono);

		// ---------------------------------------------------------
		$respuestaGanancia = ControladorGanancias::ctrSumarGananciasPorPrestamo($itemPrestamo,$valorPrestamo);
		$gananciaPrestamo= $respuestaGanancia[0]["ganancia"]; 
		// ---------------------------------------------------------
		//codeudor
		$respuestaCodeudor = ControladorCodeudores::ctrMostrarCodeudores($itemVendedor,$idCodeudor);


		// ---------------------------------------------------------
		$capitalPagado= $monto-$saldoPendiente;
		$interesPagado = 0;
		$cuotasPagadas = 0;
		$totalCuotas = 0;
		$cuotaApagar = 0;
		$diasMora = 0;
		// ---------------------------------------------------------


		//DIAS EN MORA
		// ---------------------------------------------------------
		
		$primeraFechaPendiente = null;
		
		
		foreach ($respuestaCuotas as $valor) {

			if($valor["id_prestamo"] == ($this->codigo)){

				if ($valor["estado"] == '1') {
					$primeraFechaPendiente =$valor["fecha_vencimiento"];
					break;
				}
			}
		}
		// $fechaPago = '2023-05-28';
		$fechaActual = new DateTime(); // Fecha actual

		$fechaPagoObj = DateTime::createFromFormat('Y-m-d', $primeraFechaPendiente); // Convertir la fecha de pago a un objeto DateTime

		if ($fechaActual > $fechaPagoObj) {
			$intervalo = $fechaActual->diff($fechaPagoObj);
			$diasMora = $intervalo->days;
		} else {
			$diasMora = 0;
		}

		// ---------------------------------------------------------
		

		foreach ($respuestaCuotas as $key => $value) {
		

			
			if($value["id_prestamo"] == ($this->codigo)){
				$totalCuotas = $totalCuotas +1;

				if(!$value["estado"]){
					// $capitalPagado = $capitalPagado + $value["capital_a_pagar"];
					
					$interesPagado = $interesPagado + $value["interes_a_pagar"];
					$cuotasPagadas = $cuotasPagadas + 1;
				}else{

					
					$cuotaApagar=$value["monto_cuota"];


				}
			}

			
		}
		if($respuestaGanancia != 0){
			$interesPagado += $gananciaPrestamo;
		}
		

		// Creamos el PDF

		 ?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">

			<!-- Font Awesome -->
  			<link rel="stylesheet" href="../../vistas/bower_components/font-awesome/css/font-awesome.min.css">

			<style>
				@import url('fonts/BrixSansRegular.css');
				@import url('fonts/BrixSansBlack.css');

				*{
					margin: 0;
					padding: 0;
					box-sizing: border-box;
				}
				p, label, span, table{
					font-family: 'BrixSansRegular';
					font-size: 9pt;
				}
				.h2{
					font-family: 'BrixSansBlack';
					font-size: 16pt;
				}
				.h3{
					font-family: 'BrixSansBlack';
					font-size: 12pt;
					display: block;
					background: #0a4661;
					color: #FFF;
					text-align: center;
					padding: 3px;
					margin-bottom: 5px;
				}
				#page_pdf{
					width: 95%;
					margin: 15px auto 10px auto;
				}

				#factura_head, #factura_cliente, #factura_detalle{
					width: 100%;
					margin-bottom: 10px;
				}
				.logo_factura{
					width: 25%;
					
				}
				/* .logo_factura .img{
					width: 25px;
					height: 25px;
					background-image: url(img/logocolor.png);
				} */
				.info_empresa{
					width: 50%;
					text-align: center;
				}
				.info_factura{
					width: 25%;
				}
				.info_cliente{
					width: 100%;
				}
				.datos_cliente{
					width: 100%;
				}
				.datos_cliente tr td{
					width: 50%;
				}
				.datos_cliente{
					padding: 10px 10px 0 10px;
				}
				.datos_cliente label{
					width: 75px;
					display: inline-block;
				}
				.datos_cliente p{
					display: inline-block;
				}

				.textright{
					text-align: right;
				}
				.textleft{
					text-align: left;
				}
				.textcenter{
					text-align: center;
				}
				.round{
					border-radius: 10px;
					border: 1px solid #0a4661;
					overflow: hidden;
					padding-bottom: 15px;
				}
				.round p{
					padding: 0 15px;
				}

				#factura_detalle{
					border-collapse: collapse;
				}
				#factura_detalle thead th{
					background: #058167;
					color: #FFF;
					padding: 5px;
				}
				#detalle_productos tr:nth-child(even) {
					background: #ededed;
				}
				#detalle_totales span{
					font-family: 'BrixSansBlack';
				}
				.nota{
					font-size: 8pt;
				}
				.label_gracias{
					font-family: verdana;
					font-weight: bold;
					font-style: italic;
					text-align: center;
					margin-top: 20px;
				}
				.anulada{
					position: absolute;
					left: 50%;
					top: 50%;
					transform: translateX(-50%) translateY(-50%);
				}
			</style>
		</head>
		<body>
		
		<div id="page_pdf">
			<table id="factura_head">
				<tr>
					<td class="logo_factura">
						<div>
							<!-- <img src="/vistas/img/plantilla/1.png" class=" im" />  -->
							
						</div>
						
					</td>
					<td class="info_empresa">
						
						<div>
							<span class="h2">INVERSIONES ANGELICA</span>
							<!-- <p>NIT: 1065885168-3</p>							 -->
							<p>Teléfono: 3162482360</p>
							<p>Email: yeyin-25@hotmail.com</p>
							<!-- <p></p> -->
							<p>Aguachica-Cesar</p>
							
							
						</div>
						
					</td>
					<td class="info_factura">
						<div class="round">
							<span class="h3">Crédito</span>
							<p>No. Crédito: <strong><?php echo $codigoPrestamo; ?></strong></p>
							<p>Fecha: <?php echo $fechaPrestamo; ?></p>
							
							<p>Vendedor: <?php echo $respuestaVendedor['nombre']; ?></p>
						</div>
					</td>
				</tr>
			</table>
			
			<table id="factura_cliente">
				<tr>
					<td class="info_cliente">
						<div class="round">
							<span class="h3">Información Cliente</span>
							<table class="datos_cliente">
								<tr>
									<td><label><strong>CC:</strong> </label><p><?php echo $respuestaCliente['documento']; ?></p></td>
									<td><label><strong>Teléfono:</strong> </label> <p><?php echo $respuestaCliente['telefono']; ?></p></td>
								</tr>
								<tr>
									<td><label><strong>Nombre:</strong> </label> <p><?php echo $respuestaCliente['nombre']; ?></p></td>
									<td><label><strong>Dirección:</strong> </label> <p><?php echo $respuestaCliente['direccion']; ?></p></td>
								</tr>
							</table>
						</div>
					</td>

				</tr>
			</table>
			<!-- informacion codeudor  -->
			<table id="factura_cliente">
				<tr>
					<td class="info_cliente">
						<div class="round">
							<span class="h3">Información Codeudor</span>
							<table class="datos_cliente">
								<tr>
									<td><label><strong>CC:</strong> </label><p><?php echo $respuestaCodeudor['documento']; ?></p></td>
									<td><label><strong>Teléfono:</strong> </label> <p><?php echo $respuestaCodeudor['telefono']; ?></p></td>
								</tr>
								<tr>
									<td><label><strong>Nombre:</strong> </label> <p><?php echo $respuestaCodeudor['nombre']; ?></p></td>
									<td><label><strong>Dirección:</strong> </label> <p><?php echo $respuestaCodeudor['direccion']; ?></p></td>
								</tr>
							</table>
						</div>
					</td>

				</tr>
			</table>
			<!-- informacion del credito  -->
			<table id="factura_cliente">
				<tr>
					<td class="info_cliente">
						<div class="round">
							<span class="h3">Información del Crédito</span>
							<table class="datos_cliente">
								<tr>
									<td><label><strong>Capital Prestado:</strong> </label><p>$<?php echo number_format($monto) ; ?></p></td>
									<td><label><strong>Capital Pagado:</strong> </label><p>$<?php echo number_format($capitalPagado) ; ?></p></td>
									<td><label><strong>Intereses Pagados:</strong> </label> <p><?php echo number_format($interesPagado); ?></p></td>
									<td><label><strong>Valor de la cuota:</strong> </label> <p>$<?php echo number_format($cuotaApagar); ?></p></td>
								</tr>
								<tr>
									<td><label><strong>Capital Pendiente:</strong> </label><p>$<?php echo number_format($saldoPendiente) ; ?></p></td>

									<td><label><strong>Cuotas Pagadas:</strong> </label> <p><?php echo $cuotasPagadas; ?> de <?php echo $totalCuotas; ?></p></td>
									<td><label><strong>Tasa de interés:</strong></label> <p><?php echo $tasaInteres; ?>% Mes</p></td>
									<td><label><strong>Mora:</strong> </label> <p><?php echo $diasMora; ?> Dias</p></td>
								</tr>
							</table>
						</div>
					</td>

				</tr>
			</table>
			<div class="informacion_pagos text-center">
				<i class="fa fa-info-circle"></i>
				<h3>Información de Pagos</h3>
			</div>
			<table id="factura_detalle">
					<thead>
						<tr>
							<th width="50px">No. Cuota</th>
							<th width="textleft">Cuota</th>
							<th class="textleft">Fecha</th>
							<th class="textright" width="150px">Capital</th>
							<th class="textright" width="150px"> Intereses</th>
							<th class="textright" width="150px"> Estado Cuota</th>
						</tr>
					</thead>
					<tbody id="detalle_productos">

					<?php
					// var_dump($respuestaCuotas);
						foreach ($respuestaCuotas as $key => $item) {

							if($item["id_prestamo"] == ($this->codigo)){

								if($item["estado"]){
									$estado = "Pendiente";
									
								}else{
									$estado = "Pagado";
								}

							
					?>
						<tr>
							<td class="textcenter"><?php echo $item['num_cuota']; ?></td>
							<td class="textcenter"><?php echo number_format($item['monto_cuota']); ?></td>
							<td><?php echo $item['fecha_vencimiento']; ?></td>
							<td class="textright"><?php echo number_format($item["capital_a_pagar"]); ?></td>
							<td class="textright"><?php echo number_format($item["interes_a_pagar"]); ?></td>

							<td class="textright"><?php echo $estado; ?></td>
						</tr>
					<?php
							}
							
						}

						

					?>
					</tbody>

					<tfoot id="detalle_totales">
						
					</tfoot>
			</table>
			<!-- tabla abonos -->
			<div class="informacion_pagos ">
				<i class="fa fa-info-circle"></i>
				<h3>Información de Abonos</h3>
			</div>
			<table id="factura_detalle">
					<thead>
						<tr>
							<th width="50px">No. Abono</th>
							<th width="textleft">Monto Abono</th>
							<th class="textleft">Fecha</th>
						</tr>
					</thead>
					<tbody id="detalle_productos">

					<?php
					// var_dump($respuestaCuotas);
					$num_Abono = 0;
						foreach ($respuestaAbonos as $key => $item) {

							if($item["id_prestamo"] == ($this->codigo)){
								$num_Abono = $num_Abono+1;

							
					?>
						<tr>
							<td class="textcenter"><?php echo $num_Abono; ?></td>
							<td class="textcenter"><?php echo number_format($item['cantidad_abonada']); ?></td>
							<td><?php echo $item['fecha']; ?></td>
						</tr>
					<?php
							}
							
						}

						

					?>
					</tbody>

					<tfoot id="detalle_totales">
						
					</tfoot>
			</table>
			<div >
				<p class="nota">Si usted tiene preguntas sobre este crédito, <br>pongase en contacto con nombre, teléfono y Email con la empresa</p>
				<h4 class="label_gracias">¡Gracias por preferirnos!</h4>
			</div>

		</div>

		</body>
		</html>
		<?php
	

	}
}


$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();
 