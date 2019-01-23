<?php 

	# 32815219

	/* COMPROBAMOS LOS DATOS VACIOS */
	if ($_POST['direccion']=="") {
		$_POST['direccion'] = $_POST['direccion_2'];
	}
	if ($_POST['depto']=="") {
		$_POST['depto'] = $_POST['depto_2'];
	}
	if ($_POST['muni']=="") {
		$_POST['muni'] = $_POST['muni_2'];
	}
	if ($_POST['telefono']=="") {
		$_POST['telefono'] = $_POST['telefono_2'];
	}
	if ($_POST['email']=="") {
		$_POST['email'] = $_POST['email_2'];
	}

#	echo "<pre>";
#	print_r($_POST);
#	echo "</pre>";exit;

	/*Subir archivos al servidor */
	$uploads_dir = "../bodega/tmp/";

	//fileLegalizar

	if ($_FILES['fileCuentaCobro']['error']==0) {
		$extension = end(explode(".", $_FILES['fileCuentaCobro']['name']));
		$_FILES['fileCuentaCobro']['name'] = "Cuenta_de_Cobro.".$extension;

		$tmp_name = $_FILES["fileCuentaCobro"]["tmp_name"];
    	$name = basename($_FILES["fileCuentaCobro"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}

	if ($_FILES['fileAportes']['error']==0) {
		$extension = end(explode(".", $_FILES['fileAportes']['name']));
		$_FILES['fileAportes']['name'] = "Aportes_Parafiscales.".$extension;

		$tmp_name = $_FILES["fileAportes"]["tmp_name"];
    	$name = basename($_FILES["fileAportes"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}

	if ($_FILES['fileRUT']['error']==0) {
		$extension = end(explode(".", $_FILES['fileRUT']['name']));
		$_FILES['fileRUT']['name'] = "RUT.".$extension;

		$tmp_name = $_FILES["fileRUT"]["tmp_name"];
    	$name = basename($_FILES["fileRUT"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}

	if ($_FILES['fileCertOrigen']['error']==0) {
		$extension = end(explode(".", $_FILES['fileCertOrigen']['name']));
		$_FILES['fileCertOrigen']['name'] = "Certificado_origen.".$extension;

		$tmp_name = $_FILES["fileCertOrigen"]["tmp_name"];
    	$name = basename($_FILES["fileCertOrigen"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}

	if ($_FILES['fileCedula']['error']==0) {
		$extension = end(explode(".", $_FILES['fileCedula']['name']));
		$_FILES['fileCedula']['name'] = "Cedula.".$extension;

		$tmp_name = $_FILES["fileCedula"]["tmp_name"];
    	$name = basename($_FILES["fileCedula"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}

	if ($_FILES['fileLegalizar']['error']==0) {
		$extension = end(explode(".", $_FILES['fileLegalizar']['name']));
		$_FILES['fileLegalizar']['name'] = "Legalizacion_Viaticos.".$extension;

		$tmp_name = $_FILES["fileLegalizar"]["tmp_name"];
    	$name = basename($_FILES["fileLegalizar"]["name"]);
    	move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}


	$adjuntosSubidos = "[";
	foreach ($_FILES as $key) {
		if ($key['error']==0) {
			$adjuntosSubidos .= '"'.$key['name'].'",';
		}
	}

	$adjuntosSubidos = trim($adjuntosSubidos, ",");
	$adjuntosSubidos .= "]";

	/*Variables predefinidas*/
	$mediorespuesta = 0;
	$depto = $_POST['depto'];
	$muni = $_POST['muni'];
	$direccion = $_POST['direccion'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];

	
    //traemos el expediente de usuario si este lo tiene 
	$sql_expediente="select sgd_exp_numero from sgd_sexp_secexpedientes  sexp where SEXP.sgd_sexp_parexp2='".$_POST['numid']."' limit 1";
	$rs_expediente=$db->conn->Execute($sql_expediente);
	
	if(!$rs_expediente->EOF){
		$numeroExpediente = $rs_expediente->fields["sgd_exp_numero"];
	}else {
		$numeroExpediente = "";
	}
	

/*	echo "nombre_remitente ->".$_POST['nombre_remitente']."<br>";
	echo "apellidos_remitente ->".$_POST['apellidos_remitente']."<br>";
	echo "direccion ->".$_POST['direccion']."<br>";
	echo "email ->".$_POST['email']."<br>";
	echo "telefono ->".$_POST['telefono']."<br>";
	echo "depto ->".$_POST['depto']."<br>";
	echo "muni ->".$_POST['muni']."<br>";
	echo "pais ->".$_POST['pais']."<br>";

	echo "tipoPoblacion ->".$_POST['tipoPoblacion']."<br>";
	echo "asunto ->".$_POST['asunto']."<br>";
	echo "comentario ->".$_POST['comentario']."<br>";
	echo "adjuntosSubidos ->".$_POST['adjuntosSubidos']."<br>";
	echo "captcha ->".$_POST['captcha']."<br>";
	echo "pqrsFacebook ->".$_POST['pqrsFacebook']."<br>";
	echo "idFormulario ->".$_POST['idFormulario']."<br>";

	echo 'Más información de depuración:';
	print_r($_FILES);

	
	exit; */

 ?>