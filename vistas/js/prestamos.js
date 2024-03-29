/*=============================================
CARGAR LA TABLA DINÁMICA DE VENTAS
=============================================*/

// $.ajax({

// 	url: "ajax/datatable-ventas.ajax.php",
// 	success:function(respuesta){

// 		console.log("respuesta", respuesta);

// 	}

// })//
$(document).ready(function () {
  $(".tablaVentas").DataTable({
    ajax: "ajax/datatable-prestamos.ajax.php",
    deferRender: true,
    retrieve: true,
    processing: true,
    language: {
      sProcessing: "Procesando...",
      sLengthMenu: "Mostrar _MENU_ registros",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
      sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
      sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
      sInfoPostFix: "",
      sSearch: "Buscar:",
      sUrl: "",
      sInfoThousands: ",",
      sLoadingRecords: "Cargando...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "Siguiente",
        sPrevious: "Anterior",
      },
      oAria: {
        sSortAscending:
          ": Activar para ordenar la columna de manera ascendente",
        sSortDescending:
          ": Activar para ordenar la columna de manera descendente",
      },
    },
  });

  /*=============================================
		FORMATEO DE CAMPO DE MONTO PRESTAMO
		=============================================*/
  

  /*=============================================
		SUMAR TODOS LOS PRECIOS
		=============================================*/

  function sumarTotalPrecios() {
    var precioItem = $(".nuevoPrecioProducto");

    var arraySumaPrecio = [];

    for (var i = 0; i < precioItem.length; i++) {
      arraySumaPrecio.push(Number($(precioItem[i]).val()));
    }

    function sumaArrayPrecios(total, numero) {
      return total + numero;
    }

    var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);

    $("#nuevoTotalVenta").val(sumaTotalPrecio);
    $("#totalVenta").val(sumaTotalPrecio);
    $("#nuevoTotalVenta").attr("total", sumaTotalPrecio);
  }

  /*=============================================
		FORMATO AL PRECIO FINAL
		=============================================*/

  $("#nuevoTotalVenta").number(true, 2);

  /*=============================================
		SELECCIONAR MÉTODO DE PAGO
		=============================================*/

  $("#nuevoMetodoPago").change(function () {
    var metodo = $(this).val();

    if (metodo == "Efectivo" || metodo == "Consignacion") {
      $(this).parent().parent().removeClass("col-xs-6");

      $(this).parent().parent().addClass("col-xs-4");

      $(this)
        .parent()
        .parent()
        .parent()
        .children(".cajasMetodoPago")
        .html(
          '<div class="col-xs-4">' +
            '<div class="input-group">' +
            '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
            '<input type="text" class="form-control" id="nuevoValorEfectivo" placeholder="000000" required>' +
            "</div>" +
            "</div>" +
            '<div class="col-xs-4" id="capturarCambioEfectivo" style="padding-left:0px">' +
            '<div class="input-group">' +
            '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>' +
            '<input type="text" class="form-control" id="nuevoCambioEfectivo" placeholder="000000" readonly required>' +
            "</div>" +
            "</div>"
        );

      // Agregar formato al precio

      $("#nuevoValorEfectivo").number(true, 2);
      $("#nuevoCambioEfectivo").number(true, 2);

      // Listar método en la entrada
      listarMetodos();
    } else {
      $(this).parent().parent().removeClass("col-xs-4");

      $(this).parent().parent().addClass("col-xs-6");

      $(this)
        .parent()
        .parent()
        .parent()
        .children(".cajasMetodoPago")
        .html(
          '<div class="col-xs-6" style="padding-left:0px">' +
            '<div class="input-group">' +
            '<input type="number" min="0" class="form-control" id="nuevoCodigoTransaccion" placeholder="Código transacción"  required>' +
            '<span class="input-group-addon"><i class="fa fa-lock"></i></span>' +
            "</div>" +
            "</div>"
        );
    }
  });

  /*=============================================
		CAMBIO EN EFECTIVO
		=============================================*/
  $(".formularioVenta").on("change", "input#nuevoValorEfectivo", function () {
    var efectivo = $(this).val();

    var cambio = Number(efectivo) - Number($("#nuevoTotalVenta").val());

    var nuevoCambioEfectivo = $(this)
      .parent()
      .parent()
      .parent()
      .children("#capturarCambioEfectivo")
      .children()
      .children("#nuevoCambioEfectivo");

    nuevoCambioEfectivo.val(cambio);
  });

  /*=============================================
		CAMBIO TRANSACCIÓN
		=============================================*/
  $(".formularioVenta").on(
    "change",
    "input#nuevoCodigoTransaccion",
    function () {
      // Listar método en la entrada
      listarMetodos();
    }
  );

  /*=============================================
		LISTAR TODOS LOS PRODUCTOS
		=============================================*/

  function listarProductos() {
    var listaProductos = [];

    var descripcion = $(".nuevaDescripcionProducto");

    var cantidad = $(".nuevaCantidadProducto");

    var precio = $(".nuevoPrecioProducto");

    for (var i = 0; i < descripcion.length; i++) {
      listaProductos.push({
        id: $(descripcion[i]).attr("idProducto"),
        descripcion: $(descripcion[i]).val(),
        cantidad: $(cantidad[i]).val(),
        stock: $(cantidad[i]).attr("nuevoStock"),
        precio: $(precio[i]).attr("precioReal"),
        total: $(precio[i]).val(),
      });
    }

    $("#listaProductos").val(JSON.stringify(listaProductos));
  }

  /*=============================================
		LISTAR MÉTODO DE PAGO
		=============================================*/

  function listarMetodos() {
    var listaMetodos = "";

    if ($("#nuevoMetodoPago").val() == "Efectivo") {
      $("#listaMetodoPago").val("Efectivo");
    } else {
      $("#listaMetodoPago").val(
        $("#nuevoMetodoPago").val() + "-" + $("#nuevoCodigoTransaccion").val()
      );
    }
  }

  /*=============================================
		BOTON EDITAR PRESTAMO
		=============================================*/
  $(".tablas").on("click", ".btnEditarPrestamo", function () {
    let idPrestamo = $(this).attr("idPrestamo");

    window.location = "index.php?ruta=editar-prestamo&idPrestamo=" + idPrestamo;
  });

  /*=============================================
		FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
		=============================================*/

  function quitarAgregarProducto() {
    //Capturamos todos los id de productos que fueron elegidos en la venta
    var idProductos = $(".quitarProducto");

    //Capturamos todos los botones de agregar que aparecen en la tabla
    var botonesTabla = $(".tablaVentas tbody button.agregarProducto");

    //Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la venta
    for (var i = 0; i < idProductos.length; i++) {
      //Capturamos los Id de los productos agregados a la venta
      var boton = $(idProductos[i]).attr("idProducto");

      //Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
      for (var j = 0; j < botonesTabla.length; j++) {
        if ($(botonesTabla[j]).attr("idProducto") == boton) {
          $(botonesTabla[j]).removeClass("btn-primary agregarProducto");
          $(botonesTabla[j]).addClass("btn-default");
        }
      }
    }
  }

  /*=============================================
		CADA VEZ QUE CARGUE LA TABLA CUANDO NAVEGAMOS EN ELLA EJECUTAR LA FUNCIÓN:
		=============================================*/

  $(".tablaVentas").on("draw.dt", function () {
    quitarAgregarProducto();
  });

  /*=============================================
		BORRAR PRESTAMO
		=============================================*/
  $(".tablas").on("click", ".btnEliminarPrestamo", function () {
    var idPrestamo = $(this).attr("idPrestamo");

    swal({
      title: "¿Está seguro de borrar este prestamo?",
      text: "¡Si no lo está puede cancelar la accíón!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, borrar Prestamo!",
    }).then(function (result) {
      if (result.value) {
        window.location = "index.php?ruta=prestamos&idPrestamo=" + idPrestamo;
      }
    });
  });

  /*=============================================
		IMPRIMIR FACTURA
		=============================================*/

  $(".tablaPrestamos").on("click", ".btnImprimirFactura", function () {
    var codigoPrestamo = $(this).attr("codigoPrestamo");
    // console.log(codigoPrestamo);

    window.open(
      "extensiones/factura/generaFactura.php?codigo=" + codigoPrestamo,
      "_blank"
    );
  });
  /*=============================================
		RECOGER PRESTAMO
		=============================================*/
  const sumar = (num1, num2) => {
    return num1 + num2;
  };
  $(".tablaPrestamos").on("click", ".btnCancelarPrestamo", function () {
    var codigoPrestamo = $(this).attr("codigoPrestamo");

    var datos = new FormData();
    datos.append("idPrestamo", codigoPrestamo);

    $.ajax({
      url: "ajax/cobros.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        // console.log(respuesta);
        let saldoPendiente = Number(respuesta["saldo_pendiente"]);
        let tasaInteres = Number(respuesta["tasa_interes"] / 100);
        let monto = respuesta["monto"];

        let interesPagar = saldoPendiente * tasaInteres;
        let cantidadPagar = sumar(
          parseFloat(saldoPendiente),
          parseFloat(interesPagar)
        );

        $("#idPrestamo").val(respuesta["id_prestamo"]);
        $("#cantidad_prestamo").val(respuesta["monto"]);
        $("#cantidad").val(cantidadPagar);
        $("#interesPagar").val(interesPagar);
        $("#capitalPagar").val(saldoPendiente);

        let codigoCliente = respuesta["id_cliente"];
        var datos2 = new FormData();
        datos2.append("idCliente", codigoCliente);

        $.ajax({
          url: "ajax/cobros.ajax.php",
          method: "POST",
          data: datos2,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function (respuesta) {
            $("#nombreCliente").val(respuesta["nombre"]);
          },
        });
      },
    });
  });

  /*=============================================
		RANGO DE FECHAS
		=============================================*/

  $("#daterange-btn").daterangepicker(
    {
      ranges: {
        "Hoy3": [moment(), moment()],
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
      $("#daterange-btn span").html(
        start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
      );

      var fechaInicial = start.format("YYYY-MM-DD");

      var fechaFinal = end.format("YYYY-MM-DD");

      var capturarRango = $("#daterange-btn span").html();

      localStorage.setItem("capturarRango", capturarRango);

      window.location =
        "index.php?ruta=prestamos&fechaInicial=" +
        fechaInicial +
        "&fechaFinal=" +
        fechaFinal;
    }
  );

  /*=============================================
		CANCELAR RANGO DE FECHAS
		=============================================*/

  $(".daterangepicker.opensleft .range_inputs .cancelBtn").on(
    "click",
    function () {
      localStorage.removeItem("capturarRango");
      localStorage.clear();
      // Obtener la cadena de consulta de la URL actual
      var queryString2 = window.location.search;

      // Crear un objeto URLSearchParams para analizar la cadena de consulta
      var params = new URLSearchParams(queryString2);

      // Obtener el valor de una variable GET específica
      var ruta2 = params.get("ruta");
      // if(ruta2==null){
      // 	ruta2 = "prestamos";
      // }
      // Utilizar el valor de la ruta GET
      window.location = ruta2;
    }
  );

  /*=============================================
		CAPTURAR HOY
		=============================================*/

  $(".daterangepicker.opensleft .ranges li").on("click", function () {
    var textoHoy = $(this).attr("data-range-key");

    if (textoHoy == "Hoy3") {
      var d = new Date();

      var dia = d.getDate();
      var mes = d.getMonth() + 1;
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

      localStorage.setItem("capturarRango", "Hoy3");

      window.location =
        "index.php?ruta=prestamos&fechaInicial=" +
        fechaInicial +
        "&fechaFinal=" +
        fechaFinal;
    }
  });
});
