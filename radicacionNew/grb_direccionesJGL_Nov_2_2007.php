<?
error_reporting(7);
echo "<hr><br />Inicia grabar direcciones. <br />";
/**
 * Modulo en el cual se inserta y actualizan datos de los remitentes y destinatarios de los radicados
 * @autor JAIRO LOSASDA
 * Modificado por HLP.
 */
include_once("$ruta_raiz/class_control/Municipio.php");
if (!$muni_us1) $muni_us1 = NULL;
if (!$muni_us2) $muni_us2 = NULL;
if (!$muni_us3) $muni_us3 = NULL;
if (!$muni_us7) $muni_us7 = NULL;

echo "<hr><br />Inicia grabar direcciones muni_us1: " . $muni_us1 ."muni_us2: " . $muni_us2 . "muni_us3: " . $muni_us3 . "muni_us7: ". $muni_us7 . "<br />";
// Creamos las valores del codigo del dpto y mcpio desglozando el valor del <SELECT> correspondiente.
if (!is_null($muni_us1))
{	$tmp_mun = new Municipio($conexion);
	$tmp_mun->municipio_codigo($codep_us1,$muni_us1);

	$tmp_idcont = $tmp_mun->get_cont_codi();
	$tmp_idpais = $tmp_mun->get_pais_codi();
	$muni_tmp1 = explode("-",$muni_us1);
	switch (count($muni_tmp1))
	{	case 4:
			{
				echo "<hr><br />Muni 1 caso 4.<br />";

				$idcont1 = $muni_tmp1[0];
				$idpais1 = $muni_tmp1[1];
				$dpto_tmp1 = $muni_tmp1[2];
				$muni_tmp1 = $muni_tmp1[3];

			}break;
		case 3:
			{
				echo "<hr><br />Muni 1 caso 3.<br />";
				$idcont1 = $tmp_idcont;
				$idpais1 = $muni_tmp1[0];
				$dpto_tmp1 = $muni_tmp1[1];
				$muni_tmp1 = $muni_tmp1[2];
			}break;
		case 2:
			{
				echo "<hr><br />Muni 1 caso 2.<br />";
				$idcont1 = $tmp_idcont;
				$idpais1 = $tmp_idpais;
				$dpto_tmp1 = $muni_tmp1[0];
				$muni_tmp1 = $muni_tmp1[1];
			}break;
	}
	unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
}
if (!is_null($muni_us2))
{	$tmp_mun = new Municipio($conexion);
	$tmp_mun->municipio_codigo($codep_us2,$muni_us2);
	$tmp_idcont = $tmp_mun->get_cont_codi();
	$tmp_idpais = $tmp_mun->get_pais_codi();
	$muni_tmp2 = explode("-",$muni_us2);
	switch (count($muni_tmp2))
	{	case 4:
			{
				echo "<hr><br />Muni 2 caso 4.<br />";
				$idcont2 = $muni_tmp2[0];
				$idpais2 = $muni_tmp2[1];
				$dpto_tmp2 = $muni_tmp2[2];
				$muni_tmp2 = $muni_tmp2[3];
			}break;
		case 3:
			{
				echo "<hr><br />Muni 2 caso 3.<br />";
				$idcont2 = $tmp_idcont;
				$idpais2 = $muni_tmp2[0];
				$dpto_tmp2 = $muni_tmp2[1];
				$muni_tmp2 = $muni_tmp2[2];
			}break;
		case 2:
			{
				echo "<hr><br />Muni 2 caso 2.<br />";
				$idcont2 = $tmp_idcont;
				$idpais2 = $tmp_idpais;
				$dpto_tmp2 = $muni_tmp2[0];
				$muni_tmp2 = $muni_tmp2[1];
			}break;
	}
	unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
}
if (!is_null($muni_us3))
{	$tmp_mun = new Municipio($conexion);
	$tmp_mun->municipio_codigo($codep_us3,$muni_us3);
	$tmp_idcont = $tmp_mun->get_cont_codi();
	$tmp_idpais = $tmp_mun->get_pais_codi();
	$muni_tmp3 = explode("-",$muni_us3);
	switch (count($muni_tmp3))
	{	case 4:
			{
				echo "<hr><br />Muni 3 caso 4.<br />";
				$idcont3 = $muni_tmp3[0];
				$idpais3 = $muni_tmp3[1];
				$dpto_tmp3 = $muni_tmp3[2];
				$muni_tmp3 = $muni_tmp3[3];
			}break;
		case 3:
			{
				echo "<hr><br />Muni 3 caso 3.<br />";
				$idcont1 = $tmp_idcont;
				$idpais3 = $muni_tmp3[0];
				$dpto_tmp3 = $muni_tmp3[1];
				$muni_tmp3 = $muni_tmp3[2];
			}break;
		case 2:
			{
				echo "<hr><br />Muni 3 caso 2.<br />";
				$idcont3 = $tmp_idcont;
				$idpais3 = $tmp_idpais;
				$dpto_tmp3 = $muni_tmp3[0];
				$muni_tmp3 = $muni_tmp3[1];
			}break;
	}
	unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
}
/*AGREGADO OTRO DEST*/
/*
if (!is_null($muni_us7))
{	$tmp_mun = new Municipio($conexion);
	$tmp_mun->municipio_codigo($codep_us7,$muni_us7);
	$tmp_idcont = $tmp_mun->get_cont_codi();
	$tmp_idpais = $tmp_mun->get_pais_codi();
	$muni_tmp7 = explode("-",$muni_us7);
	switch (count($muni_tmp7))
	{	case 4:
			{
				echo "<hr><br />Muni 3 caso 4.<br />";
				$idcont7 = $muni_tmp7[0];
				$idpais7 = $muni_tmp7[1];
				$dpto_tmp7 = $muni_tmp7[2];
				$muni_tmp7 = $muni_tmp7[3];
			}break;
		case 3:
			{
				echo "<hr><br />Muni 3 caso 3.<br />";
				$idcont1 = $tmp_idcont;
				$idpais7 = $muni_tmp7[0];
				$dpto_tmp7 = $muni_tmp7[1];
				$muni_tmp7 = $muni_tmp7[2];
			}break;
		case 2:
			{
				echo "<hr><br />Muni 3 caso 2.<br />";
				$idcont7 = $tmp_idcont;
				$idpais7 = $tmp_idpais;
				$dpto_tmp7 = $muni_tmp7[0];
				$muni_tmp7 = $muni_tmp7[1];
			}break;
	}
	unset($tmp_mun);unset($tmp_idcont);unset($tmp_idpais);
}
*/
/*FINAL OTRO DEST*/



