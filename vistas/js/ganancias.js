$(document).ready(function() {

    var perfilOculto = $("#perfilOculto").val();

    $('.tablaGanancias').DataTable( {
        "ajax": "vistas/modulos/ganancias.php?perfilOculto="+perfilOculto,
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

    $('#daterange-btnGanancias').daterangepicker(
    {
        ranges   : {
        'Hoy'       : [moment(), moment()],
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
        $('#daterange-btnGanancias span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        var fechaInicial = start.format('YYYY-MM-DD');

        var fechaFinal = end.format('YYYY-MM-DD');

        var capturarRango = $("#daterange-btnGanancias span").html();
    
        localStorage.setItem("capturaRangoGanancia", capturarRango);

        window.location = "index.php?ruta=ganancias&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

    });

    /*=============================================
    CANCELAR RANGO DE FECHAS
    =============================================*/

    $(".daterangepicker.opensleft .range_inputs .cancelBtnCobros").on("click", function(){

        localStorage.removeItem("capturaRangoGanancia");
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

        if(textoHoy == "Hoy"){

            var d = new Date();
            
            var dia = d.getDate();
            var mes = d.getMonth()+1;
            var año = d.getFullYear();

            
				if(mes < 10){

					mes = "0"+mes;

				}if(dia < 10){

					dia ="0"+dia;

				}else if(mes < 10 && dia < 10){

					mes = "0"+mes;
					dia ="0"+dia;

				}
				const fechaInicial = año + "-" +mes+"-"+dia;
				const fechaFinal = año + "-" +mes+"-"+dia;
            localStorage.setItem("capturaRangoGanancia", "Hoy");

            window.location = "index.php?ruta=ganancias&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

        }

    });

    
});
