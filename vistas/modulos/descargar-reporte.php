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
require_once "../../controladores/pagos.controlador.php";
require_once "../../modelos/pagos.modelo.php";
require_once "../../controladores/codeudores.controlador.php";
require_once "../../modelos/codeudores.modelo.php";


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_GET["reporte"])){

    

    if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

        $prestamos = ControladorPrestamos::ctrRangoFechasPrestamos($_GET["fechaInicial"], $_GET["fechaFinal"]);
        $cuotas = ControladorCobros::ctrRangoFechasCobros($_GET["fechaInicial"], $_GET["fechaFinal"]);
        $abonos = ControladorAbonos::ctrRangoFechasAbonos($_GET["fechaInicial"], $_GET["fechaFinal"]);
        $pagos = ControladorPagos::ctrRangoFechasPagos($_GET["fechaInicial"], $_GET["fechaFinal"]);
        $ganancia = ControladorGanancias::ctrRangoFechasganancia($_GET["fechaInicial"], $_GET["fechaFinal"]);

    }else{

        $item = null;
        $valor = null;

        $prestamos = ControladorPrestamos::ctrRangoFechasPrestamos($item, $valor);
        $cuotas = ControladorCobros::ctrRangoFechasCobros($item, $valor);
        $abonos = ControladorAbonos::ctrRangoFechasAbonos($item,$valor);
        $pagos = ControladorPagos::ctrRangoFechasPagos($item,$valor);
        $ganancia = ControladorGanancias::ctrRangoFechasganancia($item,$valor);

    }
    $clientes = ControladorClientes::ctrMostrarClientes(null,null);
    $codeudores = ControladorCodeudores::ctrMostrarCodeudores(null,null);



    // Crear una instancia de Spreadsheet
    $spreadsheet = new Spreadsheet();

    // Obtener la hoja activa
    $hoja = $spreadsheet->getActiveSheet();
    $hoja->setTitle('REPORTE PRESTAMOS');

    // Escribir datos en la hoja
    $hoja->getColumnDimension('A')->setWidth(10);
    $hoja->setCellValue('A1', '#');

    $hoja->getColumnDimension('B')->setWidth(30);
    $hoja->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja->getColumnDimension('C')->setWidth(30);
    $hoja->setCellValue('C1', 'NOMBRE CODEUDOR');

    $hoja->getColumnDimension('D')->setWidth(20);
    $hoja->setCellValue('D1', 'CÓDIGO PRESTAMO');

    $hoja->getColumnDimension('E')->setWidth(20);
    $hoja->setCellValue('E1', 'MONTO PRESTAMO');

    $hoja->getColumnDimension('F')->setWidth(15);
    $hoja->setCellValue('F1', 'TASA DE INTERÉS');

    $hoja->getColumnDimension('G')->setWidth(15);
    $hoja->setCellValue('G1', 'FECHA DEL PRESTAMO');

    $hoja->getColumnDimension('H')->setWidth(10);
    $hoja->setCellValue('H1', 'TIEMPO DEL PRESTAMO');

    $hoja->getColumnDimension('I')->setWidth(20);
    $hoja->setCellValue('I1', 'FORMA DE PAGO');

    $hoja->getColumnDimension('J')->setWidth(20);
    $hoja->setCellValue('J1', 'SALDO PENDIENTE');

    $hoja->getColumnDimension('K')->setWidth(20);
    $hoja->setCellValue('K1', 'ESTADO PRESTAMO');

    $fila = 2;
    foreach ($prestamos as $key => $value) {
    $hoja->setCellValue('A'.$fila, ($key+1));
    $hoja->setCellValue('B'.$fila, $value["nombre_cliente"]);
    $hoja->setCellValue('C'.$fila, $value["nombre_codeudor"]);
    $hoja->setCellValue('D'.$fila, $value["codigo_prestamo"]);
    $hoja->setCellValue('E'.$fila, $value["monto"]);
    $hoja->setCellValue('F'.$fila, $value["tasa_interes"]);
    $hoja->setCellValue('G'.$fila, $value["fecha_prestamo"]);
    $hoja->setCellValue('H'.$fila, $value["tiempo_en_meses"]);
    $hoja->setCellValue('I'.$fila, $value["forma_pago"]);
    $hoja->setCellValue('J'.$fila, $value["saldo_pendiente"]);
    if($value["estado_prestamo"] == 1){
        $hoja->setCellValue('K'.$fila, "PENDIENTE");
    }else{
        $hoja->setCellValue('K'.$fila, "PAGADO");
    }
    
    $fila++;        
    }

    // Obtener la hoja2 activa
    $hoja2 = $spreadsheet->createSheet();
    $hoja2->setTitle('REPORTE CUOTAS');

    // Escribir datos en la hoja2
    $hoja2->getColumnDimension('A')->setWidth(10);
    $hoja2->setCellValue('A1', 'N° CUOTA');

    $hoja2->getColumnDimension('B')->setWidth(30);
    $hoja2->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja2->getColumnDimension('C')->setWidth(30);
    $hoja2->setCellValue('C1', 'CUOTA A PAGAR');

    $hoja2->getColumnDimension('D')->setWidth(20);
    $hoja2->setCellValue('D1', 'INTERÉS A PAGAR');

    $hoja2->getColumnDimension('E')->setWidth(20);
    $hoja2->setCellValue('E1', 'CAPITAL A PAGAR');

    $hoja2->getColumnDimension('F')->setWidth(25);
    $hoja2->setCellValue('F1', 'CAPITAL PENDIENTE');

    $hoja2->getColumnDimension('G')->setWidth(15);
    $hoja2->setCellValue('G1', 'ESTADO');

    $hoja2->getColumnDimension('H')->setWidth(20);
    $hoja2->setCellValue('H1', 'FECHA DE COBRO');

    $fila = 2;
    foreach ($cuotas as $key => $value) {
    $hoja2->setCellValue('A'.$fila, $value["num_cuota"]);
    $hoja2->setCellValue('B'.$fila, $value["nombre_cliente"]);
    $hoja2->setCellValue('C'.$fila, $value["monto_cuota"]);
    $hoja2->setCellValue('D'.$fila, $value["interes_a_pagar"]);
    $hoja2->setCellValue('E'.$fila, $value["capital_a_pagar"]);
    $hoja2->setCellValue('F'.$fila, $value["cantidad_pendiente"]);

    if($value["estado"] == 1){
        $hoja2->setCellValue('G'.$fila, "PENDIENTE");
    }else{
        $hoja2->setCellValue('G'.$fila, "PAGADO");
    }

    $hoja2->setCellValue('H'.$fila, $value["fecha_vencimiento"]);
    
    
    $fila++;        
    }


    // Obtener la hoja3 activa
    $hoja3 = $spreadsheet->createSheet();
    $hoja3->setTitle('REPORTE ABONOS');

    // Escribir datos en la hoja3
    $hoja3->getColumnDimension('A')->setWidth(10);
    $hoja3->setCellValue('A1', 'N° ABONO');

    $hoja3->getColumnDimension('B')->setWidth(30);
    $hoja3->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja3->getColumnDimension('C')->setWidth(15);
    $hoja3->setCellValue('C1', 'NUMERO CUOTA');

    $hoja3->getColumnDimension('D')->setWidth(20);
    $hoja3->setCellValue('D1', 'CANTIDAD ABONADA');

    $hoja3->getColumnDimension('E')->setWidth(20);
    $hoja3->setCellValue('E1', 'FECHA');

    $fila = 2;
    foreach ($abonos as $key => $value) {
    $fecha1 = substr($value["fecha"],0,10);
    $hoja3->setCellValue('A'.$fila, ($key+1));
    $hoja3->setCellValue('B'.$fila, $value["nombre_cliente"]);
    $hoja3->setCellValue('C'.$fila, $value["num_cuota"]);
    $hoja3->setCellValue('D'.$fila, $value["cantidad_abonada"]);
    $hoja3->setCellValue('E'.$fila, $fecha1);    
    
    $fila++;        
    }

    // Obtener la hoja4 activa
    $hoja4 = $spreadsheet->createSheet();
    $hoja4->setTitle('REPORTE DE PAGOS');

    // Escribir datos en la hoja4
    $hoja4->getColumnDimension('A')->setWidth(10);
    $hoja4->setCellValue('A1', 'N° PAGO');

    $hoja4->getColumnDimension('B')->setWidth(30);
    $hoja4->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja4->getColumnDimension('C')->setWidth(30);
    $hoja4->setCellValue('C1', 'CONCEPTO PAGO');

    $hoja4->getColumnDimension('D')->setWidth(20);
    $hoja4->setCellValue('D1', 'CANTIDAD ABONADA');

    $hoja4->getColumnDimension('E')->setWidth(20);
    $hoja4->setCellValue('E1', 'SALDO PENDIENTE');

    $hoja4->getColumnDimension('F')->setWidth(20);
    $hoja4->setCellValue('F1', 'FECHA');

    $fila = 2;
    foreach ($pagos as $key => $value) {
        $fecha1 = substr($value["fecha_pago"],0,10);
        $hoja4->setCellValue('A'.$fila, ($key+1));
        $hoja4->setCellValue('B'.$fila, $value["nombre_cliente"]);
    if($value["id_cuota"] == null ||$value["id_cuota"] == 0 ){
        $hoja4->setCellValue('C'.$fila, 'ABONO');
    }else{
        $hoja4->setCellValue('C'.$fila, 'PAGO A CUOTA');

    }
    
    $hoja4->setCellValue('D'.$fila, $value["monto_pagado"]);
    $hoja4->setCellValue('E'.$fila, $value["saldo_pendiente"]);
    $hoja4->setCellValue('F'.$fila, $fecha1);    
    
    $fila++;        
    }

    // Obtener la hoja5 activa
    $hoja5 = $spreadsheet->createSheet();
    $hoja5->setTitle('REPORTE DE GANANCIAS');

    // Escribir datos en la hoja5
    $hoja5->getColumnDimension('A')->setWidth(10);
    $hoja5->setCellValue('A1', 'N° GANANCIA');

    $hoja5->getColumnDimension('B')->setWidth(30);
    $hoja5->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja5->getColumnDimension('C')->setWidth(30);
    $hoja5->setCellValue('C1', 'CONCEPTO PAGO');

    $hoja5->getColumnDimension('D')->setWidth(20);
    $hoja5->setCellValue('D1', 'MONTO');

    $hoja5->getColumnDimension('E')->setWidth(20);
    $hoja5->setCellValue('E1', 'FECHA');

    $fila = 2;
    foreach ($ganancia as $key => $value) {

        $fecha1 = substr($value["fecha_ganancia"],0,10);
        $hoja5->setCellValue('A'.$fila, ($key+1));
        $hoja5->setCellValue('B'.$fila, $value["nombre_cliente"]);
        
        if($value["id_pago"] == null ||$value["id_pago"] == 0 ){
            $hoja5->setCellValue('C'.$fila, 'ABONO');
        }else{
            $hoja5->setCellValue('C'.$fila, 'PAGO A CUOTA');

        }    
        $hoja5->setCellValue('D'.$fila, $value["ganancia"]);
        $hoja5->setCellValue('E'.$fila, $fecha1);    
        
        $fila++;        
    }

     // Obtener la hoja6 activa
    $hoja6 = $spreadsheet->createSheet();
    $hoja6->setTitle('REPORTE DE CLIENTES');

    // Escribir datos en la hoja6
    $hoja6->getColumnDimension('A')->setWidth(10);
    $hoja6->setCellValue('A1', '#');

    $hoja6->getColumnDimension('B')->setWidth(30);
    $hoja6->setCellValue('B1', 'NOMBRE CLIENTE');

    $hoja6->getColumnDimension('C')->setWidth(30);
    $hoja6->setCellValue('C1', 'DOCUMENTO');

    $hoja6->getColumnDimension('D')->setWidth(20);
    $hoja6->setCellValue('D1', 'EMAIL');

    $hoja6->getColumnDimension('E')->setWidth(20);
    $hoja6->setCellValue('E1', 'TELEFONO');

    $hoja6->getColumnDimension('F')->setWidth(20);
    $hoja6->setCellValue('F1', 'DIRECCION');

    $hoja6->getColumnDimension('G')->setWidth(20);
    $hoja6->setCellValue('G1', 'FECHA NACIMIENTO');

    $hoja6->getColumnDimension('H')->setWidth(20);
    $hoja6->setCellValue('H1', 'TOTAL DE COMPRAS');

    $hoja6->getColumnDimension('I')->setWidth(20);
    $hoja6->setCellValue('I1', 'FECHA ULTIMA COMPRA');

    $fila = 2;
    foreach ($clientes as $key => $value) {

        $fecha1 = substr($value["ultima_compra"],0,10);
        $hoja6->setCellValue('A'.$fila, ($key+1));
        $hoja6->setCellValue('B'.$fila, $value["nombre"]);
        $hoja6->setCellValue('C'.$fila, $value["documento"]);
        $hoja6->setCellValue('D'.$fila, $value["email"]);
        $hoja6->setCellValue('E'.$fila, $value["telefono"]);
        $hoja6->setCellValue('F'.$fila, $value["direccion"]);
        $hoja6->setCellValue('G'.$fila, $value["fecha_nacimiento"]);
        $hoja6->setCellValue('H'.$fila, $value["compras"]);
        $hoja6->setCellValue('I'.$fila, $fecha1);    
        
        $fila++;        
    }

     // Obtener la hoja7 activa
     $hoja7 = $spreadsheet->createSheet();
     $hoja7->setTitle('REPORTE DE CODEUDORES');
 
     // Escribir datos en la hoja7
     $hoja7->getColumnDimension('A')->setWidth(10);
     $hoja7->setCellValue('A1', '#');
 
     $hoja7->getColumnDimension('B')->setWidth(30);
     $hoja7->setCellValue('B1', 'NOMBRE CODEUDOR');
 
     $hoja7->getColumnDimension('C')->setWidth(30);
     $hoja7->setCellValue('C1', 'DOCUMENTO');
 
     $hoja7->getColumnDimension('D')->setWidth(20);
     $hoja7->setCellValue('D1', 'TELEFONO');
 
     $hoja7->getColumnDimension('E')->setWidth(20);
     $hoja7->setCellValue('E1', 'DIRECCION');
 
     $fila = 2;
     foreach ($codeudores as $key => $value) {
 
         $hoja7->setCellValue('A'.$fila, ($key+1));
         $hoja7->setCellValue('B'.$fila, $value["nombre"]);
         $hoja7->setCellValue('C'.$fila, $value["documento"]);
         $hoja7->setCellValue('D'.$fila, $value["telefono"]);
         $hoja7->setCellValue('E'.$fila, $value["direccion"]);  
         
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