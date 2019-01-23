<?
session_start(); 
/*
 * Lista Subseries documentales
 * @autor Jairo Losada SuperSOlidaria 
 * @fecha 2009/06 Modificacion Variables Globales.
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$ruta_raiz = ".."; 
/**
 * Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
 *
 * @param char $var
 * @return numeric
 */
function return_bytes($val)
{	$val = trim($val);
	$ultimo = strtolower($val{strlen($val)-1});
	switch($ultimo)
	{	// El modificador 'G' se encuentra disponible desde PHP 5.1.0
		case 'g':	$val *= 1024;
		case 'm':	$val *= 1024;
		case 'k':	$val *= 1024;
	}
	return $val;
}

/*  REALIZAR TRANSACCIONES
 *  Este archivo realiza las transacciones de radicados en Orfeo.
 */
?>
<html>
<head>
<title>Realizar Transaccion - Orfeo </title>
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<?
/**
  * Inclusion de archivos para utilizar la libreria ADODB
  *
  */
	include_once "$ruta_raiz/include/db/ConnectionHandler.php";
	include_once("$ruta_raiz/class_control/anexo.php");
	$db = new ConnectionHandler("$ruta_raiz");
//	$db->conn->debug=true;
	$anex       = & new Anexo($db);
 /*
	* Genreamos el encabezado que envia las variable a la paginas siguientes.
	* Por problemas en las sesiones enviamos el usuario.
	* @$encabezado  Incluye las variables que deben enviarse a la singuiente pagina.
	* @$linkPagina  Link en caso de recarga de esta pagina.
	*/
	$encabezado = "".session_name()."=".session_id()."&krd=$krd&depeBuscada=$depeBuscada&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion";

/*  FILTRO DE DATOS
 *  @$setFiltroSelect  Contiene los valores digitados por el usuario separados por coma.
 *  @$filtroSelect Si SetfiltoSelect contiene algunvalor la siguiente rutina realiza el arreglo de la condicion para la consulta a la base de datos y lo almacena en whereFiltro.
 *  @$whereFiltro  Si filtroSelect trae valor la rutina del where para este filtro es almacenado aqui.
 *
 */
if($checkValue)
{
	$num = count($checkValue);
	$i = 0;
	while ($i < $num)
	{
		$record_id = key($checkValue);
		$setFiltroSelect .= $record_id ;
		$radicadosSel[] = $record_id;
		if($i<=($num-2))
		{
			$setFiltroSelect .= ",";
		}
  	next($checkValue);
	$i++;
	}
	if ($radicadosSel)
	{
		$whereFiltro = " and b.radi_nume_radi in($setFiltroSelect)";
	}
}
 if($setFiltroSelect)
 {
		$filtroSelect = $setFiltroSelect;
 }

echo "<hr>$filtroSelect<hr>";
//session_start();

//if (!$dependencia or !$nivelus)  include "./rec_session.php";
$causaAccion = "Agregar Anexo a Radicado";
?>
<body>
<br>
<?
/*
 * Aqui se haya el número de anexo actual.
 */
$anexNumero=$anex->obtenerMaximoNumeroAnexoConCopias($valRadio)+1;
/*
 * Aqui se traen los anexos_tipo
 */
$sql_anexosTipo="select anex_tipo_ext from anexos_tipo";
$rs_anexosTipo=$db->conn->GetArray($sql_anexosTipo);
foreach ($rs_anexosTipo as $item){
	$exts[]=".".$item["ANEX_TIPO_EXT"];
}
/**
 * Aqui se intenta subir el archivo al sitio original
 *
 */
