<?php

error_reporting(0);

if(isset($_GET["fechaInicial"])){

    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

}else{

$fechaInicial = null;
$fechaFinal = null;

}

$respuesta = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);
$arrayFechas = array();
$arrayVentas = array();
$sumaGanancias = array();

foreach ($respuesta as $key => $value) {

	#Capturamos sólo el año y el mes
	$fecha = substr($value["fecha"],0,7);

	#Introducir las fechas en arrayFechas
	array_push($arrayFechas, $fecha);

	#Capturamos las ganancias
	$arrayVentas = array($fecha => $value["ganancia_venta"]);
  
	#Sumamos las ganancias que ocurrieron el mismo mes
	foreach ($arrayVentas as $key => $value) {
		
		$sumaGanancias[$key] += $value;
	}
  

}

$noRepetirFechas = array_unique($arrayFechas);
// var_dump($noRepetirFechas);

?>

<!--=====================================
GRÁFICO DE GANANCIAS
======================================-->


<div class="box box-solid bg-teal-gradient">
	
	<div class="box-header">
		
 		<i class="fa fa-th"></i>

  		<h3 class="box-title">Gráfico de Ganancias</h3>

	</div>

	<div class="box-body border-radius-none nuevoGraficoVentas">

		<div class="chart" id="line-chart-ventas" style="height: 250px;"></div>

  </div>

</div>

<script>
	
 var line = new Morris.Line({
    element          : 'line-chart-ventas',
    resize           : true,
    data             : [

    <?php

    if($noRepetirFechas != null){

	    foreach($noRepetirFechas as $key){
        
	    	echo "{ y: '".$key."', ganancias: ".$sumaGanancias[$key]." },";
        

	    }
	    echo "{y: '".$key."', ganancias: ".$sumaGanancias[$key]." }";
      
    }else{
       echo "{ y: '0', ganancias: '0' }";

    }


    ?>

    ],
    xkey             : 'y',
    ykeys            : ['ganancias'],
    labels           : ['ganancias'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    preUnits         : '$',
    gridTextSize     : 10
  });

</script>