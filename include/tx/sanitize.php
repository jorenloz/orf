<?php

/** Funcion qeu permite evitar la inyecccion de codigo en sql al sanear las 
  * Variables con esta funcion.
  */

include $ruta_raiz."/include/tx/classSanitize.php";


$sanitize = new classSanitize();

foreach ($_GET as $key => $valor)  {
	if (is_array($valor)){
		foreach ($valor as $key_ => $valor_) {
			$_GET[$key][$key_] = $sanitize->noSql($valor_);
		}
	}else{
		$_GET[$key] = $sanitize->noSql($valor);
	}
}
foreach ($_POST as $key => $valor) {
	if (is_array($valor)){
		foreach ($valor as $key_ => $valor_) {
			$_POST[$key][$key_] = $sanitize->noSql($valor_);
		}
	}else{
		$_POST[$key] = $sanitize->noSql($valor);
	}
}

?>
