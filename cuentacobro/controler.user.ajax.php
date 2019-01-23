<?php  

	$ruta_raiz = "../"; 
	include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");
	if (!$db) $db = new ConnectionHandler($ruta_raiz);
	$ADODB_COUNTRECS = false;
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	

	$cedula = $_POST['value'];
	##$cedula = '1143845875';

	if ($cedula == null ){
		die('No data');
	}

	$isql="select 
	ciu.SGD_CIU_NOMBRE as nombres,
	( coalesce(ciu.sgd_ciu_apell1,'') || ' ' || coalesce(ciu.sgd_ciu_apell2,'') ) as apellidos,
	dir.sgd_dir_direccion,
	dir.sgd_dir_mail,
	dir.sgd_dir_telefono,
	ciu.dpto_codi, 
	ciu.muni_codi
	from radicado ra
	inner join sgd_dir_drecciones dir on dir.RADI_NUME_RADI = ra.RADI_NUME_RADI
	inner join sgd_ciu_ciudadano ciu on ciu.sgd_ciu_codigo = dir.sgd_ciu_codigo 
	where dir.sgd_dir_doc = '".$cedula."' order by dir.id desc limit 1";
	
	$aux = 0;
	$datosUsuarios=$db->conn->Execute($isql);
	$datosAux=$db->conn->Execute($isql);

	while (!$datosAux->EOF){
			$aux++;
	      	$datosAux->MoveNext();
	}


	if($aux>0){
		$dato['STATUS'] = true;
		$dato['NOMBRES']= strtoupper($datosUsuarios->fields["NOMBRES"]);
		$dato['APELLIDOS']=  strtoupper($datosUsuarios->fields["APELLIDOS"]);
		$dato['DIRECCION']=  strtoupper($datosUsuarios->fields["SGD_DIR_DIRECCION"]);
		$dato['MAIL']=  strtoupper($datosUsuarios->fields["SGD_DIR_MAIL"]);
		$dato['TELEFONO']=  strtoupper($datosUsuarios->fields["SGD_DIR_TELEFONO"]);
		$dato['DEPARTAMENTO']=  strtoupper($datosUsuarios->fields["DPTO_CODI"]);
		$dato['MUNICIPIO']=  strtoupper($datosUsuarios->fields["MUNI_CODI"]);	

	
	    //traemos el expediente de usuario si este lo tiene 
		$sql_expediente="select sgd_exp_numero from sgd_sexp_secexpedientes  sexp where SEXP.sgd_sexp_parexp2='".$cedula."' limit 1";
		$rs_expediente=$db->conn->Execute($sql_expediente);
		
		if(!$rs_expediente->EOF){
			$numeroExpediente = $rs_expediente->fields["sgd_exp_numero"];
		}else {
			$numeroExpediente = "";
		}

		$dato['EXPEDIENTE']=$numeroExpediente;

	}else{
		$dato['STATUS'] = false;
		$dato['MENSAJE']= 'NO DATA FOR USER';
		$dato['EXPEDIENTE']="";
	}

	//print_r($dato); exit;
	
 echo json_encode($dato);

?>