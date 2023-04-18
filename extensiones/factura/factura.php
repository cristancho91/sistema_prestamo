<?php

require_once "../../controladores/ventas.controlador.php";
require_once "../../modelos/ventas.modelo.php";

require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";

require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";

require_once "../../controladores/productos.controlador.php";
require_once "../../modelos/productos.modelo.php";

class imprimirFactura{

	public $codigo;

	public function traerImpresionFactura(){

		
		$impuesto 	= 0;
		$total 		= 0;

		//TRAEMOS LA INFORMACIÓN DE LA VENTA

		$itemVenta = "codigo";
		$valorVenta = $this->codigo;

		$respuestaVenta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);

		$fecha = substr($respuestaVenta["fecha"],0,-8);
		$hora = substr($respuestaVenta["fecha"],10);
		$productos = json_decode($respuestaVenta["productos"], true);
		$neto = number_format($respuestaVenta["neto"],2);
		$impuesto = number_format($respuestaVenta["impuesto"],2);
		$total = number_format($respuestaVenta["total"],2);
		$iva = number_format((($respuestaVenta["impuesto"]*100)/$respuestaVenta["total"]),2);
		
		//TRAEMOS LA INFORMACIÓN DEL CLIENTE

		$itemCliente = "id";
		$valorCliente = $respuestaVenta["id_cliente"];

		$respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

		//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

		$itemVendedor = "id";
		$valorVendedor = $respuestaVenta["id_vendedor"];

		$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

		$nombreImagen = "logocolor.png";
		$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($nombreImagen));
		// ---------------------------------------------------------

		//print_r($configuracion); ?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">

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
							<!-- <img src="/extensiones/factura/img/logocolor.png" />  -->
						</div>
						
					</td>
					<td class="info_empresa">
						
						<div>
							<span class="h2">CRISTANCHOLICORES</span>
							<p>NIT: 1065885168-3</p>							
							<p>Teléfono: 3187151351</p>
							<p>Email: jhoncristancho08@gmail.com</p>
							<p>Manzana H casa 4 tierra linda</p>
							<p>Aguachica-Cesar</p>
							
							
						</div>
						
					</td>
					<td class="info_factura">
						<div class="round">
							<span class="h3">Factura</span>
							<p>No. Factura: <strong><?php echo $valorVenta; ?></strong></p>
							<p>Fecha: <?php echo $fecha; ?></p>
							<p>Hora: <?php echo $hora; ?></p>
							<p>Vendedor: <?php echo $respuestaVendedor['nombre']; ?></p>
						</div>
					</td>
				</tr>
			</table>
			<table id="factura_cliente">
				<tr>
					<td class="info_cliente">
						<div class="round">
							<span class="h3">Cliente</span>
							<table class="datos_cliente">
								<tr>
									<td><label>CC:</label><p><?php echo $respuestaCliente['documento']; ?></p></td>
									<td><label>Teléfono:</label> <p><?php echo $respuestaCliente['telefono']; ?></p></td>
								</tr>
								<tr>
									<td><label>Nombre:</label> <p><?php echo $respuestaCliente['nombre']; ?></p></td>
									<td><label>Dirección:</label> <p><?php echo $respuestaCliente['direccion']; ?></p></td>
								</tr>
							</table>
						</div>
					</td>

				</tr>
			</table>

			<table id="factura_detalle">
					<thead>
						<tr>
							<th width="50px">Cant.</th>
							<th class="textleft">Descripción</th>
							<th class="textright" width="150px">Precio Unitario.</th>
							<th class="textright" width="150px"> Precio Total</th>
						</tr>
					</thead>
					<tbody id="detalle_productos">

					<?php
						foreach ($productos as $key => $item) {

							$itemProducto = "descripcion";
							$valorProducto = $item["descripcion"];
							$orden = null;
					
							$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);
					
							$valorUnitario = number_format($respuestaProducto["precio_venta"], 2);
					
							$precioTotal = number_format($item["total"], 2);
					?>
						<tr>
							<td class="textcenter"><?php echo $item['cantidad']; ?></td>
							<td><?php echo $item['descripcion']; ?></td>
							<td class="textright"><?php echo $valorUnitario; ?></td>
							<td class="textright"><?php echo $precioTotal; ?></td>
						</tr>
					<?php
							
							
						}

					?>
					</tbody>
					<tfoot id="detalle_totales">
						<tr>
							<td colspan="3" class="textright"><span>SUBTOTAL Q.</span></td>
							<td class="textright"><span><?php echo $neto; ?></span></td>
						</tr>
						<tr>
							<td colspan="3" class="textright"><span>IVA (<?php echo $iva; ?> %)</span></td>
							<td class="textright"><span><?php echo $impuesto; ?></span></td>
						</tr>
						<tr>
							<td colspan="3" class="textright"><span>TOTAL Q.</span></td>
							<td class="textright"><span><?php echo $total; ?></span></td>
						</tr>
				</tfoot>
			</table>
			<div>
				<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p>
				<h4 class="label_gracias">¡Gracias por su compra!</h4>
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
 