$newId = false;
if(!$modificar)
{
		echo "<hr><br />No Modificar<br />";

   $nextval=$conexion->nextId("sec_dir_drecciones");
}
if ($nextval==-1)
{
	echo "<span class='etextomenu'>No se encontro la secuencia sec_dir_drecciones ";
}
global $ADODB_COUNTRECS;
if($documento_us1 and !$cc)
{
		echo "<hr><br />documento_us1 y no CC.<br />";

	$sgd_ciu_codigo=0;
	$sgd_oem_codigo=0;
	$sgd_esp_codigo=0;
	$sgd_fun_codigo=0;
  	if($tipo_emp_us1==0)
  	{	$sgd_ciu_codigo=$documento_us1;
		$sgdTrd = "1";
	}
	if($tipo_emp_us1==1)
	{	$sgd_esp_codigo=$documento_us1;
		$sgdTrd = "3";
	}
	if($tipo_emp_us1==2)
	{	$sgd_oem_codigo=$documento_us1;
		$sgdTrd = "2";
	}
	if($tipo_emp_us1==6)
	{	$sgd_fun_codigo=$documento_us1;
		$sgdTrd = "4";
	}

	$ADODB_COUNTRECS = true;

	$record = array();
	$record['SGD_TRD_CODIGO'] = $sgdTrd;
	$record['SGD_DIR_NOMREMDES'] = $grbNombresUs1;
	$record['SGD_DIR_DOC'] = $cc_documento_us1;
	$record['MUNI_CODI'] = $muni_tmp1;
	$record['DPTO_CODI'] = $dpto_tmp1;
	$record['ID_PAIS'] = $idpais1;
	$record['ID_CONT'] = $idcont1;
	$record['SGD_DOC_FUN'] = $sgd_fun_codigo;
	$record['SGD_OEM_CODIGO'] = $sgd_oem_codigo;
	$record['SGD_CIU_CODIGO'] = $sgd_ciu_codigo;
	$record['SGD_OEM_CODIGO'] = $sgd_oem_codigo;
	$record['SGD_ESP_CODI'] = $sgd_esp_codigo;
	$record['RADI_NUME_RADI'] = $nurad;
	$record['SGD_SEC_CODIGO'] = 0;
	$record['SGD_DIR_DIRECCION'] = $direccion_us1;
	$record['SGD_DIR_TELEFONO'] = trim($telefono_us1);
	$record['SGD_DIR_MAIL'] = $mail_us1;
	$record['SGD_DIR_TIPO'] = 1;
	$record['SGD_DIR_CODIGO'] = $nextval;
	$record['SGD_DIR_NOMBRE'] = $otro_us1;

	$insertSQL = $conexion->conn->Replace("SGD_DIR_DRECCIONES", $record, array('RADI_NUME_RADI','SGD_DIR_TIPO'), $autoquote = true);
	switch ($insertSQL)
	{	case 1:	{	//Insercion Exitosa
					$dir_codigo_new = $nextval;
					$newId=true;
				}break;
		case 2:{	//Update Exitoso
					$newId = false;
				}break;
		case 0:{	//Error Transacci�n.
					echo "<span class='etextomenu'>No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES </span><!-- $isql -->";
				}break;
	}
	unset($record);
	$ADODB_COUNTRECS = false;
}
	// ***********************  us2
