/*=============================================
EDITAR CODEUDOR
=============================================*/
$(".tablas").on("click", ".btnEditarCodeudor", function(){

	var idCodeudor = $(this).attr("idCodeudor");

	var datos = new FormData();
    datos.append("idCodeudor", idCodeudor);

    $.ajax({

      url:"ajax/codeudores.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
      
      	   $("#idCodeudor").val(respuesta["id"]);
	       $("#editarCodeudor").val(respuesta["nombre"]);
	       $("#editarDocumentoId").val(respuesta["documento"]);
	       $("#editarTelefono").val(respuesta["telefono"]);
	       $("#editarDireccion").val(respuesta["direccion"]);
	  }

  	})

})

/*=============================================
ELIMINAR  CODEUDOR
=============================================*/
$(".tablas").on("click", ".btnEliminarCodeudor", function(){

	var idCodeudor = $(this).attr("idCodeudor");
	
	swal({
        title: '¿Está seguro de borrar el Codeudor?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar codeudor!'
      }).then(function(result){
        if (result.value) {
          
            window.location = "index.php?ruta=codeudores&idCodeudor="+idCodeudor;
        }

  })

})