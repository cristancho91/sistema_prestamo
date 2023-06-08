<?php

class ControladorPagos{

    /*=============================================
	RANGO FECHAS PAGOS
	=============================================*/	

	static public function ctrRangoFechasPagos($fechaInicial, $fechaFinal){

		$tabla = "pagos";

		$respuesta = ModeloPagos::mdlRangoFechasPagos($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}
}