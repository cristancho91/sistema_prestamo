<?php
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "../../controladores/prestamos.controlador.php";
require_once "../../modelos/prestamos.modelo.php";
require_once "../../controladores/clientes.controlador.php";
require_once "../../modelos/clientes.modelo.php";
require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";
require_once "../../controladores/abonos.controlador.php";
require_once "../../modelos/abonos.modelo.php";
require_once "../../controladores/cobros.controlador.php";
require_once "../../modelos/cobros.modelo.php";
require_once "../../controladores/ganancias.controlador.php";
require_once "../../modelos/ganancia.modelo.php";



// Crear una instancia de Spreadsheet
$spreadsheet = new Spreadsheet();

// Obtener la hoja activa
$hoja = $spreadsheet->getActiveSheet();

// Escribir datos en la hoja
$hoja->setCellValue('A1', 'Hola');
$hoja->setCellValue('B1', 'Mundo');

// Guardar el archivo en el servidor o descargarlo al navegador
$writer = new Xlsx($spreadsheet);

$fecha = date('Y-m-d'); // Obtener la fecha actual en formato AñoMesDía (por ejemplo, 20230528)
$nombreArchivo = $_GET["reporte"] . '_' . $fecha . '.xlsx'; // Concatenar la fecha a la variable $Name // Nombre del archivo de salida

$writer->save($nombreArchivo);

// Descargar el archivo al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Cache-Control: max-age=0');

// Leer el archivo y enviarlo al navegador
readfile($nombreArchivo);

// Eliminar el archivo del servidor
unlink($nombreArchivo);
