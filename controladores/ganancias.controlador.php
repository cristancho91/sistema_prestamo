<?php

class ControladorGanancias{

    /*=============================================
	RANGO FECHAS
	=============================================*/	

	static public function ctrRangoFechasganancia($fechaInicial, $fechaFinal){

		$tabla = "ganancia";

		$respuesta = ModeloGanancia::mdlRangoFechasGanancia($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;
		
	}

    /*=============================================
	SUMAR LAS GANANCIAS DEL MES
	=============================================*/	
    static public function ctrSumaGananciaFecha($fechaInicial, $fechaFinal){

        $tabla="ganancia";
        $respuesta = ModeloGanancia::mdlSumaGananciaFecha($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;

    }
}