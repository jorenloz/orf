<?php

//register_shutdown_function('onDie');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//date_default_timezone_set('America/Bogota');
$dataType = $_POST["type"]; // i si es instalación; c si es configuración de la base de datos solamente; 'o' es para solo configurar orfeo


if($dataType=="i" || $dataType=="c"){
    $dataName = $_POST["dbNm"]; //nombre de la base de datos
    $dataSrv = $_POST["dbSrv"]; //direccion de la base de datos
    $dataSrvPort = $_POST["dbSrvP"]; //puerto del servidor de base de datos
    $dataUser = $_POST["user"]; //Nombre de usuario para acceder a la base de datos
    $dataPass = $_POST["pass"]; //Contraseña para acceder a la base de datos
}else if($dataType=="o"){
    $dataEnv = $_POST["envOr"];
    $dataTheme = $_POST["theme"];
    $dataEntidad = $_POST["entidad"];
    $dataEntidadNombre = $_POST["entidadC"];
    $dataEntidadTel = $_POST["entidadT"];
    $dataEntidadD = $_POST["entidadD"];
}

function abrir($dh,$dbp,$dbn,$du,$dp){
    try{
        $mypdo = new PDO("pgsql:host=$dh; port=$dbp; dbname=$dbn","$du","$dp");
    }catch(PDOException $e){
        print "0 Error!: " . $e->getMessage();
        die();
    }catch(Exception $e){
        print "0 Error!: " . $e->getMessage();
        die();
    }
    return $mypdo;
}

function configBD($dh,$dbp,$dbn,$du,$dp){
    $archivoConf = "../config.php";
    $str = file_get_contents($archivoConf);
    $strEnd = $str;
    $PATHO=substr(realpath(dirname(__FILE__)),0,-12);
    $reemplazar= "/ABSOL_PATH *= .*/";
    $reemplazo = "ABSOL_PATH = \"$PATHO/\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar= "/servicio *= .*/";
    $reemplazo = "servicio = \"$dbn\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/usuario *= .*/";
    $reemplazo = "usuario = \"$du\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/contrasena *= .*/";
    $reemplazo = "contrasena = \"$dp\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/servidor *= .*/";
    $reemplazo = "servidor = \"$dh:$dbp\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    //echo "$str";
    $error=file_put_contents($archivoConf,$str);
    if(!$error){
        return false;
    }else{
        return $strEnd;
    }
}
function configEntidadOrfeo($env,$theme,$entidad,$entidadC,$entidadT,$entidadD){

    $archivoConf = "../config.php";
    $str = file_get_contents($archivoConf);
    $strEnd = $str;
    $reemplazar= "/ambiente *= .*/";
    $reemplazo = "ambiente = \"$env\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar= "/theme *= .*/";
    $reemplazo = "theme = \"$theme\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/entidad *= .*/";
    $reemplazo = "entidad = \"$entidad\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/entidad_largo *= .*/";
    $reemplazo = "entidad_largo = \"$entidadC\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/entidad_tel *= .*/";
    $reemplazo = "entidad_tel = \"$entidadT\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    $reemplazar = "/entidad_dir *= .*/";
    $reemplazo = "entidad_dir = \"$entidadD\";";
    $str = preg_replace($reemplazar,$reemplazo,$str);
    //echo "$str";
    $error=file_put_contents($archivoConf,$str);
    if(!$error){
        return false;
    }else{
        return $strEnd;
    }
}
function getThemes(){
    $themes = scandir("../themes");
    $dirs = array();
    foreach($themes as $theme){
        if(is_dir("../themes/".$theme) && $theme != '.' && $theme != '..'){
            $dirs[$theme]=$theme;
        }
    };
    return $dirs;
}
//echo "$dataSrv $dataName $dataUser $dataPass";
//echo realpath(dirname(__FILE__));


