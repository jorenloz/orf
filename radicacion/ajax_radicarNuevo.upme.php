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
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

  session_start();
  $ruta_raiz = "..";

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(-1);

$sendEmail = true; //Enviar correo electronico

  if (!$_SESSION['dependencia'])
      header ("Location: $ruta_raiz/cerrar_session.php");

  header('Content-Type: application/json');
  include_once("$ruta_raiz/include/db/ConnectionHandler.php");
  $db     = new ConnectionHandler("$ruta_raiz");
  $db->conn->debug  = false;
  $ADODB_COUNTRECS  = true;
  $ADODB_FORCE_TYPE = ADODB_FORCE_NULL;

  include("$ruta_raiz/include/tx/Tx.php");
  include("$ruta_raiz/include/tx/Radicacion.php");
  include("$ruta_raiz/include/tx/usuario.php");
  include("$ruta_raiz/class_control/Municipio.php");

  $hist      = new Historico($db);
  $classusua = new Usuario($db);
  $Tx        = new Tx($db);

  $dependencia   = $_SESSION["dependencia"];
  $codusuario    = $_SESSION["codusuario"];
  $usua_doc      = $_SESSION["usua_doc"];
  $tpDepeRad     = $_SESSION["tpDepeRad"];
  $adate=date('Y');


  $tpRadicado    = empty($_POST['datorad'])? 0 : $_POST['datorad'];
  $tpRadicado    = trim($tpRadicado ,";");

  //$tpRadicado    = empty($_POST['radicado_tipo'])? 0 : $_POST['radicado_tipo'];
  $cuentai       = $_POST['cuentai'];
  $guia          = $_POST['guia'];
  $fecha_gen_doc = $_POST['fecha_gen_doc'];
  $usuarios      = $_POST['usuario'];
  $asu           = $_POST['asu'];
  $med           = $_POST['med'];

  $nofolios      = $_POST['nofolios'];
  $noanexos      = $_POST['noanexos'];
  $otro_us       = $_POST['otro_us'];

  $ane           = $_POST['ane'];
  $coddepe       = $_POST['coddepe'];
  $tdoc          = $_POST['tdoc'];

  $ent           = $_POST['ent'];
  $radicadopadre = $_POST['radicadopadre'];
  if(!$radicadopadre){  $radicadopadre = null; }


  //Enviados solo si es para modificar
  $modificar     = $_POST['modificar'];
  $nurad         = $_POST['nurad'];


 //Si el numero el radicado esta en fisico
 if ($_SESSION["entidad"] == 'CRA'){ 
  if (isset($_POST["esta_fisico"])){
  $esta_fisico = 1; 
  }else{
  $esta_fisico = 0;  
  }  
 } 


  /**************************************************/
  /*********** RADICAR DOCUMENTO  *******************/
  /**************************************************/
  $rad               = new Radicacion($db);

  //Si el radicado que se esta realizando es un memorando
  //este debe quedar guardado en la bandeja del usuario que
  //realiza el radicado por esta razon guardamos el radicado
  //con el codigo del usuario que realiza la accion.
  if($ent == 2){
    $carp_codi         = 0;
    $rad->radiUsuaActu = 1;
    $rad->radiDepeActu = $coddepe;
  }else{
    $carp_codi         = $ent;
    $rad->radiUsuaActu = $codusuario;
    $rad->radiDepeActu = $dependencia;
  }

  $rad->radiTipoDeri = $tpRadicado;
  $rad->radiCuentai  = trim($cuentai);
  $rad->guia         = trim(substr($guia,0 ,20));
  $rad->eespCodi     = $documento_us3;
  $rad->mrecCodi     = $med;// "dd/mm/aaaa"
  $rad->radiFechOfic =       substr($fecha_gen_doc,6 ,4)
                       ."-". substr($fecha_gen_doc,3 ,2)
                       ."-". substr($fecha_gen_doc,0 ,2);

  if(!$radicadopadre){
    $radicadopadre = null;
  }

  if(!$ent){
    $radicadopadre = null;
  }

  $rad->radiNumeDeri = trim($radicadopadre);
  $rad->descAnex     = substr($ane, 0, 99);
  $rad->radiDepeRadi = "'$coddepe'";
  $rad->trteCodi     = $tip_rem;
