<?php
/**********************************************************************************
Diseno de un Web Service que permita la interconexion de aplicaciones con Orfeo
**********************************************************************************/

/**
 * @author German Mahecha
 * @author William Duarte (modificacion del archivo original y adicion de funcionalidad)
 * @author Donaldo Jinete Forero
 * @author JAIRO LOSADA, YENY BETANCUR     Adaptacion php7.
 */

//Llamado a la clase nusoap

$ruta_raiz = "..";
define('RUTA_RAIZ','../');

require_once $ruta_raiz."/include/nusoap/nusoap.php";
include_once $ruta_raiz."/config.php";
include_once $ruta_raiz."/include/db/ConnectionHandler.php";
//require_once $ruta_raiz."/include/fpdf/fpdf.php";

//Asignacion del namespace
$ns="webServices/nusoap";
$ns="urn:nusoap";

//Creacion del objeto soap_server
$server = new soap_server();

$server->configureWSDL('Sistema de Gestion Documental Orfeo-Internas',$ns);

/*********************************************************************************
Se registran los servicios que se van a ofrecer, el metodo register tiene los sigientes parametros
**********************************************************************************/

//Servicio de transferir archivo

$server->register('UploadFile',  									 //nombre del servicio
    array('bytes' => 'xsd:base64Binary', 'filename' => 'xsd:string', 'key'	=> 'xsd:string'),//entradas
    array('return' => 'xsd:string'),   								 // salidas
    $ns,                         									 //Elemento namespace para el metodo
    $ns . '#UploadFile',   											 //Soapaction para el metodo
    'rpc',                 											 //Estilo
  	'encoded',
    'Upload a File'
);

$server->register('crearAnexo',  								//nombre del servicio
    array('radiNume' => 'xsd:string',									//numero de radicado
     'file' => 'xsd:base64Binary',										//archivo en base 64
     'filename' => 'xsd:string',										//nombre original del archivo
     'correo' => 'xsd:string',									       //correo electronico
     'descripcion'=>'xsd:string',										//descripcion del anexo
     'key'	=> 'xsd:string',												//Clave del servicio
     ),																//fin parametros del servicio
    array('return' => 'xsd:string'),   								//retorno del servicio
    $ns                     									 	//Elemento namespace para el metod
);

$server->register('insertarTRD',                  //nombre del servicio
    array('codigoSerie' => 'xsd:string',                  //numero de radicado                    //archivo en base 64
     'codigoSubserie' => 'xsd:string',                    //nombre original del archivo
     'tipoDocumental' => 'xsd:string',                         //correo electronico
     'radicado'=>'xsd:string',                    //descripcion del anexo
     'usuario'=>'xsd:string',
     'key'  => 'xsd:string',                        //Clave del servicio
     ),                               //fin parametros del servicio
    array('return' => 'xsd:string'),                  //retorno del servicio
    $ns                                         //Elemento namespace para el metod
);

$server->register('consultarEstadoRad',                  //nombre del servicio
    array('radicado'=>'xsd:string',                    //Numero del radicado
          'key'  => 'xsd:string',                        //Clave del servicio
     ),                               //fin parametros del servicio
    array('return' => 'xsd:string'),                  //retorno del servicio
    $ns                                         //Elemento namespace para el metod
);

//Servicio para crear un expediente
$server->register('crearExpediente',  					//nombre del servicio
    array('nurad' => 'xsd:string',							//numero de radicado
     'usuaDocResponsable' => 'xsd:string',			//Documento usuario que crea y sera el responsable del expediente
     'anoExp' => 'xsd:string',									//ano del expediente
     'fechaExp' => 'xsd:string',								//fecha expediente
     'codiSRD'=>'xsd:string',										//Serie del Expediendte
     'codiSBRD'=>'xsd:string',									//Subserie del expediente
     'key'	=> 'xsd:string',										//Clave del servicio
     'parametro1'=>'xsd:string',                // Parametro del expediente 1
     'parametro2'=>'xsd:string',                // Parametro del expediente 2
     'parametro3'=>'xsd:string',                // Parametro del expediente 3
     'parametro4'=>'xsd:string',                // Parametro del expediente 4
     'parametro5'=>'xsd:string',                // Parametro del expediente 5
     ),																//entradas
    array('return' => 'xsd:string'),   								// salidas
    $ns                     									 	//Elemento namespace para el metod
);

//Servicio que entrega todos los usuarios de Orfeo
/*$server->register('darUsuario',
	array('key'=> 'xsd:string'), //Clave del servicio
	array('return'=>'tns:Matriz'),
	$ns
);*/

// Servicio que realiza una radicacion en Orfeo
$server->register('radicarDocumento',
	array(
		'file' => 'xsd:base64Binary',										//archivo en base 64
		'fileName' => 'xsd:string',
		'correo' => 'xsd:string',
		'dest_cc_documento'=>'xsd:string',
		'dest_nombre'=>'xsd:string',
		'dest_prim_apel'=>'xsd:string',
		'dest_seg_apel'=>'xsd:string',
		'dest_telefono'=>'xsd:string',
		'dest_direccion'=>'xsd:string',
		'dest_mail'=>'xsd:string',
		'dest_otro'=>'xsd:string',
		'dest_idcont'=>'xsd:string',
		'dest_idpais'=>'xsd:string',
		'dest_codep'=>'xsd:string',
		'dest_muni'=>'xsd:string',
		'asu'=>'xsd:string',
		'med'=>'xsd:string',
		'ane'=>'xsd:string',
		'coddepe'=>'xsd:string',
		'tpRadicado'=>'xsd:string',
		'cuentai'=>'xsd:string',
		'usuaDocActu'=>'xsd:string',
		'tip_rem'=>'xsd:string',
		'tipoDocumental'=>'xsd:string',
		'tipoIdentificacion'=>'xsd:string',
		'carp_codi'=>'xsd:string',
		'carp_per'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return' => 'xsd:string'
	),
	$ns,
	$ns."#radicarDocumento",
	"rpc",
  "encoded",
  "Get food by type"
);

$server->register('anexarExpediente',
	array(
		'numRadicado'=>'xsd:string',
		'numExpediente'=>'xsd:string',
		'usuaLogin'=>'xsd:string',
		'observa'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'xsd:string'
	),
	$ns,
	$ns."#anexarExpediente",
	'rpc',
	'encoded',
	'Anexar un radicado a un expediente'
);

$server->register('cambiarImagenRad',
	array(
		'numRadicado'=>'xsd:string',
		'ext'=>'xsd:string',
		'file'=>'xsd:base64Binary',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'xsd:string'
	),
	$ns,
	$ns."#cambiarImagenRad",
	'rpc',
	'encoded',
	'Cambiar imagen a un radicado'
);

