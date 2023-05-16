<?php

require_once "../controladores/cobros.controlador.php";
require_once "../modelos/cobros.modelo.php";

// require_once "../controladores/abonos.controlador.php";
// require_once "../modelos/abonos.modelo.php";
require_once "../controladores/prestamos.controlador.php";
require_once "../modelos/prestamos.modelo.php";
require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

class AjaxCobros{


  /*=============================================
  pagar CUOtas
  =============================================*/ 

  public $idCuota;
  public $idPrestamo;
  public $idCliente;

  public function ajaxEditarCobro(){

    

      $item = "id_cuota";
      $valor = $this->idCuota;

      $respuesta = ControladorCobros::ctrMostrarCobros($item, $valor);

      echo json_encode($respuesta);

  }

  /*=============================================
  TRAER EL PRESTAMO
  =============================================*/ 
  public function ajaxTraerPrestamo(){

    $item = "id_prestamo";
    $valor = $this->idPrestamo;
    $respuesta = ControladorPrestamos::ctrMostrarPrestamos($item, $valor);

    echo json_encode($respuesta);
  }

  /*=============================================
  TRAER CLIENTE
  =============================================*/ 

  public function ajaxTraerCliente(){

    $item = "id";
    $valor = $this->idCliente;
    $respuesta = ControladorClientes::ctrMostrarClientes($item, $valor);

    echo json_encode($respuesta);
  }



}


/*=============================================
EDITAR PRODUCTO
=============================================*/ 

if(isset($_POST["idCuota"])){

  $editarCobro = new AjaxCobros();
  $editarCobro -> idCuota = $_POST["idCuota"];
  $editarCobro -> ajaxEditarCobro();

}

/*=============================================
TRAER PRESTAMO
=============================================*/
if(isset($_POST["idPrestamo"])){
  $mostrarPrestamo = new AjaxCobros();
  $mostrarPrestamo ->idPrestamo = $_POST["idPrestamo"];
  $mostrarPrestamo -> ajaxTraerPrestamo();
}

/*=============================================
TRAER CLIENTE
=============================================*/ 
if(isset($_POST["idCliente"])){
  $mostrarCliente = new AjaxCobros();
  $mostrarCliente -> idCliente = $_POST["idCliente"];
  $mostrarCliente -> ajaxTraerCliente();
}


/*=============================================
TRAER PRODUCTO
=============================================*/ 

// if(isset($_POST["traerProductos"])){

//   $traerProductos = new AjaxProductos();
//   $traerProductos -> traerProductos = $_POST["traerProductos"];
//   $traerProductos -> ajaxEditarProducto();

// }






