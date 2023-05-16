/*=============================================
CARGAR LA TABLA DINÁMICA DE COBROS
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-cobros.ajax.php",
// 	success:function(respuesta){
		
// 		console.log("respuesta", respuesta);

// 	}

// });

$(document).ready(function() {

		var perfilOculto = $("#perfilOculto").val();

		$('.tablaProductos').DataTable( {
			"ajax": "vistas/modulos/cobros.php?perfilOculto="+perfilOculto,
			"deferRender": true,
			"retrieve": true,
			"processing": true,
			"language": {

					"sProcessing":     "Procesando...",
					"sLengthMenu":     "Mostrar _MENU_ registros",
					"sZeroRecords":    "No se encontraron resultados",
					"sEmptyTable":     "Ningún dato disponible en esta tabla",
					"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
					"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
					"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix":    "",
					"sSearch":         "Buscar:",
					"sUrl":            "",
					"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
					"sFirst":    "Primero",
					"sLast":     "Último",
					"sNext":     "Siguiente",
					"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}

			}

		} );


		/*=============================================
		RANGO DE FECHAS
		=============================================*/

		$('#daterange-btnCobros').daterangepicker(
		{
			ranges   : {
			'Hoy2'       : [moment(), moment()],
			'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
			'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
			'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
			'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			},
			startDate: moment(),
			endDate  : moment()
		},
		function (start, end) {
			$('#daterange-btnCobros span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

			var fechaInicial = start.format('YYYY-MM-DD');

			var fechaFinal = end.format('YYYY-MM-DD');

			var capturarRango = $("#daterange-btnCobros span").html();
		
			localStorage.setItem("capturarRangocobro", capturarRango);

			window.location = "index.php?ruta=cobros&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

		});

		/*=============================================
		CANCELAR RANGO DE FECHAS
		=============================================*/

		$(".daterangepicker.opensleft .range_inputs .cancelBtnCobros").on("click", function(){

			localStorage.removeItem("capturarRangocobro");
			localStorage.clear();

			// Obtener la cadena de consulta de la URL actual
			var queryString = window.location.search;

			// Crear un objeto URLSearchParams para analizar la cadena de consulta
			var params = new URLSearchParams(queryString);

			// Obtener el valor de una variable GET específica
			var ruta = params.get('ruta');

			// if(ruta==null){
			// 	ruta = "cobros";
			// }

			// Utilizar el valor de la ruta GET
			window.location = ruta;
		});

		/*=============================================
		CAPTURAR HOY
		=============================================*/

		$(".daterangepicker.opensleft .ranges li").on("click", function(){

			var textoHoy = $(this).attr("data-range-key");

			if(textoHoy == "Hoy2"){

				var d = new Date();
				
				var dia = d.getDate();
				var mes = d.getMonth()+1;
				var año = d.getFullYear();

				if(mes < 10){

					var fechaInicial = año+"-0"+mes+"-"+dia;
					var fechaFinal = año+"-0"+mes+"-"+dia;

				}if(dia < 10){

					var fechaInicial = año+"-"+mes+"-0"+dia;
					var fechaFinal = año+"-"+mes+"-0"+dia;

				}if(mes < 10 && dia < 10){

					var fechaInicial = año+"-0"+mes+"-0"+dia;
					var fechaFinal = año+"-0"+mes+"-0"+dia;

				}else{

					var fechaInicial = año+"-"+mes+"-"+dia;
					var fechaFinal = año+"-"+mes+"-"+dia;

				}
				localStorage.setItem("capturarRangocobro", "Hoy2");

				window.location = "index.php?ruta=cobros&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

			}

		});

		
	});
	// AGREGANDO DATOS A LA CUOTA A PAGAR 
	$(".tablaCobros").on("click",".btnPagarCuota", function(){
		
		var idCuota = $(this).data("id_cuota");
		var datos = new FormData();
		datos.append("idCuota", idCuota);

		$.ajax({

			url:"ajax/cobros.ajax.php",
			method: "POST",
			data: datos,
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(respuesta){


				$("#idPrestamo").val(respuesta["id_prestamo"]);
				$("#id_cuota").val(respuesta["id_cuota"]);
				$("#numCouta").val(respuesta["num_cuota"]);
				$("#cantidad").val(respuesta["monto_cuota"]);
				$("#interesPagar").val(respuesta["interes_a_pagar"]);
				$("#capitalPagar").val(respuesta["capital_a_pagar"]);
				$("#capitalPendiente").val(respuesta["cantidad_pendiente"]);
				$("#fechaCobro").val(respuesta["fecha_vencimiento"]);
				$("#estadoCuota").val(respuesta["estado"]);

				let idPrestamo = respuesta["id_prestamo"];
				let datos2 = new FormData();
				datos2.append("idPrestamo",idPrestamo);

				$.ajax({					

					url:"ajax/cobros.ajax.php",
					method: "POST",
					data: datos2,
					cache: false,
					contentType: false,
					processData: false,
					dataType: "json",
					success: function(respuesta){


						let idCliente = respuesta["id_cliente"];
						let datos3 = new FormData();
						datos3.append("idCliente",idCliente);

						$.ajax({					

							url:"ajax/cobros.ajax.php",
							method: "POST",
							data: datos3,
							cache: false,
							contentType: false,
							processData: false,
							dataType: "json",
							success: function(respuesta){
								$("#nombreCliente").val(respuesta["nombre"]);

							}

						});



					}


				});

				


			}

		});
	});
