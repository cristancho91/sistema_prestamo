<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/abonos.controlador.php";
require_once "controladores/cobros.controlador.php";
require_once "controladores/clientes.controlador.php";
require_once "controladores/prestamos.controlador.php";
require_once "controladores/ganancias.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/abonos.modelo.php";
require_once "modelos/cobros.modelo.php";
require_once "modelos/clientes.modelo.php";
require_once "modelos/prestamos.modelo.php";
require_once "modelos/ganancia.modelo.php";

$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();