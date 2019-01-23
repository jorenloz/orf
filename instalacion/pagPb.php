<?php
session_start();
// Modificado SGD 20-Septiembre-2007
/**
  * Paggina Cuerpo.php que muestra el contenido de las Carpetas
	* Creado en la SSPD en el año 2003
  * 
	* Se añadio compatibilidad con variables globales en Off
  * @autor Jairo Losada 2009-05
  * @licencia GNU/GPL V 3
  */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$nomcarpeta=$_GET["nomcarpeta"];
if($_GET["tipo_carp"])  $tipo_carp = $_GET["tipo_carp"];

define('ADODB_ASSOC_CASE', 2);
?>
<html>
<head>
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<body bgcolor="#FFFFFF" topmargin="0" >
<form action="qsql.php" method=post>

<?

$isql = "SELECT * FROM USUARIO WHERE depe_codi_nueva >=10";

	if($isql){
	include_once "../include/db/ConnectionHandler.php";
	require_once("../class_control/Mensaje.php");
	if (!$db) $db = new ConnectionHandler("..");
  $db->conn->debug = true;
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$rs = $db->conn->query($isql);
	if(!$rs->EOF){

	  while (!$rs->EOF)	{
			 
		 $depeActu = $rs->fields["depe_codi"];	
		 $usuaDoc = $rs->fields["usua_doc"];	
		 $usuaActu = $rs->fields["usua_codi"];	
     $depeNueva = $rs->fields["depe_codi_nueva"];	
		 if($rs->fields["jefe"]=='1')  $usuNuevo = 1; else $usuNuevo = $usuaActu ;			 
			
		 $sql1 = "UPDATE RADICADO SET RADI_DEPE_ACTU=$depeNueva, RADI_USUA_ACTU=$usuNuevo WHERE RADI_DEPE_ACTU=$depeActu AND RADI_USUA_ACTU=$usuaActu;";
     $sql2 = "UPDATE INFORMADOS SET DEPE_CODI=$depeNueva,USUA_CODI=$usuNuevo WHERE DEPE_CODI=$depeActu AND USUA_CODI=$usuaActu;";
     $sql3 = "UPDATE CARPETA_PER SET DEPE_CODI =$depeNueva,USUA_CODI=$usuNuevo WHERE DEPE_CODI=$depeActu AND USUA_CODI=$usuaActu;";
	   $sql4 = "update usuario set depe_codi=$depeNueva,USUA_CODI=$usuNuevo WHERE DEPE_CODI=$depeActu AND USUA_CODI=$usuaActu;";	
			
		 echo " --  Cedula $usuaDoc \n
		        $sql1 \n $sql2 \n $sql3 \n $sql4\n\n\n  "	;
		 $rs->MoveNext();	
		}
		
		
	}
	}
	?>
</form>
</body>
</html>
