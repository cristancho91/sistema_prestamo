<?php

require_once "../controladores/codeudores.controlador.php";
require_once "../modelos/codeudores.modelo.php";

class AjaxCodeudores{

	/*=============================================
	EDITAR CODEUDOR
	=============================================*/	

	public $idCodeudor;

	public function ajaxEditarCodeudor(){

		$item = "id";
		$valor = $this->idCodeudor;

		$respuesta = ControladorCodeudores::ctrMostrarCodeudores($item, $valor);

		echo json_encode($respuesta);


	}

}

/*=============================================
EDITAR CLIENTE
=============================================*/	

if(isset($_POST["idCodeudor"])){

	$cliente = new AjaxCodeudores();
	$cliente -> idCodeudor = $_POST["idCodeudor"];
	$cliente -> ajaxEditarCodeudor();

}