if($documento_us2)
{
			echo "<hr><br />documento_us2.<br />";

	$sgd_ciu_codigo=0;
    $sgd_oem_codigo=0;
    $sgd_esp_codigo=0;
		$sgd_fun_codigo=0;
  if($tipo_emp_us2==0){
		$sgd_ciu_codigo=$documento_us2;
		$sgdTrd = "1";
	}
	if($tipo_emp_us2==1){
		$sgd_esp_codigo=$documento_us2;
		$sgdTrd = "3";
	}
	if($tipo_emp_us2==2){
		$sgd_oem_codigo=$documento_us2;
		$sgdTrd = "2";
	}
	if($tipo_emp_us2==6){
		$sgd_fun_codigo=$documento_us2;
		$sgdTrd = "4";
	}
	$isql = "select * from sgd_dir_drecciones where radi_nume_radi=$nurad and sgd_dir_tipo=2";
	$rsg=$conexion->query($isql);

    if 	($rsg->EOF)
	{
		//if($newId==true)
			//{
			   $nextval=$conexion->nextId("sec_dir_drecciones");
			//}
			if ($nextval==-1)
			{
				//$db->conn->RollbackTrans();
				echo "<span class='etextomenu'>No se encontr� la secuencia sec_dir_drecciones ";
			}
		echo "<hr><br />Inserta Direccion.<br />";

		$isql = "insert into SGD_DIR_DRECCIONES(SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, DPTO_CODI, MUNI_CODI,
      			id_pais, id_cont, SGD_DOC_FUN, SGD_OEM_CODIGO, SGD_CIU_CODIGO, SGD_ESP_CODI, RADI_NUME_RADI, SGD_SEC_CODIGO,
      			SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_DIR_NOMBRE)
	  			values('$sgdTrd', '$grbNombresUs2', '$cc_documento_us2', $dpto_tmp2, $muni_tmp2, $idpais2, $idcont2,
	  			$sgd_fun_codigo, $sgd_oem_codigo, $sgd_ciu_codigo, $sgd_esp_codigo, $nurad, 0,'".trim($direccion_us2).
	  			"', '".trim($telefono_us2)."', '$mail_us2', 2, $nextval, '$otro_us2')";
   	  $dir_codigo_new = $nextval;
   	  $newId=true;
    }
	 else
	{
	  $newId = false;
		$isql = "update SGD_DIR_DRECCIONES
				set MUNI_CODI=$muni_tmp2, DPTO_CODI=$dpto_tmp2, id_pais=$idpais2, id_cont=$idcont2
				,SGD_OEM_CODIGO=$sgd_oem_codigo
				,SGD_CIU_CODIGO=$sgd_ciu_codigo
				,SGD_ESP_CODI=$sgd_esp_codigo
				,SGD_DOC_FUN=$sgd_fun_codigo
				,SGD_SEC_CODIGO=0
				,SGD_DIR_DIRECCION='$direccion_us2'
				,SGD_DIR_TELEFONO='$telefono_us2'
				,SGD_DIR_MAIL='$mail_us2'
				,SGD_DIR_NOMBRE='$otro_us2'
				,SGD_DIR_NOMREMDES='$grbNombresUs2'
				,SGD_DIR_DOC='$cc_documento_us2'
				,SGD_TRD_CODIGO='$sgdTrd'
			 	where radi_nume_radi=$nurad and SGD_DIR_TIPO=2 ";
	}

	$rsg=$conexion->query($isql);

	if (!$rsg){
		die ("<span class='etextomenu'>No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES </span><!-- $isql -->");
	}

	}
/* Se insertan usuarios a los que se les envia copia de documentos....
   7 es el numero de tipo de cumento para los usuario de envio de copias.
   */

