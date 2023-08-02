$(document).ready(()=>{



/*=============================================
		RANGO DE FECHAS
		=============================================*/

        $("#prestamos-activos-btn").daterangepicker(
            {
              ranges: {
                "Hoy2": [moment(), moment()],
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
              $("#prestamos-activos-btn span").html(
                start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
              );
        
              var fechaInicial = start.format("YYYY-MM-DD");
        
              var fechaFinal = end.format("YYYY-MM-DD");
        
              var capturarRango = $("#prestamos-activos-btn span").html();
        
              localStorage.setItem("capturarRango", capturarRango);
        
              window.location =
                "index.php?ruta=prestamos-activos&fechaInicial=" +
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
        
            if (textoHoy == "Hoy2") {
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
        
              localStorage.setItem("capturarRango", "Hoy2");
        
              window.location =
                "index.php?ruta=prestamos-activos&fechaInicial=" +
                fechaInicial +
                "&fechaFinal=" +
                fechaFinal;
            }
          });
        });