$server->register('getInfoUsuario',
	array(
		'usuaLoginMail'=>'xsd:string',
		'key'=>'xsd:string',
		'variableBusqueda'=>'xsd:string'
	),
	array(
		'return'=>'tns:Vector'
	),
	$ns,
	$ns.'#getInfoUsuario',
	'rpc',
	'encoded',
	'Obtener informacion de un usuario a partir del correo electronico, variable busqueda (correo, login, documento)'
);

$server->register('getInfoRadicado',
	array(
		'numRadicado'=>'xsd:string',
		'key'	=> 'xsd:string'
	),
	array(
		'return'=>'xsd:string'
	),
	$ns,
	$ns.'#getInfoRadicado',
	'rpc',
	'encoded',
	'Obtener informacion de un radicado'
);

/**********************************************************************************
Se registran los tipos complejos y/o estructuras de datos
***********************************************************************************/

$server->wsdl->addComplexType(
        'Estructura',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'RADI_NUME_RADI' => array('name' => 'RADI_NUME_RADI', 'type' => 'xsd:string'),
        'RADI_FECH_RADI'=>array('name' => 'RADI_FECH_RADI', 'type' => 'xsd:string'),
        'TDOC_CODI'=>array('name' => 'TDOC_CODI', 'type' => 'xsd:string'),
        'RADI_PATH'=>array('name' => 'RADI_PATH', 'type' => 'xsd:string'),
        'RADI_USUA_ACTU'=>array('name' => 'RADI_USUA_ACTU', 'type' => 'xsd:string'),
        'RADI_DEPE_ACTU'=>array('name' => 'RADI_DEPE_ACTU', 'type' => 'xsd:string'),
        'RADI_USU_ANTE'=>array('name' => 'RADI_USU_ANTE', 'type' => 'xsd:string'),
        'RADI_DEPE_RADI'=>array('name' => 'RADI_DEPE_RADI', 'type' => 'xsd:string'),
        'RA_ASUN'=>array('name' => 'RA_ASUN', 'type' => 'xsd:string'),
        'RADI_USUA_RADI'=>array('name' => 'RADI_USUA_RADI', 'type' => 'xsd:string'),
        'CODI_NIVEL'=>array('name' => 'CODI_NIVEL', 'type' => 'xsd:string'),
        'DEPE_CODI'=>array('name' => 'DEPE_CODI', 'type' => 'xsd:string'),
        'SGD_DIR_CODIGO'=>array('name' => 'SGD_DIR_CODIGO', 'type' => 'xsd:string'),
        'SGD_DIR_TIPO'=>array('name' => 'SGD_DIR_TIPO', 'type' => 'xsd:string'),
        'SGD_OEM_CODIGO'=>array('name' => 'SGD_OEM_CODIGO', 'type' => 'xsd:string'),
        'SGD_CIU_CODIGO'=>array('name' => 'SGD_CIU_CODIGO', 'type' => 'xsd:string'),
        'MUNI_CODI'=>array('name' => 'MUNI_CODI', 'type' => 'xsd:string'),
        'DPTO_CODI'=>array('name' => 'DPTO_CODI', 'type' => 'xsd:string'),
        'ID_PAIS'=>array('name' => 'ID_PAIS', 'type' => 'xsd:string'),
        'SGD_DIR_DIRECCION'=>array('name' => 'SGD_DIR_DIRECCION', 'type' => 'xsd:string'),
        'SGD_DIR_TELEFONO'=>array('name' => 'SGD_DIR_TELEFONO', 'type' => 'xsd:string'),
        'SGD_DIR_MAIL'=>array('name' => 'SGD_DIR_MAIL', 'type' => 'xsd:string'),
        'SGD_DIR_NOMBRE'=>array('name' => 'SGD_DIR_NOMBRE', 'type' => 'xsd:string'),
        'SGD_DIR_NOMREMDES'=>array('name' => 'SGD_DIR_NOMREMDES', 'type' => 'xsd:string'),
        'SGD_DIR_DOC'=>array('name' => 'SGD_DIR_DOC', 'type' => 'xsd:string'))
);

//Adicionando un tipo complejo MATRIZ
$server->wsdl->addComplexType(
    'Matriz',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
	array(),
    array(
    array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Vector[]')
    ),
    'tns:Vector'
);

//Adicionando un tipo complejo VECTOR

$server->wsdl->addComplexType(
    'Vector',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
	array(),
    array(
    array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string[]')
    ),
    'xsd:string'
);

/******************************************************************************
 Servicios  que se ofrecen
******************************************************************************/

/**
*
 * Esta funcion pretende almacenar todos los usuarios de orfeo, con la informacion
 *
 * de correo, cedula, dependencia y codigo del usuario
 *
 * @author German A. Mahecha
 *
 * @return Matriz con todos los usuarios de Orfeo
 *
 */



/*function darUsuario($key){

	global $ruta_raiz, $keyWS;

	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

		$sql = "select DEPE_CODI, USUA_CODI, USUA_DOC, USUA_EMAIL  from usuario";

		$rs = $db->getResult($sql);
		$i =0;
		while (!$rs->EOF){

//				 $usuario[$i]['email'] = $rs->fields['USUA_EMAIL'];
//
//				 $usuario[$i]['codusuario']  = $rs->fields['USUA_CODI'];
//
//				 $usuario[$i]['dependencia'] = $rs->fields['DEPE_CODI'];
//
//				 $usuario[$i]['documento'] =  $rs->fields['USUA_DOC'];
//
//				 $i=$i+1;
//
//				 $rs->MoveNext();
//                                $usu.=$usuario[$i]['email'].'||'.$usuario[$i]['codusuario'].'||'.$usuario[$i]['dependencia'].'||'.$usuario[$i]['documento'].'<<<';
                                  $usuario['email'] = $rs->fields['USUA_EMAIL'];
			 $usuario['codusuario']  = $rs->fields['USUA_CODI'];
			 $usuario['dependencia'] = $rs->fields['DEPE_CODI'];
			 $usuario['documento'] =  $rs->fields['USUA_DOC'];
                         $usu.=$usuario['email'].'||'.$usuario['codusuario'].'||'.$usuario['dependencia'].'||'.$usuario['documento']."<<<";
		}
        $usu=substr($usu,0,strlen($usu)-3);
	return $usu;

	}
	else
	{
		return 'Error, Llave de consulta no valida';
	}
}*/

/**
 *
 * UploadFile es una funcion que permite almacenar cualquier tipo de archivo en el lado del servidor
 *
 * @param $bytes
 *
 * @param $filename es el nombre del archivo con que queremos almacenar en el servidor
 *
 * @author German A. Mahecha
 *
 * @return Retorna un String indicando si la operacion fue satisfactoria o no
 *
 */

