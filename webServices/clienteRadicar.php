<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

//require_once('../include/nusoap/nusoap.php');

$wsdl="http://localhost/orfeop7/webServices/servidor.php?wsdl"; 
echo "Llego aka";
$client=new nusoap_client($wsdl, true);  
$client->debug = true;
echo "Llego aka";

$arregloDatos = array();
/**
$arregloDatos[0] = "0";                 
//$arregloDatos[1] = "0";                 
$arregloDatos[2] = "jlosada@gmail.com";
$arregloDatos[3] = "79802120";
$arregloDatos[4] = "JairoP";
$arregloDatos[5] = "LosadaP";
$arregloDatos[6] = "CardonaP";
$arregloDatos[7] = "";
$arregloDatos[8] = "Cra 13 no 54 67 dd";
$arregloDatos[9] = "jlosada@correlibre.org";
$arregloDatos[10] = "0";
$arregloDatos[11] = "1";
$arregloDatos[12] = "170";
$arregloDatos[13] = "11";
$arregloDatos[14] = "1";
$arregloDatos[15] = "Asunto..Probando la radicadion por ws";
$arregloDatos[16] = "1";
$arregloDatos[17] = "5";
$arregloDatos[18] = "900";
$arregloDatos[19] = "0";
$arregloDatos[20] = "666666666";
$arregloDatos[21] = "1";
$arregloDatos[22] = "1";
$arregloDatos[23] = "0";
$arregloDatos[24] = "1";
$arregloDatos[25] = "0";
$arregloDatos[26] = "0";
$arregloDatos[27] = "17d7d7d"; */

$arregloDatos[] = "0";                 
$arregloDatos[] = "0";                 
$arregloDatos[] = "jlosada@gmail.com";
$arregloDatos[] = "79802120";
$arregloDatos[] = "JairoP";
$arregloDatos[] = "LosadaP";
$arregloDatos[] = "CardonaP";
$arregloDatos[] = "";
$arregloDatos[] = "Cra 13 no 54 67 dd";
$arregloDatos[] = "jlosada@correlibre.org";
$arregloDatos[] = "0";
$arregloDatos[] = "1";
$arregloDatos[] = "170";
$arregloDatos[] = "11";
$arregloDatos[] = "1";
$arregloDatos[] = "Asunto..Probando la radic";
$arregloDatos[] = "1";
$arregloDatos[] = "5";
$arregloDatos[] = "900";
$arregloDatos[] = "0";
$arregloDatos[] = "666666";
$arregloDatos[] = "1";
$arregloDatos[] = "1";
$arregloDatos[] = "0";
$arregloDatos[] = "1";
$arregloDatos[] = "0";
$arregloDatos[] = "0";
$arregloDatos[] = "17d7d7d";
$a = $client->call('radicarDocumento',$arregloDatos);

//$a = $client->call('consultaServicios', array($arregloDatos));
echo "Probando Salida";
var_dump($a);



?>



