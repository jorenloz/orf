<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

OrfeoGpl - Argo Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
error_reporting(7);
$ruta_raiz = "..";
include '../config.php';
include '../include/db/ConnectionHandler.php';
include '../include/tx/Tx.php';
if (!$_SESSION['dependencia'])  header ("Location: $ruta_raiz/cerrar_session.php");


$arrRadicados  = $_POST["arrRadicados"];
$dependenciaDestino   = $_POST["dependencia"];
$codigoUsuario = $_POST["codigoUsuario"];
$comentario    = $_POST["comentario"];
$krd           = $_SESSION["krd"];
  
//header('Content-Type: application/json');
$db = new ConnectionHandler("$ruta_raiz");
//$db->conn->debug = true;

$tx = new Tx($db);

if($_POST['radicado']){
  // Si llega esta variable se informa dese Radicacion.
  $observa  = "($krd) informa desde radicaci&oacute;n";
  $radicado = array($_POST['radicado']);
  $usuarios = $_POST['addUser'];
}
if($arrRadicados){
  // $arrRadicados indica qla radicacion llego desde informar normal por las bandejas.
  $arrRadicados .=",";
  $radicado = explode(",",$arrRadicados); 
  $observa = $comentario;
  $usuarios = array();
  $usuarios[] = $dependenciaDestino."_".$codigoUsuario;
}  

$dependencia   = $_SESSION["dependencia"];
$codusuario    = $_SESSION["codusuario"];
$krd           = $_SESSION["krd"];
$usua_doc       = $_SESSION['usua_doc'];

$nombTx        = "Informar Documentos";
if($enviarMailMovimientos==1) $sendMail = true; else $sendMail = false; 

if(is_array($usuarios)){
   while (list(,$var)=each($usuarios)) {
       $data     = explode("_", $var);
       $result[] = $tx->informar($radicado, $krd, $data[0], $dependencia, $data[1], $codusuario, $observa, $usua_doc,'', 0,$sendMail);
   }
}

if(!empty($result)){
    echo json_encode( array( "true"  => 'Se informo a los usuarios seleccionados'));
}

?>