function UploadFile($bytes, $filename){
	
	$var = explode(".",$filename);
		//try{
			//direccion donde se quiere guardar los archivos
			
			$path = getPath($filename);
               // die($path);
		$fp = fopen("$path", "w") or die("fallotttttt");
			// decodificamos el archivo
			
			$bytes=base64_decode($bytes);
			$salida=true;
			if( is_array($bytes) ){
				foreach($bytes as $k => $v){
					$salida=($salida && fwrite($fp,$bytes));
				}
			}else{
				$salida=fwrite($fp,$bytes);
			}
			fclose($fp);
		/*}catch (Exception $e){
			return "error";
		}*/
		if ($salida){
	return "exito";
		}else{
		   "error";
		}

}




function insertarTRD($codigoSerie,$codigoSubserie, $tipoDocumental, $radicado , $usuario, $key=""){
if ($key == $keyWS)
  {
  $ruta_raiz="..";
  
  $db = new ConnectionHandler($ruta_raiz);
  
      if($usuario){
        $infoUsuarioActual = getInfoUsuario($usuario,$key,'login');
        if ($infoUsuarioActual!= -1){
          $codigoUsuario = $infoUsuarioActual["usua_codi"];
          $codigoDependencia = $infoUsuarioActual["usua_depe"];
        }else{
          return "-1| El usuario $usuario no existe"; 
        }
      }

        $sql = "SELECT DEPE_CODI, SGD_MRD_CODIGO FROM sgd_mrd_matrird 
            WHERE (DEPE_CODI=$codigoDependencia or depe_codi_aplica like '%$codigoDependencia%') and  sgd_srd_codigo = $codigoSerie
            and sgd_sbrd_codigo= $codigoSubserie and sgd_tpr_codigo = $tipoDocumental"; 
        # Busca el usuairo para luego traer sus datos.
        //$this->db->conn->debug = true;
        
        $rs = $db->conn->query($sql);
        $band=0;
        while(!$rs->EOF){
            $depeCodiMatriz = $rs->fields["DEPE_CODI"];
            if ($depeCodiMatriz== $codigoDependencia and $band==0){
              $codigoMatriz = $rs->fields["SGD_MRD_CODIGO"];
              $band=1;
            }elseif ($band==0){
              $codigoMatriz = $rs->fields["SGD_MRD_CODIGO"];
              $depeCodiMatriz = $rs->fields["DEPE_CODI"];
            }
            
        $rs->MoveNext();
        }
  
      if ($codigoMatriz){
    
        include_once("../include/tx/TipoDocumental.php");
        $db = new ConnectionHandler($ruta_raiz);
        $tDocumental= new TipoDocumental($db);
        $retTDoc = $tDocumental->insertarTRD($codiTRDS, $codigoMatriz, $radicado, $codigoDependencia, $codigoUsuario, $tipoDocumental );
        
      
        
        if (is_numeric($retTDoc)){
        include_once ('../include/tx/Historico.php');
        $db = new ConnectionHandler($ruta_raiz);
          $histo = new Historico($db) ;
          $histDescripcionn= "TRD insertada desde servicio web";
          
          $codTx= 32;
          $radicados[]=$radicado;
          //echo $usuario["usua_depe"]."dependencia del usuario q anexa".$usuario["usua_codi"]."Codigo del usuarop". "-----". $histDescripcion  ;
          $histo->insertarHistorico($radicados,  $codigoDependencia , $codigoUsuario, "NULL", "NULL", $histDescripcionn , $codTx);
          
          return "0|Se realizo la tipificacion";
        }else{
          $retTDoc = strip_tags($retTDoc."' ' ; ");
          
          return "-1|No se realizo la tipificacion ($retTDoc)";
        }
      }else{
        return '-1|Tipificación no valida';
      }
    
  }else {
    return '-1|Error, llave de consulta no valida';
  }

}



function consultarEstadoRad($radicado, $key=""){

if ($key == $keyWS)
  {
  
    $ruta_raiz="..";
  
    $db = new ConnectionHandler($ruta_raiz);
    
    $sql="select ANEX_ESTADO from anexos where radi_nume_salida= $radicado ";
    
    $rs = $db->conn->query($sql);
    
    $anexEstado = $rs->fields["ANEX_ESTADO"];
    
    if ($anexEstado){
      return $anexEstado;
    }else{
      return "0|El radicado no tiene imagen";
    }
  
  }else {
    return '-1|Error, llave de consulta no valida';
  }

}



/**
 *
 * Esta funcion permite obtener el path donde se debe almacenar el archivo
 *
 * @param $filename, el nombre del archivo
 *
 * @author German A. Mahecha
 *
 * @return Retorna el path
 *
 */
function getPath($filename){
	global $ruta_raiz;

	$var = explode(".",$filename);
	$path = $ruta_raiz."/bodega/";
	$path .= substr($var[0],0,4);
	$path .= "/".substr($var[0],4,3);
	//Verificamos si se trata de una imagen o un anexo, si lleva guion bajo el nombre del
	//archivo indica que es un anexo y por lo tanto se almacena dentro de el directorio docs
	//si no lo almacenamos en la carpeta con codigo de la dependencia
	if (strstr($filename,'_'))
	{
		$path .= "/docs/".$filename;
	}
	else
	{
		$path .= "/".$filename;
	}

	return  $path;
}



/**
 *
 * Esta funcion permite crear un expediente a partir de un radicado
 *
 * @param $nurad, este parametro es el numero de radicado
 *
 * @param $usuario, este parametro es el usuario que crea el expediente, es el usuario de correo
 *
 * @author German A. Mahecha
 *
 * @return El numero de expediente para asignarlo en aplicativo de contribuciones AI
 *
 */

function crearExpediente($nurad,$usuaDocResponsable,$anoExp,$fechaExp,$codiSRD,$codiSBRD,$key, $parametro1="", $parametro2="", $parametro3="", $parametro4="", $parametro5=""){
	$digCheck = 'E';
	include_once("../include/tx/Expediente.php");
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$expediente = new Expediente($db);
		//Aqui busco la informacion necesaria del usuario para la creacion de expedientes

		$infoUsuario = getInfoUsuario($usuaDocResponsable,"","documento");
	  $codusuario  = $infoUsuario["usua_codi"]; 
	  $dependencia  = $infoUsuario["usua_depe"];
		$usua_doc =  $infoUsuario["usua_doc"];
		$usuaDocExp = $usua_doc;
		
		$trdExp = substr("00".$codiSRD,-2) . substr("00".$codiSBRD,-2);
		$secExp = $expediente->secExpediente($dependencia,$codiSRD,$codiSBRD,$anoExp);
		$consecutivoExp = substr("00000".$secExp,-5);
		$numeroExpediente = $anoExp . $dependencia . $trdExp . $consecutivoExp . $digCheck;
		if($parametro1) $arrParametros[1]=$parametro1; else $arrParametros[1]="";
		if($parametro2) $arrParametros[2]=$parametro2; else $arrParametros[2]="";
		if($parametro3) $arrParametros[3]=$parametro3; else $arrParametros[3]="";
		if($parametro4) $arrParametros[4]=$parametro4; else $arrParametros[4]="";
		if($parametro5) $arrParametros[5]=$parametro5; else $arrParametros[5]="";

		$numeroExpedienteE = $expediente->crearExpediente( $numeroExpediente,$nurad,$dependencia,$codusuario,$usua_doc,$usuaDocExp,$codiSRD,$codiSBRD,'false',$fechaExp,$codiProc,$arrParametros);



		if($numeroExpedienteE!=0) $insercionExp = $expediente->insertar_expediente( $numeroExpediente,$nurad,$dependencia,$codusuario,$usua_doc);
		
		
		
		$retorno = $numeroExpedienteE;
		if(count($expediente->err)>=1){
		  foreach($expediente->err as $value) {
        $retorno .= "||".$value;
      }
      
		}
		
    return $retorno;
	}
	else
	{
		return '0|Error, llave de consulta no valida';
	}

}

