<?php
require '../../vendor/autoload.php';



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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_GET["reporte"])){

    

    if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

        $prestamos = ControladorPrestamos::ctrRangoFechasPrestamos($_GET["fechaInicial"], $_GET["fechaFinal"]);
        $cuotas = ControladorCobros::ctrRangoFechasCobros($_GET["fechaInicial"], $_GET["fechaFinal"]);
        // $abonos = ControladorAbonos::ctrMostrarAbonos($_GET["fechaInicial"], $_GET["fechaFinal"]);

    }else{

        $item = null;
        $valor = null;

        $prestamos = ControladorPrestamos::ctrRangoFechasPrestamos($item, $valor);
        $cuotas = ControladorCobros::ctrRangoFechasCobros($item, $valor);



    }



    // Crear una instancia de Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Obtener la hoja activa
    $hoja = $spreadsheet->getActiveSheet();
    $hoja->setTitle('reporte prestamos');

    // Escribir datos en la hoja
    $hoja->getColumnDimension('A')->setWidth(30);
    $hoja->setCellValue('A1', 'NOMBRE CLIENTE');

    $hoja->getColumnDimension('B')->setWidth(30);
    $hoja->setCellValue('B1', 'NOMBRE CODEUDOR');

    $hoja->getColumnDimension('C')->setWidth(20);
    $hoja->setCellValue('C1', 'CÓDIGO PRESTAMO');

    $hoja->getColumnDimension('D')->setWidth(20);
    $hoja->setCellValue('D1', 'MONTO PRESTAMO');

    $hoja->getColumnDimension('E')->setWidth(15);
    $hoja->setCellValue('E1', 'TASA DE INTERÉS');

    $hoja->getColumnDimension('F')->setWidth(15);
    $hoja->setCellValue('F1', 'FECHA DEL PRESTAMO');

    $hoja->getColumnDimension('G')->setWidth(10);
    $hoja->setCellValue('G1', 'TIEMPO DEL PRESTAMO');

    $hoja->getColumnDimension('H')->setWidth(20);
    $hoja->setCellValue('H1', 'FORMA DE PAGO');

    $hoja->getColumnDimension('I')->setWidth(20);
    $hoja->setCellValue('I1', 'SALDO PENDIENTE');

    $hoja->getColumnDimension('J')->setWidth(20);
    $hoja->setCellValue('J1', 'ESTADO PRESTAMO');

    $fila = 2;
    foreach ($prestamos as $key => $value) {
    $hoja->setCellValue('A'.$fila, $value["nombre_cliente"]);
    $hoja->setCellValue('B'.$fila, $value["nombre_codeudor"]);
    $hoja->setCellValue('C'.$fila, $value["codigo_prestamo"]);
    $hoja->setCellValue('D'.$fila, $value["monto"]);
    $hoja->setCellValue('E'.$fila, $value["tasa_interes"]);
    $hoja->setCellValue('F'.$fila, $value["fecha_prestamo"]);
    $hoja->setCellValue('G'.$fila, $value["tiempo_en_meses"]);
    $hoja->setCellValue('H'.$fila, $value["forma_pago"]);
    $hoja->setCellValue('I'.$fila, $value["saldo_pendiente"]);
    if($value["estado_prestamo"] == 1){
        $hoja->setCellValue('J'.$fila, "PENDIENTE");
    }else{
        $hoja->setCellValue('J'.$fila, "PAGADO");
    }
    
    $fila++;

        
    }



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

}