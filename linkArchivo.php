<?php
 /*
  * Invocado por una funcion javascript (funlinkArchivo(numrad,rutaRaiz))
  * Consulta el path del radicado 
  * @author Liliana Gomez Velasquez
  * @since 5 de noviembre de 2009
  * @category imagenes
 */
error_reporting(0);
session_start();
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd                = $_SESSION["krd"];
$dependencia        = $_SESSION["dependencia"];
$ln          = $_SESSION["digitosDependencia"];
$digitos_totales = 11 + $ln;
if (!$ruta_raiz) $ruta_raiz = ".";

if (isset($db)) unset($db);
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode( ADODB_FETCH_ASSOC );
include_once "$ruta_raiz/tx/verLinkArchivo.php";
//$db->conn->debug = true;
$verLinkArchivo = new verLinkArchivo($db);
$tipoDoc ="Radicado";

//echo strlen( $numrad)."  <= $digitos_totales";

if (strlen( $numrad) <= $digitos_totales){

  $resulVali = $verLinkArchivo->valPermisoRadi($numrad);
  $verImg = $resulVali['verImg'];
  $pathImagen = $resulVali['pathImagen'];

 if(!$pathImagen){
   include_once "./htmlheader.inc.php";
   $htmlMsg = '<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="glyphicon glyphicon-info-sign"></span> <strong>Upps !</strong> Este ('.$tipoDoc.') no tiene Imagen Asociada.
  </div>';
   die($htmlMsg);  
 }

 $seguridadRadicado =  $_SESSION['seguridadradicado'] ;
 unset($_SESSION['seguridadradicado'] );
 
 
  if(substr($pathImagen,0,9) == "../bodega") {
  	$pathImagen=str_replace('../bodega','./bodega',$pathImagen);
  	$file = $pathImagen;
  }elseif(substr($pathImagen,0,12) == "../../bodega") {
    $pathImagen=str_replace('../../bodega','./bodega',$pathImagen);
  	$file = $pathImagen;
  }
  	else {
  		$file = $ruta_raiz. "/bodega/".$pathImagen;
  }	
}else {
  $tipoDoc ="Anexo"; 
  //Se trata de un anexo	
  
  $noCache = "?dateNow=".date("ymd_his");
  $noCache = "";
  //$db->conn->debug = true;
  $resulValiA = $verLinkArchivo->valPermisoAnex($numrad);
  $verImg = $resulValiA['verImg'];
  $pathImagen = $resulValiA['pathImagen'];  
  
  
  
  if(!$pathImagen) $pathImagen=$numrad;

   if(substr(trim($numrad),0,1)==1){
      $file = "bodega/".substr(trim($numrad),1,4)."/".substr(trim($numrad),5,$ln)."/docs/".trim($pathImagen)."$noCache";
    }else{
     $file = "$ruta_raiz/bodega/".substr(trim($numrad),0,4)."/".substr(trim($numrad),4,$ln)."/docs/".trim($pathImagen)."$noCache"; 
   }
  }
//header("location: $file");   // caso de ver imagen sin Seguridad. !!!!!
$fileArchi = $file;
$tmpExt = explode('.',$pathImagen);
$es_pdf = ($tmpExt[1] == 'pdf')? 'pdf' : null;
$filedatatype = ($es_pdf)? $es_pdf : $pathImagen;

// Si se tiene una extension 
if(count($tmpExt)>1){
   //$filedatatype =  $tmpExt[count($tmpExt)-1];
   $filedatatype = array_pop($tmpExt);
}

if($verImg=="SI" or $_SESSION["nivelus"]==5 or $seguridadRadicado == 0 ){

// //echo $fileArchi;
//var_dump(file_exists($fileA));


if (file_exists($fileArchi)) {
  header('Content-Description: File Transfer');
  //
  switch($filedatatype) {
    case 'odt':
        header('Content-Type: application/vnd.oasis.opendocument.text');
        break;
    case 'doc':
    //case 'docx':
          header('Content-Type: application/msword');
          break;
    case 'docx':
          //header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
          header('Content-Type: application/msword');
    case 'tif':
          header('Content-Type: image/TIFF');
          break;
    case 'pdf':
          header('Content-Type: application/pdf');
          break;  
    case 'xls':
    //case 'xlsx':
          header('Content-Type: application/vnd.ms-excel');
          break;
    case 'csv':
          header('Content-Type: application/vnd.ms-excel');
          break;
    case 'ods':
          header('Content-Type: application/vnd.ms-excel');
          break;  
    case 'html':
          header('Content-Type: text/html');
          //$file=utf8_encode($file);
          break;
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        break;
    case 'png':
        header('Content-Type: image/png');
        break;http://hitnu2/orfeo/
    case 'gif':
        header('Content-Type: image/gif');
        break;			
    default :
    header('Content-Type: application/octet-stream');
  break;  
  }
         
		if ($filedatatype == 'html') {
			header('Content-Disposition: inline; filename='.basename($file));
		}else{
			header('Content-Disposition: attachment; filename='.basename($file));
    }

		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
}else {
	$msn="NO se encontro el Archivo. ";
	//die ("<B><center>  NO se encontro el Archivo  </a><br>");
}


  }elseif($verImg == "NO"){ 
	$msn= "NO tiene permiso para acceder al Archivo. ";
  
  	//die ("<B><CENTER>  NO tiene permiso para acceder al Archivo </a><br>");
  }
else{
	$msn="NO se ha podido encontrar informacion del Documento. ";

    //die ("<B><CENTER>  NO se ha podido encontrar informacion del Documento</a><br>"); 
}
if ($msn){
    include_once "./htmlheader.inc.php";
    die ('<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <span class="glyphicon glyphicon-info-sign"></span> <strong>Upps !</strong> '.$msn.' ('.$tipoDoc.') </div>');
    }
?>