function getUsuarioCorreo($correo,$key){

	global $ruta_raiz,$keyWS;

	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
		           FROM USUARIO WHERE USUA_EMAIL='$correo' AND USUA_ESTA=1";
		$salida=array();
		if(verificarCorreo($correo)){
		$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
		           FROM USUARIO WHERE USUA_EMAIL='".trim($correo)."' AND USUA_ESTA=1";
		 $db = new ConnectionHandler($ruta_raiz);
		 $rs = $db->query($consulta);

		 if (!$rs->EOF){
			 $salida['email'] = $rs->fields['USUA_EMAIL'];
			 $salida['codusuario']  = $rs->fields['USUA_CODI'];
			 $salida['dependencia'] = $rs->fields['DEPE_CODI'];
			 $salida['documento'] =  $rs->fields['USUA_DOC'];
			 $salida['nivel'] = $rs->fields['CODI_NIVEL'];
			 $salida['login'] = $rs->fields['USUA_LOGIN'];
		   } else {
		   	$salida['error']="El ususario no existe o se encuentra deshabilitado";
		   }
		}else{
			$salida["error"]="el mail no corresponde a un email valido";
		}
	}
	else
	{
		$salida["error"]="Llave de consulta no valida";
	}

	return $salida;
}

/**
 *
 * funcion que verifica que un correo electronico cumpla con
 *
 * un patron estandar
 *
 *
 *
 * @param strig $correo correo a verificar
 *
 * @return boolean
 *
 */

function verificarCorreo($correo){
	 $expresion=preg_match("(^\w+([\.-] ?\w+)*@\w+([\.-]?\w+)*(\.\w+)+)",$correo);
	 return $expresion;
}

/**
 *
 * funcion encargada regenerar un archivo enviado en base64
 *
 *
 *
 * @param string $ruta ruta donde se almacenara el archivo
 *
 * @param base64 $archivo archivo codificado en base64
 *
 * @param string $nombre nombre del archivo
 *
 * @return boolean retorna si se pudo decodificar el archivo
 *
 */

function subirArchivo($ruta,$archivo,$nombre){
		//try{
		//direccion donde se quiere guardar los archivos
		$fp = @fopen("{$ruta}{$nombre}", "w");
		$bytes=base64_decode($archivo);

		$salida=true;
		if( is_array($bytes) ){
			foreach($bytes as $k => $v){
				$salida=($salida && fwrite($fp,$bytes));
			}
		}else{
			$salida=fwrite($fp,$bytes);
		}
		fclose($fp);
	/*}catch (Exception $e){
		return "error";
	}*/
	return $salida;
}
/**
 *
 * @param string $correo mail del usuario en orfeo
 * @return array resultado de la consulta;
 */
function getUsuarioCorreo2($correo){
	global $ruta_raiz;
	//$salida="pailas papa";
/*	$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
	           FROM USUARIO WHERE USUA_EMAIL='$correo' AND USUA_ESTA=1";*/
	$salida=array();
	if(verificarCorreo($correo)){
	$consulta="SELECT USUA_LOGIN,DEPE_CODI,USUA_EMAIL,CODI_NIVEL,USUA_CODI,USUA_DOC
	           FROM USUARIO WHERE UPPER(USUA_EMAIL)=UPPER('".trim($correo)."') AND USUA_ESTA=1";
	 $db = new ConnectionHandler($ruta_raiz);
	 $rs = $db->query($consulta);
	 //$rs->_numOfRows > 0
	 if (!$rs->EOF){
		 $salida['email'] = $rs->fields['USUA_EMAIL'];
		 $salida['codusuario']  = $rs->fields['USUA_CODI'];
		 $salida['dependencia'] = $rs->fields['DEPE_CODI'];
		 $salida['documento'] =  $rs->fields['USUA_DOC'];
		 $salida['nivel'] = $rs->fields['CODI_NIVEL'];
		 $salida['login'] = $rs->fields['USUA_LOGIN'];
                 $sal=$salida['email'].'||'.$salida['codusuario'].'||'.$salida['dependencia'].'||'.$salida['documento'].'||'.$salida['nivel'].'||'.$salida['login'];
	   }
           else{
               $salida['error']="El ususario no existe o se encuentra deshabilitado: " . $consulta;
               $sal="El ususario no existe o se encuentra deshabilitado: " . $consulta;
           }
	}else{
		$salida["error"]="el mail no corresponde a un email valido";
                $sal="el mail no corresponde a un email valido";
	}
	
	return $sal;
}
/**
 *
 * funcion que crea un Anexo, y ademas decodifica el anexo enviasdo en base 64
 *
 *
 *
 * @param string $radiNume numero del radicado al cual se adiciona el anexo
 *
 * @param base64 $file archivo codificado en base64
 *
 * @param string $filename nombre original del anexo, con extension
 *
 * @param string $correo correo electronico del usuario que adiciona el anexo
 *
 * @param string $descripcion descripcion del anexo
 *
 * @return string mensaje de error en caso de fallo o el numero del anexo en caso de exito
 *
 */

