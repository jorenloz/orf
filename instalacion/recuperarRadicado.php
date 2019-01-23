<?php

session_start();
define('ADODB_ASSOC_CASE', 1);

include_once "../include/db/ConnectionHandler.php";
require_once("../class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler("..");
$db->conn->debug = false;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//recuperamos con defecto.
		
		//Consulta en donde traigo todos los radicados que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "select radi_nume_radi, radi_fech_radi, radi_nume_deri from radicado 
      where radi_nume_radi not in (select radi_nume_radi from sgd_dir_drecciones ) order by radi_fech_radi desc"; 


		$rs = $db->conn->Execute($sqli);
		$i=0;
		//Creo un arreglo con los radicados que no aparecen en SGD_DIR_DRECCIONES
		$arreglo_de_radicados = array();

		while(!$rs->EOF){
			$i=$i+1;
			$numeroRadicado = $rs->fields["RADI_NUME_RADI"]; 
			$arreglo_de_radicados[$i] = $rs->fields["RADI_NUME_RADI"]; 
			$iSql = "select substr(isql, 22,1300) isql from sgd_auditoria 
                                 where isql ilike '%$numeroRadicado%' and isql ilike '%drecciones%' and   isql not ilike '%delete%' limit 1";
		  	$rsRad = $db->conn->Execute($iSql);	
			$consulta = $rsRad->fields["ISQL"]; 
                        $consulta = str_replace("^", "'",$consulta);
                        $consulta = str_replace("''", "0",$consulta);
                        $db->conn->debug= true; 
			$rsUpdate = $db->conn->Execute($consulta);
                        echo "$numeroRadicado : $consulta <hr>"; 
			$rs->MoveNext();
			echo "$i";
		}
		
	var_dump($arreglo_de_radicados);
?>
</body>

</html>


