<?php

	echo base64_encode('2');

	require_once '../dompdf/vendor/autoload.php';
	require_once '../dompdf/autoload.inc.php';
	use Dompdf\Dompdf;
	use Dompdf\Options;

	ob_start();
	include(dirname('__FILE__').'/factura.php');
	$html = ob_get_clean();
	$options = new Options();
	$options->set('isRemoteEnabled', TRUE);
	$codigo = $_GET["codigo"];
	// instantiate and use the dompdf class
	$dompdf = new Dompdf($options);
	$dompdf->loadHtml($html);
	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('letter', 'portrait');
	// Render the HTML as PDF
	$dompdf->render();
	// Output the generated PDF to Browser
	$dompdf->stream('factura_'.$codigo.'.pdf',array('Attachment'=>0));
	exit;

?>