function crearAnexo($radiNume,$file,$filename,$correo,$descripcion,$key, $soloLectura='n', $anexEstado='0', $generaRadicado='0', $radiSalida='NULL'){

	global $ruta_raiz,$keyWS;
  include_once (RUTA_RAIZ.'include/tx/Historico.php');
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		//$db->conn->debug= true;
		$usuario=getInfoUsuario($correo,$key,'correo');
		//var_dump($usuario);
		$error=(isset($usuario['error']))?true:false;
		$ruta=RUTA_RAIZ."bodega/".substr($radiNume,0,4)."/".substr($radiNume,4,3)."/docs/";
		$numAnexos=numeroAnexos($radiNume,$db)+1;
		$maxAnexos=maxRadicados($radiNume,$db)+1;
		$extension=substr($filename,strrpos($filename,".")+1);
		$numAnexo=($numAnexos > $maxAnexos)?$numAnexos:$maxAnexos;
		$nombreAnexo=$radiNume.substr("00000".$numAnexo,-5);
		$subirArchivo=subirArchivo($ruta,$file,$nombreAnexo.".".$extension);
		$tamanoAnexo = $subirArchivo / 1024; //tamano en kilobytes
		
		$error=($error && !$subirArchivo)?true:false;
		$fechaAnexado= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
		$tipoAnexo=tipoAnexo($extension,$db);
		if(!$error){
			$tipoAnexo=($tipoAnexo)?$tipoAnexo:"NULL";
			$consulta="INSERT INTO ANEXOS (ANEX_CODIGO,ANEX_RADI_NUME,ANEX_TIPO,ANEX_TAMANO,ANEX_SOLO_LECT,ANEX_CREADOR,
			            ANEX_DESC,ANEX_NUMERO,ANEX_NOMB_ARCHIVO,ANEX_ESTADO,SGD_REM_DESTINO,ANEX_FECH_ANEX, ANEX_BORRADO, ANEX_SALIDA, RADI_NUME_SALIDA)
			            VALUES('$nombreAnexo',$radiNume,$tipoAnexo,$tamanoAnexo,'$soloLectura','".$usuario['usua_login']."','$descripcion'
			            ,$numAnexo,'$nombreAnexo.$extension',$anexEstado,1,$fechaAnexado, 'N', $generaRadicado, $radiSalida)";

			$error=$db->query($consulta);

			$consultaVerificacion = "SELECT ANEX_CODIGO FROM ANEXOS WHERE ANEX_CODIGO = '$nombreAnexo'";
			$rs=$db->query($consultaVerificacion);
			$cod = $rs->fields['ANEX_CODIGO'];
			
			if ($cod){
        $histo = new Historico($db) ;
        $histDescripcion= "Anexo creado desde servicio web ".$descripcion;
        $radicado[]=$radiNume;
        //echo $usuario["usua_depe"]."dependencia del usuario q anexa".$usuario["usua_codi"]."Codigo del usuarop". "-----". $histDescripcion  ;
        $histo->insertarHistorico($radicado,  $usuario["usua_depe"] , $usuario["usua_codi"], "NULL", "NULL", $histDescripcion , 91);
        
        
			}
		}
		return $cod ? $nombreAnexo : 'Error en la adicion verifique: ' . $nombreAnexo;
	}
	else
	{
		return "Error, llave de consulta no valida";
	}

}

/**
 *
 * funcion que calculcula el numero de anexos que tiene un radicado
 *
 *
 *
 * @param int  $radiNume radicado al cual se realiza se adiciona el anexo
 *
 * @param ConectionHandler $db
 *
 * @return int numero de anexos del radicado
 *
 */

function numeroAnexos($radiNume,$db){
	$consulta="SELECT COUNT(1) AS NUM_ANEX FROM ANEXOS WHERE ANEX_RADI_NUME={$radiNume}";
	$salida=0;
	$rs=& $db->query($consulta);
		if($rs && !$rs->EOF)
			$salida=$rs->fields['NUM_ANEX'];
		return  $salida;
}
/**
 *
 * funcioncion que rescata el maxido del anexo de los radicados
 *
 *
 *
 * @param int $radiNume numero del radicado
 *
 * @param ConnectionHandler $db conexion con la db
 *
 * @return int maximo
 *
 */

function maxRadicados($radiNume,$db){
    	$consulta="SELECT max(ANEX_NUMERO) AS NUM_ANEX FROM ANEXOS WHERE ANEX_RADI_NUME={$radiNume}";
	$rs=& $db->query($consulta);
        if($rs && !$rs->EOF)
			$salida=$rs->fields['NUM_ANEX'];
		return  $salida;
}
/**
 *
 * funcion que consulta el tipo de anexo que se esta generando
 *
 *
 *
 *
 *
 * @param sting $extension extencion del archivo
 *
 * @param ConnectionHandler $db conexion con la DB
 *
 * @return int
 *
 */

function tipoAnexo($extension,$db){

	$consulta="SELECT ANEX_TIPO_CODI FROM ANEXOS_TIPO WHERE ANEX_TIPO_EXT='".strtolower($extension)."'";
	$salida=null;

	$rs=& $db->query($consulta);

		if($rs && !$rs->EOF)
			$salida=$rs->fields['ANEX_TIPO_CODI'];
	return $salida;
}


function verificaSolAnulacion ( $radiNume, $usuaLogin ){

	global $ruta_raiz;

	$db = new ConnectionHandler($ruta_raiz);


	$consultaPermiso = "SELECT SGD_PANU_CODI FROM USUARIO WHERE USUA_LOGIN = '$usuaLogin'";

	$rs = $db->query( $consultaPermiso );
	$permisoAnulacion = $rs->fields[ 'SGD_PANU_CODI' ];

	if ( $permisoAnulacion == 0) {
		return false;
	}

    $consultaYaAnulado =	"SELECT r.RADI_NUME_RADI FROM radicado r, SGD_TPR_TPDCUMENTO c where r.radi_nume_radi is not null
    and substr(r.radi_nume_radi, 5, 3)=905 and substr(r.radi_nume_radi, 14, 1) not in ( 2 )
    and r.tdoc_codi=c.sgd_tpr_codigo and r.sgd_eanu_codigo is null and
    ( r.SGD_EANU_CODIGO = 9 or r.SGD_EANU_CODIGO = 2 or r.SGD_EANU_CODIGO IS NULL )";

    /*

    $consultaYaAnulado2 = 'SELECT  to_char(b.RADI_NUME_RADI)

    "IMG_Numero Radicado" , b.RADI_PATH "HID_RADI_PATH" , to_char(b.RADI_NUME_DERI) "Radicado Padre" ,

    b.RADI_FECH_RADI "HOR_RAD_FECH_RADI" , b.RADI_FECH_RADI "Fecha Radicado" , b.RA_ASUN "Descripcion" ,

    c.SGD_TPR_DESCRIP "Tipo Documento" , b.RADI_NUME_RADI "CHK_CHKANULAR" from radicado b, SGD_TPR_TPDCUMENTO c

    where b.radi_nume_radi is not null and substr(b.radi_nume_radi, 5, 3)=905 and

    substr(b.radi_nume_radi, 14, 1) in (1, 3, 5, 6) and b.tdoc_codi=c.sgd_tpr_codigo and

    sgd_eanu_codigo is null and  ( b.SGD_EANU_CODIGO = 9 or b.SGD_EANU_CODIGO = 2 or b.SGD_EANU_CODIGO IS NULL )

    order by 4 ';*/

	$rs=$db->query($consultaYaAnulado);
	$numRadicado = $rs->fields['RADI_NUME_RADI'];
	if ( !$numRadicado ) {
		return  false;
	}
	return true;
}

