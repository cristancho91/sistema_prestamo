<?php

class ControladorCodeudores{

	/*=============================================
	CREAR CODEUDOR
	=============================================*/

	static public function ctrCrearCodeudor(){

		if(isset($_POST["nuevoCodeudor"])){

			if($_POST["nuevoCodeudor"]){

			   	$tabla = "codeudores";

			   	$datos = array("nombre"=>$_POST["nuevoCodeudor"],
					           "documento"=>$_POST["nuevoDocumento"],
					           "telefono"=>$_POST["telefono"],
					           "direccion"=>$_POST["direccion"]);

			   	$respuesta = ModeloCodeudores::mdlIngresarCodeudor($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El codeudor ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "codeudores";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El codeudor no pudo ser creado!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "codeudores";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	MOSTRAR CODEUDORES
	=============================================*/

	static public function ctrMostrarCodeudores($item, $valor){

		$tabla = "codeudores";

		$respuesta = ModeloCodeudores::mdlMostrarCodeudores($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	EDITAR CODEUDOR
	=============================================*/

	static public function ctrEditarCodeudor(){

		if(isset($_POST["editarCodeudor"])){

			if($_POST["editarCodeudor"]){

			   	$tabla = "codeudores";

			   	$datos = array("id"=>$_POST["idCodeudor"],
			   				   "nombre"=>$_POST["editarCodeudor"],
					           "documento"=>$_POST["editarDocumentoId"],
					           "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"]);

			   	$respuesta = ModeloCodeudores::mdlEditarCodeudor($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El codeudor ha sido actualizado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "codeudores";

									}
								})

					</script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El codeudor no pudo ser editado!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "codeudores";

							}
						})

			  	</script>';



			}

		}

	}

	/*=============================================
	ELIMINAR CODEUDOR
	=============================================*/

	static public function ctrEliminarCodeudor(){

		if(isset($_GET["idCodeudor"])){

			$tabla ="codeudores";
			$datos = $_GET["idCodeudor"];

			$respuesta = ModeloCodeudores::mdlEliminarCodeudor($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El codeudor ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar",
					  closeOnConfirm: false
					  }).then(function(result){
								if (result.value) {

								window.location = "codeudores";

								}
							})

				</script>';

			}		

		}

	}

}