if($documento_us1 and $cc){ //die("Paso por CC");
			echo "<hr><br />documento_us1 y CC.<br />";

	$sgd_ciu_codigo=0;
	$sgd_oem_codigo=0;
	$sgd_esp_codigo=0;
	$sgd_fun_codigo=0;

	echo "--$sgd_emp_us1--";
	  if($tipo_emp_us1==0){
		$sgd_ciu_codigo=$documento_us1;
		$sgdTrd = "1";
	}
	if($tipo_emp_us1==1){
		$sgd_esp_codigo=$documento_us1;
		$sgdTrd = "3";
	}
	if($tipo_emp_us1==2){
		$sgd_oem_codigo=$documento_us1;
		$sgdTrd = "2";
	}
	if($tipo_emp_us1==6){
		$sgd_fun_codigo=$documento_us1;
		$sgdTrd = "4";
	}
	if($newId==true)
		{
		   $nextval=$conexion->nextId("sec_dir_drecciones");
		}
		if ($nextval==-1)
		{
			//$db->conn->RollbackTrans();
			echo "<span class='etextomenu'>No se encontrasena la secuencia sec_dir_drecciones ";
		}
  $num_anexos=$num_anexos+1;
  $str_num_anexos = substr("00$num_anexos",-2);
  $sgd_dir_tipo = "7$str_num_anexos" ;
	$isql = "insert into SGD_DIR_DRECCIONES (SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, MUNI_CODI, DPTO_CODI,
			id_pais, id_cont, SGD_DOC_FUN, SGD_OEM_CODIGO, SGD_CIU_CODIGO, SGD_ESP_CODI, RADI_NUME_RADI, SGD_SEC_CODIGO,
			SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_ANEX_CODIGO, SGD_DIR_NOMBRE) ";
	$isql .= "values ('$sgdTrd', '$grbNombresUs1', '$cc_documento_us1', $muni_tmp1, $dpto_tmp1, $idpais1, $idcont1,
						$sgd_fun_codigo, $sgd_oem_codigo, $sgd_ciu_codigo, $sgd_esp_codigo, $nurad, 0, '$direccion_us1',
						'".trim($telefono_us1)."', '$mail_us1', $sgd_dir_tipo, $nextval, '$codigo', '$otro_us7' )";
  $dir_codigo_new = $nextval;
  $nextval++;
  $rsg=$conexion->query($isql);

	if (!$rsg)
	{
		//$conexion->conn->RollbackTrans();
		echo "<span class='etextomenu'>No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES </span><!-- $isql -->";
	}
}

//Graba para otros dest.
/*
if( $muni_us7 ){ //die("Paso por CC");
			echo "<hr><br />Graba dir por MUNI7: $muni_us7<br />";
//	die("vamos bien");
	$sgd_ciu_codigo=0;
	$sgd_oem_codigo=0;
	$sgd_esp_codigo=0;
	$sgd_fun_codigo=0;

	echo "--$sgd_emp_us1--";
	  if($tipo_emp_us1==0){
		$sgd_ciu_codigo=$documento_us1;
		$sgdTrd = "1";
	}
	if($tipo_emp_us1==1){
		$sgd_esp_codigo=$documento_us1;
		$sgdTrd = "3";
	}
	if($tipo_emp_us1==2){
		$sgd_oem_codigo=$documento_us1;
		$sgdTrd = "2";
	}
	if($tipo_emp_us1==6){
		$sgd_fun_codigo=$documento_us1;
		$sgdTrd = "4";
	}
	if($newId==true)
		{
		   $nextval=$conexion->nextId("sec_dir_drecciones");
		}
		if ($nextval==-1)
		{
			//$db->conn->RollbackTrans();
			echo "<span class='etextomenu'>No se encontrasena la secuencia sec_dir_drecciones ";
		}
  $num_anexos=$num_anexos+1;
  $str_num_anexos = substr("00$num_anexos",-2);
  $sgd_dir_tipo = "7$str_num_anexos" ;

	$isql = "insert into SGD_DIR_DRECCIONES (SGD_TRD_CODIGO, SGD_DIR_NOMREMDES, SGD_DIR_DOC, MUNI_CODI, DPTO_CODI,
			id_pais, id_cont, SGD_DOC_FUN, SGD_OEM_CODIGO, SGD_CIU_CODIGO, SGD_ESP_CODI, RADI_NUME_RADI, SGD_SEC_CODIGO,
			SGD_DIR_DIRECCION, SGD_DIR_TELEFONO, SGD_DIR_MAIL, SGD_DIR_TIPO, SGD_DIR_CODIGO, SGD_ANEX_CODIGO, SGD_DIR_NOMBRE) ";
	$isql .= "values ('$sgdTrd', '$grbNombresUs1', '$cc_documento_us1', $muni_tmp1, $dpto_tmp1, $idpais1, $idcont1,
						$sgd_fun_codigo, $sgd_oem_codigo, $sgd_ciu_codigo, $sgd_esp_codigo, $nurad, 0, '$direccion_us1',
						'".trim($telefono_us1)."', '$mail_us1', $sgd_dir_tipo, $nextval, '$codigo', '$otro_us7' )";
  $dir_codigo_new = $nextval;
  $nextval++;
  $rsg=$conexion->query($isql);

	if (!$rsg)
	{
		//$conexion->conn->RollbackTrans();
		echo "<span class='etextomenu'>No se ha podido actualizar la informacion de SGD_DIR_DRECCIONES </span><!-- $isql -->";
	}
}*/

//FIN graba para otros dest.
//die("Fin Graba direcciones")
// Fin de inserci�n de copias....
?>