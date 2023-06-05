<?php

class ControladorCodeudores{

	/*=============================================
	CREAR CODEUDOR
	=============================================*/

	static public function ctrCrearCodeudor(){

		if(isset($_POST["nuevoCodeudor"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCodeudor"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["telefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["direccion"])){

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
						  title: "¡El codeudor no puede ir vacío o llevar caracteres especiales!",
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

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCodeudor"]) &&
			   preg_match('/^[0-9]+$/', $_POST["editarDocumentoId"]) &&
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){

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
						  title: "¡El codeudor no puede ir vacío o llevar caracteres especiales!",
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