/**
* Funcion que verifica si el destinatario existe, si no envia el codigo de un usuario anonimo
*
**/
function getAddresseeId($identification='',$tipo_emp_us1=0)
{
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	//Definimos codigo de sgd ciu anonimo
	$sgd_cod_anonimo = 99999999;
	$sgd_cod = 0;

	//Validamos consulta a realizar
	if($tipo_emp_us1==0)
		{
			$sgd_ciu_codigo=$documento_us1;
		$consulta="SELECT SGD_CIU_CODIGO FROM SGD_CIU_CIUDADANO WHERE SGD_CIU_CEDULA ='".$identification."'";
		$rs=$db->query($consulta);
		if($rs && !$rs->EOF)
		$sgd_cod = $rs->fields['SGD_CIU_CODIGO'];
	}

	//Validamos consulta empresas
	if($tipo_emp_us1==1)
		{
			$sgd_ciu_codigo=$documento_us1;
		$consulta="SELECT SGD_CIU_CODIGO FROM SGD_CIU_CIUDADANO WHERE SGD_CIU_CEDULA ='".$identification."'";
		$rs=$db->query($consulta);
		if($rs && !$rs->EOF)
		$sgd_cod = $rs->fields['SGD_CIU_CODIGO'];
	}

	//Validamos si no se asigno ningun codigo colocamos por defecto el de un usuario anonimo
	//if ($sgd_cod==0) $sgd_cod = $sgd_cod_anonimo;
	//Retornamos
	return $sgd_cod;
}



/**
 *
 * Esta funcion permite radicar un documento en Orfeo
 *
 * @param $usuEmail, este parametro es el correo electronico del usuario
 *
 * @param $file, Archivo asociado al radicado codificado en Base64
 *
 * @param $filename, Nombre del archivo que se radica
 *
 * @param $correo, Correo del usuario
 *
 * @param $destinos, arreglo de destinatarios destinatarios,predio,esp
 *
 * @param $asu, Asunto del radicado
 *
 * @param $med, Medio de radicacion
 *
 * @param $ane, descripcion de anexos
 *
 * @param $coddepe, codigo de la dependencia
 *
 * @param $tpRadicado, tipo de radicado
 *
 * @param $cuentai, cuenta interna del radicado
 *
 * @param $radi_usua_actu,
 *
 * @param $tip_rem
 *
 * @param $tdoc
 *
 * @param $tip_doc
 *orfeoMtv/
 * @param $carp_codi
 *
 * @param $carp_per
 *
 * @author Donaldo Jinete Forero
 *
 * @return El numero del radicado o un mensaje de error en caso de fallo
 *
 */


 
 

 
 
 
function radicarDocumento($file,$filename,$correo,$dest_cc_documento,$dest_nombre,$dest_prim_apel,$dest_seg_apel,$dest_telefono,$dest_direccion,$dest_mail,$dest_otro,$dest_idcont,$dest_idpais,$dest_codep,$dest_muni,$asu,$med,$ane,$coddepe,$tpRadicado,$cuentai,$usuaDocActu,$tip_rem,$tipoDocumental,$tipoIdentificacion,$carp_codi,$carp_per,$key)
{
	global $keyWS;
	//Validamos que la key recibida sea valida
	//return $correo;
	
	//echo $correo."Correo";
	if ($key == $keyWS)
	{
		//Conversiones de datos para compatibilidad con aplicaciones internas
		$destinatario = array(
		'cc_documento'=>$dest_cc_documento,
		'documento'=>$dest_cc_documento,
		'tipo_emp'=>0,
		'nombre'=>$dest_nombre,
		'prim_apel'=>$dest_prim_apel,
		'seg_apel'=>$dest_seg_apel,
		'telefono'=>$dest_telefono,
		'direccion'=>$dest_direccion,
		'mail'=>$dest_mail,
		'otro'=>$dest_otro,
		'idcont'=>$dest_idcont,
		'idpais'=>$dest_idpais,
		'codep'=>$dest_codep,
		'muni'=>$dest_muni
		);
		if($correo){
			$infoUsuario = getInfoUsuario($correo,$key,'login');
			if ($infoUsuario!= -1){
			$radi_usua_radi = $infoUsuario["usua_codi"];
			$coddepe = $infoUsuario["usua_depe"];
			}else{
			return "-1| El usuario $correo no existerrr"; 
			}

	}
    if($usuaDocActu){
      $infoUsuarioActual = getInfoUsuario($usuaDocActu,$key,'documento');
      if ($infoUsuarioActual!= -1){
      $radi_usua_actu = $infoUsuarioActual["usua_codi"];
      $depedest = $infoUsuarioActual["usua_depe"];
      }else{
      return "-1| El usuario $usuaDocActu no existe"; 
      }
	  }

		// Fin
		
		global $ruta_raiz;

		include(RUTA_RAIZ."include/tx/Tx.php") ;
		include(RUTA_RAIZ."include/tx/Radicacion.php") ;
		include(RUTA_RAIZ."class_control/Municipio.php") ;

		$db = new ConnectionHandler($ruta_raiz) ;
		$hist = new Historico($db) ;
		$tmp_mun = new Municipio($db) ; 
		$rad = new Radicacion($db) ;
		$tmp_mun->municipio_codigo($destinatario["codep"],$destinatario["muni"]) ;
		$rad->radiTipoDeri = $tpRadicado ;
		$rad->radiCuentai = trim($cuentai);
		$rad->eespCodi =  $esp["documento"] ;
		$rad->mrecCodi =  $med;
		$rad->radiFechOfic =  date("Y-m-d");
		if(!$radicadopadre)  $radicadopadre = null;
		$rad->radiNumeDeri = trim($radicadopadre) ;
		$rad->radiPais =  $tmp_mun->get_pais_codi() ;
		$rad->descAnex = $ane ;
		$rad->raAsun = $asu ;
		$rad->radiDepeActu = $depedest ;
		$rad->radiDepeRadi = $coddepe ;
		$rad->radiUsuaActu = $radi_usua_actu ;
		$rad->trteCodi =  $tip_rem ;
		$rad->tdocCodi=$tipoDocumental ;
		//echo "+++++++++++++++ $tipoDocumento +++++++++++++++++";
		$rad->tdidCodi="'".$tipoIdentificacion."'";
		$rad->carpCodi = $carp_codi ;
		$rad->carPer = $carp_per ;
		$rad->radiPath = 'null';
		$codTx = 2 ;
		$flag = 1 ;
		$rad->usuaCodi=$radi_usua_radi ;
    $rad->dependencia=trim($coddepe) ;
		$noRad = $rad->newRadicado($tpRadicado,$coddepe) ;
		$nurad = trim($noRad) ;
		$sql_ret = $rad->updateRadicado($nurad,"/".date("Y")."/".$coddepe."/".$noRad.".pdf");

		if ($noRad=="-1")
		{
			return "Error no genero un Numero de Secuencia o Inserto el radicado";
		}
		$radicadosSel[0] = $noRad;
		$hist->insertarHistorico($radicadosSel,  $coddepe , $radi_usua_radi, $depedest, $radi_usua_actu, "Radicacion desde servicio web ", $codTx);
		$sgd_dir_us2=2;
		$conexion = $db;
		/*
			Preparacion de variables para llamar el codigo del
			archivo grb_direcciones.php
		*/

    include "../include/tx/usuario.php";
    
    $dirUs = new Usuario($db);
    $datosUsuario["id_table"] = 0;
    $datosUsuario["id_sgd_dir_dre"] = 0;
    $datosUsuario["email"] = $dest_mail;
    $datosUsuario["nombre"] = $dest_nombre;
    $datosUsuario["apellido"] = $dest_prim_apel . " ".$dest_seg_apel;
    $datosUsuario["dignatario"] = $dest_otro;
    $datosUsuario["direccion"] = $dest_direccion;
    $datosUsuario["telef"] = $dest_telefono;
    if($dest_cc_documento) $datosUsuario["cedula"] = $dest_cc_documento;
    $datosUsuario["muni_tmp"] = $dest_muni;
    $datosUsuario["dpto_tmp"] = $dest_codep;
    $datosUsuario["pais_tmp"] = $dest_idpais;
    $datosUsuario["cont_tmp"] = $dest_idcont;
    $datosUsuario["sgdTrd"] = $tip_rem;
    $datosUsuario["tdid_codi"] = $tipoIdentificacion;  // Tipo de identificacion (Ej. 0 Cedula, 1 tarjeta Identidad,  4 Nit, )
    $datosUsuario["sgdDirTipo"] = 1;
    
    if(!$dirUs->guardarUsuarioRadicado($datosUsuario, $nurad)){
       return "22|$nurad|No se pudo guardar remitente. Radicado Creado";  
      
    }
    $correo=$correo."@cnsc.gov.co";
    $anexo = crearAnexo($nurad,$file, $filename, $correo,$ane,$key, 's', '2', '1',$nurad );

		//*********************** FIN INSERTAR DIRECCIONES **********************
		$retval .=$noRad;
		if($filename!=''){
			$ext=explode('.',$filename);
			
			cambiarImagenRad($retval,$ext[1],$file,$key);
		}
		return $retval;
}
else
{
	return "Error, Llave de consulta no valida";
}
}

