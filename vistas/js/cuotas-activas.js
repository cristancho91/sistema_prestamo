/*=============================================
		RANGO DE FECHAS
		=============================================*/

$("#daterange-btnCuotas-activas").daterangepicker(
  {
    ranges: {
      "hoy5": [moment(), moment()],
      "Ayer": [moment().subtract(1, "days"), moment().subtract(1, "days")],
      "Últimos 7 días": [moment().subtract(6, "days"), moment()],
      "Últimos 30 días": [moment().subtract(29, "days"), moment()],
      "Este mes": [moment().startOf("month"), moment().endOf("month")],
      "Último mes": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
    startDate: moment(),
    endDate: moment(),
  },
  function (start, end) {
    $("#daterange-btnCuotas-activas span").html(
      start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
    );

    var fechaInicial = start.format("YYYY-MM-DD");

    var fechaFinal = end.format("YYYY-MM-DD");

    var capturarRango = $("#daterange-btnCuotas-activas span").html();

    localStorage.setItem("capturarRangocobro", capturarRango);

    window.location =
      "index.php?ruta=cuotas-activas&fechaInicial=" +
      fechaInicial +
      "&fechaFinal=" +
      fechaFinal;
  }
);


/*=============================================
            CAPTURAR HOY
            =============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function () {
  var textoHoy = $(this).attr("data-range-key");

  if (textoHoy == "hoy5") {
    var d = new Date();

    var dia = d.getDate();
    var mes = d.getMonth() + 1;
    var año = d.getFullYear();

    if (mes < 10) {
      mes = "0" + mes;
    }
    if (dia < 10) {
      dia = "0" + dia;
    }
    if (mes < 10 && dia < 10) {
      mes = "0" + mes;
      dia = "0" + dia;
    }
    const fechaInicial = año + "-" + mes + "-" + dia;
    const fechaFinal = año + "-" + mes + "-" + dia;
    localStorage.setItem("capturarRangocobro", "hoy5");

    window.location =
      "index.php?ruta=cuotas-activas&fechaInicial=" +
      fechaInicial +
      "&fechaFinal=" +
      fechaFinal;
  }
});
