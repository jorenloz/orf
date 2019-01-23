<?php
/**
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

    session_start();

    $ruta_raiz = "..";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");
        
    include '../include/db/ConnectionHandler.php';    
    include_once("$ruta_raiz/include/tx/Tx.php");
    $db = new ConnectionHandler($ruta_raiz);
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

    $radicados   = array_filter(explode(",", $_POST["arrRadicados"]));
    $codusuario  = $_SESSION["codusuario"];
    $dependencia = $_SESSION["dependencia"];
    $depeCodiBorrar = $dependencia;//$_POST["depeCodiBorrar"];
    $usuaCodiBorrar = $codusuario;//$_POST["usuaCodiBorrar"];
    $radicados = explode(",",$_POST["rads"]);
	for ($i=0;$i<count($radicados);$i++){
		if (empty($radicados[$i])){
			unset($radicados[$i]);
		}
	}
    
    //$db->conn->debug = true;
  

    

    $tx      = new Tx($db);
    $observa = $_POST["observa"];
    //$observa = "Eliminaci&oacute;n informado";
    
    $radicados = explode(",",$_POST["arrRadicados"]);
	for ($i=0;$i<count($radicados);$i++){
		if (empty($radicados[$i])){
			unset($radicados[$i]);
		}
	}
    $infdel  = $tx->borrarInformado($radicados, $_SESSION["krd"], $dependencia, $depeCodiBorrar, $codusuario, $usuaCodiBorrar,  $observa);
    $msginf  = implode(",", $infdel);
    
    if(!empty($infdel)){
        echo "<div class='alert alert-success fade in'>
                <button class='close' data-dismiss='alert'> × </button>
                <i class='fa-fw fa fa-check'></i>
                <strong>Borrar informados</strong>
                Se borraron los radicados $msginf de informados.
                <span id=refresh class='btn btn-ribbon' data-reset-msg='Recargar Pagina' data-html='true' rel='tooltip' data-title='refresh' data-action='resetWidgets'>
            </div>";
    } else {
        echo "<div class='alert alert-danger fade in'>
                <button class='close' data-dismiss='alert'> × </button>
                <i class='fa-fw fa fa-times'></i>
                <strong>Error al borrar los informados.</strong>
                Radicados $msginf
            </div>";
    }
?>