function anexarExpediente($numRadicado,$numExpediente,$usuaLoginMail,$observa,$key){
	global $ruta_raiz,$keyWS;
	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		//Validamos que el expediente exista
		if (!existeExpediente($numExpediente)) return 'El expediente '.$numExpediente.' no existe';
		$db = new ConnectionHandler($ruta_raiz);
		include_once (RUTA_RAIZ.'include/tx/Historico.php');
        $estado=estadoRadicadoExpediente($numRadicado,$numExpediente);
        $usua=getInfoUsuario($usuaLoginMail,$key);
        $tipoTx = 53;
    	$Historico = new Historico( $db );
    	$fecha=$db->conn->OffsetDate(0,$db->conn->sysTimeStamp);
        $us=  explode('||', $usua);
    	try{
        switch ($estado){
                case 0:
                        throw new Exception("El documento con numero de radicado  {$numRadicado} ya fue anexado al expediente {$numExpediente}");
                case 1:
                        throw new Exception("El documento con numero de radicado {$numRadicado} ya fue anexado al expediente {$numExpediente} y archivado fisicamente");
                case 2:
                        $consulta="UPDATE SGD_EXP_EXPEDIENTE SET SGD_EXP_ESTADO=0,SGD_EXP_FECH={$fecha},USUA_CODI=" . $us[3] .",USUA_DOC='" . $us[1] . "'
                                ,DEPE_CODI='" . $us[2] . "' WHERE RADI_NUME_RADI={$numRadicado}
                                                AND SGD_EXP_NUMERO='{$numExpediente}'";
                break;
                default:
                        $consulta="INSERT INTO SGD_EXP_EXPEDIENTE (SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_ESTADO,USUA_CODI,USUA_DOC,DEPE_CODI)
                                          VALUES ('{$numExpediente}',{$numRadicado},{$fecha},0," . $us[3] .",'" . $us[1] . "','" . $us[2] . "')";
                        break;
        	}
    	}
    	catch (Exception $e){
    		return $e->getMessage();
    	}
        if($db->query($consulta)){
        		$radicados = array($numRadicado);
                $radicados = $Historico->insertarHistoricoExp( $numExpediente, $radicados, $us[2], $us[3], $observa, $tipoTx, 0);
                return $numRadicado;
        }else{
                throw new Exception("Error y no se realizo la operacion");
        }
    }
   else
   {
    	  throw new Exception("Error, llave de consulta no valida");
    }
}


function cambiarImagenRad($numRadicado,$ext,$file,$key){

	global $ruta_raiz,$keyWS;

	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$sql="SELECT RAPI_DEPE_RADI,RADI_FECH_OFIC FROM RADICADO WHERE RADI_NUME_RADI='{$numRadicado}'";
		$rs=$db->query($sql);
		if(!$rs->EOF){
			$year=substr($numRadicado,0,4);
			$depe=substr($numRadicado,4,3);
			$path="/{$year}/{$depe}/{$numRadicado}.{$ext}";
			
			$update="UPDATE RADICADO SET RADI_PATH='{$path}' where RADI_NUME_RADI='{$numRadicado}'";

                        $raiz = shell_exec("pwd");
                        //return trim($raiz)."../bodega".$path;
                       // echo "Filleeeeeeee".$file;
			if(UploadFile($file,"$numRadicado.$ext",$key)=='exito'){

				$db->query($update);
				return "OK";
			}else{
				throw new Exception("ERROR no se puede copiar el archivo");
			}
		}else{
				throw new Exception("ERROR El radicado no existe");
		}
}
else
	{
		throw new Exception ("Llave de consulta no valida");
	}

}

function estadoRadicadoExpediente($numRadicado,$numExpediente){
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	$salida=-1;
	$consulta="SELECT SGD_EXP_ESTADO FROM SGD_EXP_EXPEDIENTE WHERE RADI_NUME_RADI={$numRadicado} AND SGD_EXP_NUMERO='{$numExpediente}'";
	$resultado=$db->query($consulta);
	if($resultado && !$resultado->EOF){
		$salida=$resultado->fields['SGD_EXP_ESTADO'];
	}
	return $salida;
}


/** getInfoUsuario
  * Busca informacion de un usuario del sistema dependiendo del tipo de busqueda, que puede ser correo o documento o login.
    */