$ruta_raiz = "..";
include ("$ruta_raiz/include/upload/upload_class.php"); //classes is the map where the class file is stored (one above the root)
$max_size = return_bytes(ini_get('upload_max_filesize')); // the max. size for uploading
$my_upload = new file_upload;
$my_upload->language="es";
$my_upload->upload_dir = "$ruta_raiz/bodega/tmp/"; // "files" is the folder for the uploaded files (you have to create this folder)
$my_upload->extensions = $exts;
//$my_upload->extensions = "de"; // use this to switch the messages into an other language (translate first!!!)
$my_upload->max_length_filename = 50; // change this value to fit your field length in your database (standard 100)
$my_upload->rename_file = true;
//ini_set('display_errors',1);
//error_reporting(E_ALL);
if(isset($_POST['Realizar'])) {
$tmpFile = trim($_FILES['file']['name']);
	$anex_codigo=trim($valRadio) . trim(str_pad($anexNumero, 5, "0", STR_PAD_LEFT));
	$newFile = $valRadio."_".trim(str_pad($anexNumero, 5, "0", STR_PAD_LEFT));
	if ($anex->existeAnexo($anex_codigo))
		$newFile = $valRadio."_".trim(str_pad($anexNumero+1, 5, "0", STR_PAD_LEFT));
	//Trick to remove leading zeros from dependencia for the upload dir
	$depe_dir = substr($valRadio,4,$_SESSION['digitosDependencia']);
	$depe_dir += 0;	
	$uploadDir = "$ruta_raiz/bodega/".substr($valRadio,0,4)."/".$depe_dir."/docs/";
	#COGEINSAS WilsonHernandezOrtiz@gmail.com
	#Se actualiza funcion que haya la extencion del fichero. 5 Mayo 2015.
	$fileGrb = substr($valRadio,0,4)."/".$depe_dir."/$valRadio".".".end(explode(".",$tmpFile));
	$my_upload->upload_dir = $uploadDir;

	$my_upload->the_temp_file = $_FILES['file']['tmp_name'];
	$my_upload->the_file = $_FILES['file']['name'];
	$my_upload->http_error = $_FILES['file']['error'];
	$my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
	$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename

	 $newFile;
	//$new_name = (isset($_POST['name'])) ? $_POST['name'] : "";
	$newFile="1".$newFile;	
	if ($my_upload->upload($newFile)) {
		// new name is an additional filename information, use this to rename the uploaded file
		$full_path = $my_upload->upload_dir.$my_upload->file_copy;
		echo $full_path;
		$info = $my_upload->get_uploaded_file_info($full_path);
		// ... or do something like insert the filename to the database
		$anex_hash=hash_file('sha256',$full_path);
	}else
	{

			die("<table class=borde_tab><tr><td class=titulosError>Ocurrio un Error la Fila no fue cargada Correctamente <p>".$my_upload->show_error_string()."<br><blockquote>".nl2br($info)."</blockquote></td></tr></table>");
	}
}

?>
<table cellspace=2 WIDTH=60% id=tb_general align="left" class="borde_tab">
<tr>
	<td colspan="2" class="titulos4">ACCION REQUERIDA --> <?=$causaAccion ?> </td>
</tr>
<tr>
	<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">ACCION REQUERIDA :
</td>
	<td  width="65%" height="25" class="listado2_no_identa">
	<?=strtoupper($causaAccion) ?>
	</td>
</tr>
<tr>
	<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">RADICADOS INVOLUCRADOS :
	</td>
<td  width="65%" height="25" class="listado2_no_identa"><?=$valRadio?>
</td>
</tr>
<tr>
<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">Datos Fila Asociada :
</td>
<td  width="65%" height="25" class="listado2_no_identa">
<?=$info?>
</td>
</tr>
<tr>
<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">FECHA Y HORA :
</td>
<td  width="65%" height="25" class="listado2_no_identa">
<?=date("m-d-Y  H:i:s")?>
</td>
</tr>
<tr>
<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">USUARIO ORIGEN:
</td>
<td  width="65%" height="25" class="listado2_no_identa">
<?=$_SESSION['usua_nomb']?>
</td>
</tr>
<tr>
<td align="right" bgcolor="#CCCCCC" height="25" class="titulos2">DEPENDENCIA ORIGEN:
</td>
<td  width="65%" height="25" class="listado2_no_identa">
<?=$_SESSION['depe_nomb']?>
</td>
</tr>
</table>
<table class="borde_tab">
<tr><td class="titulosError">
<?
$anex->anex_radi_nume=$valRadio;
$anex->anex_nomb_archivo=$tmpFile;
//$anex->anex_tamano=$_FILES['file']['size'];
$anex->anex_tamano="50";
$anex->anex_creador=$krd;
$anex->anex_desc=$observa;
//echo $anex->anexarFilaRadicado(); 
  if($anex->anexarFilaRadicado()!=-1)
  {
	$radicadosSel[] = $valRadio;
	$codTx = 42;	//C�digo de la transacci�n
	include "$ruta_raiz/include/tx/Historico.php";
	/*$isql = "update 
		                 anexos set
		                 anex_hash='$anex_hash'
		                 where 
		                 anex_codigo= '$anex_codigo'";

	$db->conn->query($isql);*/
	$hist = new Historico($db);
	$observa.=" con codigo de seguridad: ".$anex_hash;
	$hist->insertarHistorico($radicadosSel,  $dependencia , $codusuario, $dependencia, $codusuario, $observa, $codTx);
	$isql = "update 
				anexos set
				anex_hash='$anex_hash'
				where 
				anex_codigo= '$anex_codigo'";
			$db->conn->query($isql);

	}else{
  	echo "<hr>No actualizo la BD <hr>";
  }
?>
</td></tr>
</table>
</form>
</body>
</html>