if($dataType === "i"){
    $ruta_raiz = "..";
    include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");
    $falla = configBD($dataSrv,$dataSrvPort,"postgres",$dataUser,$dataPass);
    if(!$falla){
        echo "0 no se pudo modificar el archivo config.php revise permisos.";
        die();
    }
    try{
        $db = new ConnectionHandler($ruta_raiz);
    }catch(Exception $e){
        echo "0 error base de datos prueba";
    }
    // if(!$db){
    //     echo "1 Error: Al conectarse con el motor de base de datos antes de crear la base de datos $dataName";
    //     die();
    // }
    $db->conn->debug = false;
    $sql = "CREATE DATABASE $dataName;";
    //    $exect = $db->conn->Execute($sql);
    $exect = $db->query($sql);
    if(!$exect){
        echo "0 Error : No se pudo crear la base de datos";
        file_put_contents("../config.php",$falla);
        die();
    }
    $db = null;
    $falla = configBD($dataSrv,$dataSrvPort,$dataName,$dataUser,$dataPass);
    if(!$falla){
        echo "0 Error : no se pudo modificar el archivo config.php revise permisos.";
        die();
    }
    $db = new ConnectionHandler($ruta_raiz);
    if(!$db){
        echo "0 Error: Al conectarse con la base de datos $dataName";
        file_put_contents("../config.php",$falla);
        die();
    }
    $db->conn->debug = false;
    $salida = scandir('./bd');
    $salida ="bd/".$salida[2];
    if(!is_readable($salida)){
        echo "0 Error : El archivo no es encuentra o no tiene permisos de lectura el usuario apache, verifiquelo en el directorio instalacion/bd$salida";
        die();
    }
    $scriptFile = file_get_contents($salida);
    if(!$scriptFile){
        echo "0 Error: No se encontro script de la base de datos";
        die();
    }
    //    $exect = $db->conn->Execute($scriptFile);
    $exect = $db->query($scriptFile);
    if(!$exect){
        echo "0 Error: No se puedo ejecutar el script $salida en al base de datos";
        file_put_contents("../config.php",$falla);
        die();
    }else{
        echo "1 La base de datos se creo satisfactoriamente con el script $salida";
    }
}
if($dataType === "c"){
    $mypdo = abrir($dataSrv,$dataSrvPort,$dataName,$dataUser,$dataPass);
    $mypdo = null;
    $error = configBD($dataSrv,$dataSrvPort,$dataName,$dataUser,$dataPass,false);
    if(!$error){
        echo "0 Error! : No se pudo  modificar el archivo config.php para configurar la base de datos con Orfeo";
        die();
    }else{
        echo "1 Se configuro la base de datos con Orfeo satisfactoriamente, en el archivo config.php.";
    }
}
if($dataType == "o"){
    $error = configEntidadOrfeo($dataEnv,$dataTheme,$dataEntidad,$dataEntidadNombre,$dataEntidadTel,$dataEntidadD);    
    if(!$error){
        echo "0 Error! : No se pudo  modificar el archivo config.php para configurar la infomación de la entidad con Orfeo";
        die();
    }else{
        echo "1 Se configuro la información de la entidad con Orfeo satisfactoriamente, en el archivo config.php.";
    }
}
if(isset($_GET["themes"]) && $_GET["themes"]){
    $data =getThemes();
    header('Content-Type: application/json');
    echo json_encode($data);
}
/*$librerias = $_POST["librerias"];
   $intalar = "sudo apt-get install ";
   $test = "sudo  -u $userName  ls / ";
   echo $test;
   /*foreach($librerias as $libr){
   $test=$test." php$libr";
   }
   $out=array();
   $salida =exec("echo $userPass | sudo echo 'Es un ejemplo'>textTextTest.txt",$out,$r);
   $sout="";
   foreach($out as $libr){
   $sout=$sout." $libr";
   }
   echo "<pre>$salida</pre> $sout lll $r"; */

?>