function getInfoUsuario($variableUsuario,$key,$tipoBusqueda="correo"){

	global $ruta_raiz,$keyWS;

	//Validamos que la key recibida sea valida
	if ($key == $keyWS)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$upperMail=strtoupper($variableUsuario);
		$lowerMail=strtolower($variableUsuario);
		
		if(trim(strtolower($tipoBusqueda))=="correo"){
      $whereTipoBusqueda = "USUA_EMAIL='{$variableUsuario}' OR USUA_EMAIL='{$upperMail}' OR USUA_EMAIL='{$lowerMail}'";  
      
    }elseif(trim(strtolower($tipoBusqueda))=="documento"){
         $whereTipoBusqueda = "USUA_DOC='{$variableUsuario}' ";    
    }elseif(trim(strtolower($tipoBusqueda))=="login"){
        $whereTipoBusqueda = "USUA_LOGIN='{$variableUsuario}' OR USUA_LOGIN ='{$upperMail}' OR USUA_LOGIN ='{$lowerMail}'";    
   }
		

        $sql="SELECT USUA_LOGIN,USUA_DOC,DEPE_CODI,CODI_NIVEL,USUA_CODI,USUA_NOMB,USUA_EMAIL FROM USUARIO
                        WHERE  $whereTipoBusqueda ";
        $rs=$db->query($sql);
        
                if($rs && !$rs->EOF){

                		$salida['usua_login'] = $rs->fields["USUA_LOGIN"];
                    $salida['usua_doc']   = $rs->fields["USUA_DOC"];
                    $salida['usua_depe']  = $rs->fields["DEPE_CODI"];
                    $salida['usua_nivel'] = $rs->fields["CODI_NIVEL"];
                    $salida['usua_codi']  = $rs->fields["USUA_CODI"];
                    $salida['usua_nomb']  = $rs->fields["USUA_NOMB"];
                    $salida['usua_email'] = $rs->fields["USUA_EMAIL"];
        }else{
        	//throw new Exception("El usuario $variableUsuario no existe $sql");
        	return '-1| El usuario no existe';

   }
       return $salida;
    }
    else
    {
			//throw new Exception("Error, llave de consulta no valida");
			echo "-1| Error, llave de consulta no valida";
    }

}


//Funcion que valida si existe un expediente
function existeExpediente($numExpediente='')
{
	global $ruta_raiz;
	$db = new ConnectionHandler($ruta_raiz);
	if ($numExpediente=='') return false;
	$query = "SELECT COUNT(SGD_EXP_NUMERO) as qty FROM SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO = '$numExpediente'";
	$rs = $db->query($query);

	if($rs && $rs->fields['QTY'] > 0)
	{
		return true;
	}
	else
	{
		return false;
	}

}


function getInfoRadicado($numRadicado,$key)
{
	global $ruta_raiz,$keyWS;
	if ($keyWS==$key)
	{
		$db = new ConnectionHandler($ruta_raiz);
		$sql = "SELECT * FROM RADICADO WHERE RADI_NUME_RADI = '$numRadicado'";
		$rs = $db->query($sql);
		$radi	= array();
		if ($rs)
		{
			$radi['RADI_NUME_RADI'] = $rs->fields['RADI_NUME_RADI'];
			$radi['RADI_FECH_RADI'] = $rs->fields['RADI_FECH_RADI'];
			$radi['RA_ASUN']        = $rs->fields['RA_ASUN'];
			$radi['TDOC_CODI']      = $rs->fields['TDOC_CODI'];
			$radi['RADI_PATH']      = $rs->fields['RADI_PATH'];
			$radi['RADI_USUA_ACTU'] = $rs->fields['RADI_DEPE_ACTU'];
			$radi['RADI_DEPE_ACTU'] = $rs->fields['RADI_DEPE_ACTU'];
			$radi['RADI_USU_ANTE']  = $rs->fields['RADI_USU_ANTE'];
			$radi['RADI_DEPE_RADI'] = $rs->fields['RADI_DEPE_RADI'];
			$radi['RADI_USUA_RADI'] = $rs->fields['RADI_USUA_RADI'];
			$radi['CODI_NIVEL']     = $rs->fields['CODI_NIVEL'];
			$radi['DEPE_CODI']      = $rs->fields['DEPE_CODI'];
                        $rad=$radi['RADI_NUME_RADI'].'||'.$radi['RADI_FECH_RADI'].'||'.$radi['RA_ASUN'].'||'.$radi['TDOC_CODI'].'||'.$radi['RADI_PATH'].'||'.
                                $radi['RADI_USUA_ACTU'].'||'.$radi['RADI_DEPE_ACTU'].'||'.$radi['RADI_USU_ANTE'].'||'.$radi['RADI_DEPE_RADI'].'||'.$radi['RADI_USUA_RADI']
                                .'||'.$radi['CODI_NIVEL'].'||'.$radi['DEPE_CODI'];
			//Obtenemos informacion del destinatario
			$sqlc = "SELECT * FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = '$numRadicado'";
			$rsc = $db->query($sqlc);
			if ($rsc)
			{
				$radi['SGD_DIR_CODIGO']    = $rsc->fields['SGD_DIR_CODIGO'];
				$radi['SGD_DIR_TIPO']      = $rsc->fields['SGD_DIR_TIPO'];
				$radi['SGD_OEM_CODIGO']    = $rsc->fields['SGD_OEM_CODIGO'];
				$radi['SGD_CIU_CODIGO']    = $rsc->fields['SGD_CIU_CODIGO'];
				$radi['MUNI_CODI']         = $rsc->fields['DPTO_CODI'];
				$radi['ID_PAIS']           = $rsc->fields['ID_PAIS'];
				$radi['SGD_DIR_DIRECCION'] = $rsc->fields['SGD_DIR_DIRECCION'];
				$radi['SGD_DIR_TELEFONO']  = $rsc->fields['SGD_DIR_TELEFONO'];
				$radi['SGD_DIR_MAIL']      = $rsc->fields['SGD_DIR_MAIL'];
				$radi['SGD_DIR_NOMBRE']    = $rsc->fields['SGD_DIR_NOMBRE'];
				$radi['SGD_DIR_NOMREMDES'] = $rsc->fields['SGD_DIR_NOMREMDES'];
				$radi['SGD_DIR_DOC']       = $rsc->fields['SGD_DIR_DOC'];
                                $rad.='||'.$radi['SGD_DIR_CODIGO'].'||'.$radi['SGD_DIR_TIPO'].'||'.$radi['SGD_OEM_CODIGO'].'||'.$radi['SGD_CIU_CODIGO'].'||'.$radi['MUNI_CODI']
                                        .'||'.$radi['ID_PAIS'].'||'.$radi['SGD_DIR_DIRECCION'].'||'.$radi['SGD_DIR_TELEFONO'].'||'.$radi['SGD_DIR_MAIL'].'||'.$radi['SGD_DIR_NOMBRE']
                                        .'||'.$radi['SGD_DIR_NOMREMDES'].'||'.$radi['SGD_DIR_DOC'];
			}
		}
        	return $rad;
	}
	else
	{
		return array('error'=>'La clave de consulta del servicio web no coincide.');
	}
}
//$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
//@$server->service($HTTP_RAW_POST_DATA);

@$server->service(file_get_contents("php://input"));
?>