//  $rad->tdocCodi     = $tdoc;
  $rad->nofolios     = $nofolios;
  $rad->noanexos     = $noanexos;
  $rad->carpCodi     = $carp_codi;
  $rad->carPer       = $carp_per;
  $rad->trteCodi     = $tip_rem;
  $rad->raAsun       = substr(htmlspecialchars(stripcslashes($asu)),0,349);

   //Si el numero el radicado esta en fisico
 if ($_SESSION["entidad"] == 'CRA'){ 
  $rad->esta_fisico = $esta_fisico;
 } 


  if(strlen(trim($aplintegra)) == 0){
    $aplintegra = "0";
  }

  $rad->sgd_apli_codi = $aplintegra;

  if($nurad){

  if ($_SESSION["entidad"] == 'CRA'){ 
  $rad->tdocCodi = "noactualizar";
 }else{
  $rad->tdocCodi     = $tdoc;
 } 
    if(!$rad->updateRadicado($nurad)){
      $data[] = array( "error"   => 'No se actualiz&oacute; el radicado');
    }
  }else{
    $rad->tdocCodi     = $tdoc;
    $nurad = $rad->newRadicado($ent, $tpDepeRad[$ent]);
/**********************************Incluye un radicado interno automaticamente a una TRD*******************************************************/
/**************************************************Desarrollado para la CRA *******************************************************************/
if($ent==3 && $db->entidad=="CRA")
{
		$db->conn->Execute( "UPDATE RADICADO
							SET TDOC_CODI=384, SGD_SPUB_CODIGO=1
							WHERE RADI_NUME_RADI='$nurad'");
			
		$isqlTRD = "select SGD_MRD_CODIGO 
					from SGD_MRD_MATRIRD 
					where DEPE_CODI = '$dependencia'
				 	   and SGD_SRD_CODIGO = 91
				       and SGD_SBRD_CODIGO = 01
					   and SGD_TPR_CODIGO = 993";
		//$db->conn->debug = true;
			$rsTRD = $db->conn->Execute($isqlTRD);
			$codiTRD = $rsTRD->fields['SGD_MRD_CODIGO'];
	    
		$db->conn->Execute( "insert into  sgd_rdf_retdocf (SGD_MRD_CODIGO,RADI_NUME_RADI,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_RDF_FECH) values (".$codiTRD.", ".$nurad.", ".$coddepe.",".$codusuario.", ".$usua_doc.",SYSDATE)");
	
	if ($dependencia==410||$dependencia==420 ||$dependencia==430){ 
			$sqletq = "select SGD_EXP_NUMERO
					from SGD_SEXP_SECEXPEDIENTES
					where SGD_SEXP_ANO = '$adate'
				 	   and DEPE_CODI =401
				           and SGD_SRD_CODIGO = 91
					   and SGD_SBRD_CODIGO = 01";
			//$db->conn->debug = true;
		$rsetq = $db->conn->Execute($sqletq);
		$rexp = $rsetq->fields['SGD_EXP_NUMERO'];
		$db->conn->Execute( "insert into  SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_FECH_MOD,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_EXP_ESTADO) values ('$rexp', ".$nurad.", SYSDATE, NULL, ".$coddepe.",".$codusuario.", ".$usua_doc.",0)");
	}else {
				$sqletq = "select SGD_EXP_NUMERO
					from SGD_SEXP_SECEXPEDIENTES
					where SGD_SEXP_ANO = '$adate'
				 	   and DEPE_CODI = '$dependencia'
				           and SGD_SRD_CODIGO = 91
					   and SGD_SBRD_CODIGO = 01";
			//$db->conn->debug = true;
		$rsetq = $db->conn->Execute($sqletq);
		$rexp = $rsetq->fields['SGD_EXP_NUMERO'];
		$db->conn->Execute( "insert into  SGD_EXP_EXPEDIENTE(SGD_EXP_NUMERO,RADI_NUME_RADI,SGD_EXP_FECH,SGD_EXP_FECH_MOD,DEPE_CODI,USUA_CODI,USUA_DOC,SGD_EXP_ESTADO) values ('$rexp', ".$nurad.", SYSDATE, NULL, ".$coddepe.",".$codusuario.", ".$usua_doc.",0)");
			}
	}
/**********************************************************************************************************************************************/
  }

  if ($nurad=="-1"){
    $data[] = array( "error"   => 'No se genero un numero de radicado');
  }else{
    $data[] = array( "answer"  => $nurad);
  }

  $radicadosSel[0] = $nurad;

  if (isset($_POST['modificar'])){$_tipo_tx = 21;}else{$_tipo_tx = 2;}

  $hist->insertarHistorico( $radicadosSel,
                            $dependencia ,
                            $codusuario,
                            $coddepe,
                            $codusuario,
                            " ",
                            $_tipo_tx);

    //Borramos todos los usuarios existentes en sgd_dir_drecciones y los
    //grabamos nuevamente con los datos suministrados.
    $select = "delete from sgd_dir_drecciones where radi_nume_radi = $nurad";
    $db->conn->query($select);

    /**********************************************************************
     *********** GRABAR DIRECCIONES ***************************************
     **********************************************************************
     * Existen tres tipos distintos de datos de direccion
     *
     * Descripcion
     * (0_0_XX_XX2)
     * primer campo : consecutivo de los usuarios asignados a un radicado
     *                si es nuevo puede ser 0, o si es el primer registro
     *                de los usuarios asignados al radicado.
     *
     * segundo campo: tipo de usuario {usuario: 0, empresa :2, funcionario: 6}
     *
     * tercer campo: codigo con el cual esta grabado en la tabla SGD_DIR_DRECCIONES
     *
     * Cuarto campo; el codigo de identificacion del usuario de la tabla origen.
     *               esta tabla puede ser la de SGD_OEM_CODIGO, SGD_CIU_CODIGO
     *               o USUARIOS
     *
     *
     * 1) Un usuario nuevo (0_0_XX_XX2)(0_0_XX_XX3)....
     *    El usuario nuevo se puede identificar cuando en los datos
     *    de usuario se muestra el siguiente string (0_0_XX_XX2) el
     *    cual denota que no existe un codigo de almacenamiento unido a un
     *    radicado que son las dos primeras equis, las siguietnes son el
     *    codigo que le corresponde al usuario almacenado en la base de datos
     *    ya sea un usuario, funcionario o entidad y esta representado por
     *    las ultimas equis. Como se pueden crear mas de un usuario nuevo se
     *    genera un cosecutivo que es el ultimo digito
     *    ejemplo: (0_0_XX_XX2) las dos xx significan que no esta asociado
     *              a ningun radicado, las ultimas muestran que es un
     *              usuario nuevo y el 2 que es el segundo registro generado

     * 2) Un usuario existente en el sistema, NO asociado a un radicado (0_0_XX_12)
     *    (0_0_XX_16)...
     *
     *
     * 3) Un usuario existen (0_0_123_17) (0_0_327_123)
     *    Al momento de genear un radicado podemos traer usuario del sistema y a su ves
     *    cambiar la informacion que hace parte de este.
     */

    //Datos de usuarios
    foreach ($usuarios as $clave => $valor) {

        list($consecutivo, $sgdTrd, $id_sgd_dir_dre , $id_table) = explode('_', $valor);

//OBTENEMOS EL CONTINENTE DINAMICAMENTE
$_id_pais = $_POST[$valor."_".pais_codigo];
$query_ = "select p.id_cont from sgd_def_paises p  where id_pais = $_id_pais";
$rs = $db->conn->query($query_);
while(!$rs->EOF){
 $id_continente    = $rs->fields["ID_CONT"];
 $rs->MoveNext();
 }
        $usuarios[$clave] = array(
            "cedula"         => $_POST[$valor."_".cedula],
            "nombre"         => $_POST[$valor."_".nombre],
            "apellido"       => $_POST[$valor."_".apellido],
            "dignatario"     => $_POST[$valor."_".dignatario],
            "telef"          => $_POST[$valor."_".telefono],
            "direccion"      => $_POST[$valor."_".direccion],
            "email"          => $_POST[$valor."_".email],
            "muni"           => $_POST[$valor."_".muni],
            "muni_tmp"       => $_POST[$valor."_".muni_codigo],
            "dep"            => $_POST[$valor."_".dep],
            "dpto_tmp"       => $_POST[$valor."_".dep_codigo],
            "pais"           => $_POST[$valor."_".pais],
            "pais_tmp"       => $_POST[$valor."_".pais_codigo],
            "cont_tmp"       => $id_continente,
            "tdid_codi"      => $_POST[$valor."_".tdid_codi],
            "sgdTrd"         => $sgdTrd,
            "id_sgd_dir_dre" => $id_sgd_dir_dre,
            "id_table"       => $id_table
        );

	if($ent == 2){
		$query = "select u.USUA_EMAIL
                		from usuario u
		                where u.USUA_CODI = 1 and  u.depe_codi='$coddepe'";
				$rsM=$db->conn->query($query);
				$mailDestino_frm = $rsM->fields["USUA_EMAIL"];
	}
        $respons = $classusua->guardarUsuarioRadicado($usuarios[$clave], $nurad);

        if($respons!=1){
            $data[] = array( "error"   => 'No se Agregó correctamente el destinatario, compruebe datos');
        }



//ENVIAR UN CORREO ELECTRONICO AL DESTINATARIO AL MOMENTO DE RADICAR.
if($sendEmail){
 $codTx=51;
 $_emailUser  = $_POST[$valor."_".email]; //Email del usuario 
 $radicadosSelText = $nurad;
 if ($_emailUser == ""){$_emailUser="no-reply@upme.gov.co";}
	
	$entidad = $_SESSION['entidad'];
	$nombre_fichero = $_SESSION['entidad'].".mailInformar.php";
	$ruta_fichero = $ruta_raiz.'/include/mail/'.$nombre_fichero;
		if (file_exists($ruta_fichero)) {
	       require("$ruta_raiz/include/mail/$entidad.mailInformar.php");
		} else {
	       require("$ruta_raiz/include/mail/GENERAL.mailInformar.php");
		}
     ob_end_clean();
	}//FIN enviar email

    }

    echo json_encode($data);
