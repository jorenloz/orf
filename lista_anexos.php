<?php
session_start();


if (!$ruta_raiz) $ruta_raiz= ".";
include "$ruta_raiz/conn.php";
include_once("$ruta_raiz/class_control/anexo.php");
require_once("$ruta_raiz/class_control/TipoDocumento.php");
include "$ruta_raiz/config.php";
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$ln          = $_SESSION["digitosDependencia"];
$opt_ver_anexos_borrados = $_SESSION["opt_ver_anexos_borrados"];

$db = new ConnectionHandler("$ruta_raiz");
define('ADODB_ASSOC_CASE', 1);
$objTipoDocto  = new TipoDocumento($db);
$objTipoDocto->TipoDocumento_codigo($tdoc);
$num_archivos=0;
$anex = new Anexo($db);
$sqlFechaDocto = $db->conn->SQLDate("Y-m-D H:i:s A","a.sgd_fech_doc");
$sqlFechaAnexo = $db->conn->SQLDate("Y-m-D H:i:s A","a.anex_fech_anex");
//$sqlFechaAnexo = "to_char(anex_fech_anex, 'YYYY/DD/MM HH:MI:SS')";
$sqlSubstDesc =  $db->conn->substr."(anex_desc, 0, 100)";
//include_once("include/query/busqueda/busquedaPiloto1.php");
// Modificado SGD 06-Septiembre-2007
#$maxRows = $db->limit();
//$db->conn->debug = true;
		$db->limit(324);
                $limitMsql = $db->limitMsql;
                $limitOci8 = $db->limitOci8;
                $limitPsql = $db->limitPsql;

                $db->limit(1);
                $limit2Oci8 = $db->limitOci8;
                $limit2Psql = $db->limitPsql;
$verrad = trim($verrad);
$isql = "select $limitMsql a.anex_codigo AS DOCU
      ,at.anex_tipo_ext AS EXT
			,a.anex_tamano AS TAMA
			,a.anex_solo_lect AS RO
      ,usua_nomb AS CREA
			,$sqlSubstDesc AS DESCR
			,a.anex_nomb_archivo AS NOMBRE
			,a.ANEX_CREADOR
			,a.ANEX_ORIGEN
			,a.ANEX_SALIDA
			,$radi_nume_salida RADI_NUME_SALIDA
			,a.ANEX_ESTADO
			,a.SGD_PNUFE_CODI
			,a.SGD_DOC_SECUENCIA
			,SGD_DIR_TIPO
			,SGD_DOC_PADRE
			,a.SGD_TPR_CODIGO
			,a.SGD_TRAD_CODIGO
			,a.ANEX_TIPO
			,a.ANEX_FECH_ANEX AANEX_FECH_ANEX
			,a.ANEX_FECH_ANEX
			,a.ANEX_RADI_NUME
			,a.ANEX_TIPO_FINAL
			,a.ANEX_ENV_EMAIL
			,tpr.SGD_TPR_DESCRIP
			,$sqlFechaDocto FECDOC
			,$sqlFechaAnexo FEANEX
			,a.ANEX_TIPO NUMEXTDOC
			,(SELECT d.sgd_dir_nomremdes from sgd_dir_drecciones d where (d.sgd_anex_codigo=a.anex_codigo  or d.radi_nume_radi=a.radi_nume_salida)
 AND a.sgd_dir_tipo=d.sgd_dir_tipo  limit 1) destino
      ,rsal.radi_path PATH_RAD_SALIDA
		from  anexos_tipo at ,usuario u,
		anexos a 
		   left join radicado rsal           on (a.radi_nume_salida=rsal.radi_nume_radi)
		   left join sgd_tpr_tpdcumento tpr  on (a.sgd_tpr_codigo=tpr.sgd_tpr_codigo)
      where anex_radi_nume=$verrad and a.anex_tipo=at.anex_tipo_codi
       and a.anex_codigo like '$verrad%'
		   and a.anex_creador=u.usua_login and a.anex_borrado='N' $limitOci8
	   order by a.id, a.anex_codigo, a.ANEX_FECH_ANEX, sgd_dir_tipo,a.anex_radi_nume,a.radi_nume_salida $limitPsql";


$iSqlRadSalida = "SELECT count(*) NANEXOS FROM ANEXOS WHERE RADI_NUME_SALIDA='$verrad'";
//$db->conn->debug = true;

$rsAnexoSalida  = $db->conn->query($iSqlRadSalida);

$tieneAnexoSalida = 0;
if($rsAnexoSalida){
  $tieneAnexoSalida = $rsAnexoSalida->fields["NANEXOS"];
}

// case eliminado. No se entiende para qu se realiza.
?>
<table WIDTH="100%" align="center" id="tableDocument" class="table table-striped table-hover " >
    <thead>
        <tr class="pr2">
            <th width='1%'></th>
            <th width='1%'></th>
            <th width='15%' ><center>Documento</center></th>
            <th width='5%'><center>Trd</center></th>
            <th width='1%'></th>
            <th width='10%'>Destino</th>
            <th width='5%'> Tama&ntilde;o (Kb)</th>
            <th width='20%'>Creador</th>
            <th width='20%'>Descripcion / Tipo</th>
            <th width='12%'>Fecha de Creaci&oacute;n Anexo</th>
            <th width='10' colspan="6"><center>Acci&oacute;n</center></th>
        </tr>
    </thead>
<?php
include_once "$ruta_raiz/tx/verLinkArchivo.php";
$verLinkArchivo = new verLinkArchivo($db);
$rowan = array();
//$db->conn->debug = true;
$rs = $db->conn->query($isql);
if (!$ruta_raiz_archivo) $ruta_raiz_archivo = $ruta_raiz;
$directoriobase="$ruta_raiz_archivo/bodega/";
//Flag que indica si el radicado padre fue generado desde esta area de anexos
$swRadDesdeAnex=$anex->radGeneradoDesdeAnexo($verrad);

if($rs){

while(!$rs->EOF){
	$aplinteg     = $rs->fields["SGD_APLI_CODI"];
	$numextdoc    = $rs->fields["NUMEXTDOC"];
	$tpradic      = $rs->fields["SGD_TRAD_CODIGO"];
	$tprDescrip   = $rs->fields["SGD_TPR_DESCRIP"];
	$coddocu      = $rs->fields["DOCU"];
	$origen       = $rs->fields["ANEX_ORIGEN"];
  $para_radicar = $rs->fields["ANEX_SALIDA"];
  $pathRadSalida = $rs->fields["PATH_RAD_SALIDA"];
  $radiNumeSalida = $rs->fields["RADI_NUME_SALIDA"];
  $anexCarpeta = $rs->fields["ANEX_CARPETA"];
  $anexCodigo = $rs->fields["DOCU"];
  $anexTipoFinal = $rs->fields["ANEX_TIPO_FINAL"];


	if ($rs->fields["ANEX_SALIDA"]==1 )	$num_archivos++;
	$linkarchivo=$directoriobase.substr(trim($coddocu),0,4)."/".intval(substr(trim($coddocu),4,$ln))."/docs/".trim($rs->fields["NOMBRE"]);
	$linkarchivo_vista="$ruta_raiz/bodega/".substr(trim($coddocu),0,4)."/".intval(substr(trim($coddocu),4,$ln))."/docs/".trim($rs->fields["NOMBRE"])."?time=".time();
	$linkarchivotmp=$directoriobase.substr(trim($coddocu),0,4)."/".intval(substr(trim($coddocu),4,$ln))."/docs/tmp".trim($rs->fields["NOMBRE"]);
	if(!trim($rs->fields["NOMBRE"])) $linkarchivo = "";

if ($tpradic==''){$tip_rest = substr($verrad,-1);if ($tip_rest == 2){$tpradic = 1 ;}else{$tpradic =$tip_rest;}}
if ($db->entidad=="CRA"){if ($tpradic!=''){$tip_rest = substr($verrad,-1);if ($tip_rest == 2){$tpradic = 1 ;}else{$tpradic =$tip_rest;}}}
?>
<tr id="<?=$coddocu?>">
<?php

$cod_radi = ($rs->fields["RADI_NUME_SALIDA"]!=0)? $rs->fields["RADI_NUME_SALIDA"] : $coddocu;
$cod_radi_div = "<a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$coddocu','$ruta_raiz');\" id='codRadi$cod_radi'>$cod_radi</a>";
$anex_estado = $rs->fields["ANEX_ESTADO"];
if($anex_estado<=1) {$img_estado = "<img src='imagenes/docRecibido.gif' title='Se cargo un Archivo. . .'> "; }
if($anex_estado==2) {$img_estado = "<img src='imagenes/docRadicado.gif' title='Se Genero Radicado. . .'> "; }
if($anex_estado==3) {$img_estado = "<img src='imagenes/docImpreso.gif' title='Se Archivo Radicao y listo para enviar . . .'>"; }
if($anex_estado==4) {$img_estado = "<img src='imagenes/docEnviado.gif' title='Archivo Enviado. . .'>"; }
?>
<TD height="21" > <font size=1> <?=$img_estado?> </font> </TD>
<?php
?>
 <td width="1" valign="middle" align=right>
<?
     $total_digitos = 11 + $ln;
   $ext = $rs->fields["EXT"];
	 if (strlen($cod_radi) <= $total_digitos){
		//Se trata de un Radicado
		$resulVali = $verLinkArchivo->valPermisoRadi($cod_radi);
		$valImg = $resulVali['verImg'];
	 }else{
		//Se trata de un Anexo sin Radicar
		$resulValiA = $verLinkArchivo->valPermisoAnex($coddocu);
		$valImg = $resulValiA['verImg'];
	}
	 // Si hay un elemento definitivo,muestra el archivo definitivo.
	if($pathRadSalida and substr($pathRadSalida, -10)!=substr(str_replace("d.",".",$linkarchivo), -10)){
		//Se trata de un Radicado
		$resulValiRs = $verLinkArchivo->valPermisoRadi($radiNumeSalida);
		$valImgRs = $resulValiRs['verImg'];
		$extRadSalida = array_pop(explode(".",$pathRadSalida));
	if(($valImgRs == "SI" or $verradPermisos == "Full")  ){
	  echo "<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$radiNumeSalida','$ruta_raiz');\"><img src='img/icono_$extRadSalida.jpg' title='Imagen $extRadSalida' width='25'> </a>";
	 }else{
	  echo "<a  href='javascript:noPermiso()' class=\"vinculos\" ><img src='img/icono_$extRadSalida.jpg' title='Imagen $extRadSalida' width='25'> </a>";
  }

	 }else{
  }

	echo "</td><td>";
	 if($valImg == "SI" or $verradPermisos == "Full" ){
	   echo "<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$coddocu','$ruta_raiz');\"><img src='img/icono_$ext.jpg' title='$ext' width='25'> $cod_radi_div </a>";


	 }else{
	 echo "<a class='vinculos' href='javascript:noPermiso()' > $cod_radi_div </a>";
	 }

	// si es va mostrar el definitivo del radicado de Salida

 //Codigo solo para la migracion de acapella ---- Fin ----
	 //if($valImg == "SI" or $verradPermisos == "Full" ){
	 //echo "<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$coddocu','$ruta_raiz');\"> $cod_radi </a>";
	 //}else{
		 //echo "<a class='vinculos' href='javascript:noPermiso()' > $cod_radi </a>";
		 //}


?>
</td>
<td width="1%" valign="middle"><small>
  <?
	/*
	* Indica si el Radicado Ya tiene asociado algun TRD
	*/
		$isql_TRDA = "SELECT *
				FROM SGD_RDF_RETDOCF
				WHERE RADI_NUME_RADI = '$cod_radi'
				";
	$rs_TRA = $db->conn->Execute($isql_TRDA);
	$radiNumero = $rs_TRA->fields["RADI_NUME_RADI"];

  $msg_TRD = ($radiNumero !='')? 'S' : '';
  echo "\n";
	echo $msg_TRD;
?>
</small></td>

	<td width="1%" valign="middle"><font face="Arial, Helvetica, sans-serif">
<?php

  $no_es_impreso = $rs->fields["ANEX_ESTADO"] <= 3;
  $es_extension = ($rs->fields["EXT"]=="rtf" or
                    $rs->fields["EXT"]=="doc" or
                    $rs->fields["EXT"]=="docx" or
                    $rs->fields["EXT"]=="odt" or
                    $rs->fields["EXT"]=="xml") and $no_es_impreso;

  if($es_extension) {
    if($valImg == "SI"){
		  echo"<a class=\"vinculos\" style='cursor:pointer;cursor:hand;' onclick=\"vistaPreliminar('$coddocu','$linkarchivo','$linkarchivotmp');\">";
		}else{
		  echo "<a class='vinculos' style='cursor:pointer;cursor:hand;' href='javascript:noPermiso()' >";
		}

    echo "<span class='glyphicon glyphicon-search'></span>\n";
    echo "<font face='Arial, Helvetica, sans-serif' class='etextomenu'>\n";
    echo "</a>\n";
    $radicado = "false";
    $anexo = $cod_radi;
	}
?>
	</font>
</TD>
 <td><font size=1><?=substr($rs->fields["DESTINO"],0,18)?></font></td>
 <td><font size=1><?=$rs->fields["TAMA"]?></font></td>
 <td><font size=1><?=$rs->fields["CREA"]?></font></td>
 <td><font size=1><?=$rs->fields["DESCR"]?><br><?=$tprDescrip?></font></td>
 <td><font size=1><?=$rs->fields["FEANEX"]?></font></td>
 <td ><font size=1>
	<?php
  $es_pdf = $rs->fields["EXT"] == "pdf";
  $anexEnvEmail  = $rs->fields["ANEX_ENV_EMAIL"];
  $ruta_archivo_txt = 'bodega/' . substr($rs->fields["DOCU"], 0, 4) . '/' .
                      substr($rs->fields["DOCU"], 4, 3) . '/docs/' .
                      $rs->fields["DOCU"] . '.txt';

  $existe_txt = file_exists($ruta_archivo_txt);

  if($origen!=1 and $linkarchivo  and $verradPermisos == "Full" ) {
    $no_esta_enviado = $anex_estado < 4;

    if ($no_esta_enviado and $anexEnvEmail !=1) {

      if ($es_pdf && $para_radicar && ($existe_txt || $anexTipoFinal>=0)) {
	      echo "<a class='vinculos' href=javascript:editar_anexo('$coddocu')><img src='img/icono_modificar.png' title='Modificar Archivo'></a> ";
      } else {
	      echo "<a class='vinculos' href=javascript:verDetalles('$coddocu','$tpradic','$aplinteg')><img src='img/icono_modificar.png' title='Modificar Archivo'></a> ";
      }

			if(strlen($cod_radi)<=17){
				echo "<a href='./radicacion/NEW.php?nurad=$cod_radi&Buscar=BuscarDocModUS&".session_name()."=".session_id()."&Submit3=ModificarDocumentos&Buscar1=BuscarOrfeo78956jkgf' notborder ><img src='img/icono_modificar_radicado.png' title='Modificar Datos/Remitentes del Radicado'></a> ";
			}

    }

	}

  echo "</font>\n";
  echo "</small></td>\n";

    //Estas variables se utilizan para verificar si se debe mostrar la opcion de tipificacion de anexo .TIF
		$anexTipo = $rs->fields["ANEX_TIPO"];
    $anexTPRActual = $rs->fields["SGD_TPR_CODIGO"];

   	if ($verradPermisos == "Full") {
    ?>
		<td>
    	<?php
    	$radiNumeAnexo = $rs->fields["RADI_NUME_SALIDA"];

		if($radiNumeAnexo>0 and trim($linkarchivo) ) {
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=\"vinculos\" href=\"javascript:ver_tipodocuATRD($radiNumeAnexo,$codserie,$tsub)\";><img src='img/icono_clasificar.png' title='Clasificar Documento'></a> ";
			if($_SESSION["permSendMailRR"]=="1" && $anex_estado<4 && $anexTipoFinal>=1 && $anexEnvEmail !=1){
			  echo "<a class=\"vinculoTipifAnex\" href=\"javascript:sendMail('$cod_radi',$verrad,'$anexCodigo',$tsub)\";> <img src='img/sendMail.png' width='30' title='Enviar @mail'> </a> ";
			}
		}elseif ($perm_tipif_anexo == 1 && $anexTipo == 4 && $anexTPRActual == ''){ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, ademas el anexo no ha sido tipificado
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi','$anexo',$codserie,$tsub);> <img src='img/icono_clasificar.png' title='Clasificar Documento'> </a> ";
		}elseif ($perm_tipif_anexo == 1 && $anexTipo == 4 && $anexTPRActual != ''){ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, ademas el anexo YA ha sido tipificado antes
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi','$anexo',$codserie,$tsub);> <img src='img/icono_clasificar.png' title='Volver a Clasificar Documento'> </a> ";
		}
	?>
 	</small></td>
	<td >
<?php
  $es_administrador = $codusuario == 1;
  $usuario_creador = $rs->fields["RADI_NUME_SALIDA"] == 0 and
                      $ruta_raiz != ".." and
                      (trim($rs->fields["ANEX_CREADOR"])==trim($krd) or $es_administrador);

  if ($usuario_creador) {
  if($origen!=1  and $linkarchivo) {
      $v = "0";
      echo "<a class=\"vinculos\"  href=\"JavaScript:void(0);\" onclick=\"borrarArchivo('$coddocu','$linkarchivo','$cod_radi','0');\"> <img src='img/icono_borrar.png'  title='Borrar Archivo' id='iconoBorrar$coddocu'> </a>";

    }else{

    }
  }
		?>
	</small></td>
	<td>
	<?php

  //*********************************************
  // Muestra la opcion para radicar el documento
  //*********************************************

  $permitir_radicar = $tpradic != 2;

  if (intval($tpradic) != 0) {
  if ($permitir_radicar and $es_extension){

      if (!$rs->fields["RADI_NUME_SALIDA"]){
        if(((($num_archivos>=2 or $tieneAnexoSalida==1) and (substr($verrad,-1)!=2 )) or (substr($verrad,-1) == 2 or (substr($verrad,-1) == 9) or $num_archivos>=2 ))){
        echo "<a class=\"vinculos\" href=\"JavaScript:void(0);\" onclick=\"radicarArchivo('$coddocu','$linkarchivo','si',0,'$tpradic','$aplinteg','$numextdoc');\"> <img src='img/icono_radicar.png' title='Generar Radicado (-$tpradic)'> </a>";

        $radicado = "false";
        $anexo = $cod_radi;
    }

    if((substr($verrad,-1)!=2) and (substr($verrad,-1)!=9) and $num_archivos==1 and $tieneAnexoSalida==0 ){
      echo "<a class=\"vinculos\" href=\"JavaScript:void(0);\" onclick=\"asignarRadicado('$coddocu','$linkarchivo','$cod_radi','$numextdoc');\"> <img src='img/icono_asignar.png' title='Asignar Radicado (-$tpradic)'> </a>";
      $radicado = "false";
      $anexo = $cod_radi;
    } else if (strcmp($cod_radi,$rs->fields["SGD_DOC_PADRE"])==0){
      echo "<a class=\"vinculos\" href=\"JavaScript:void(0);\" onclick=\"radicarArchivo('$coddocu','$linkarchivo','si',0,'$tpradic','$aplinteg','$numextdoc');\"> <img src='img/icono_radicar.png' title='Generar Radicado (-$tpradic)'> </a>";
      $radicado = "false";
      $anexo = $cod_radi;
     }
    }else{
      if ($anex_estado<4){
        echo "<a class=vinculos href=\"JavaScript:void(0);\" onclick=\"radicarArchivo('$coddocu','$linkarchivo','$cod_radi',0,'','',$numextdoc);\"> <img src='img/icono_regenerar.png' title='Volver a Generar Radicado'></a>";

        }
	if ($db->entidad=="CRA" &&  $_SESSION["usua_perm_firma"]!=0){
		    echo "</td><td><a class='fa fa-pencil-square-o' href=\"JavaScript:void(0);\" onclick=\"radicarArchivoFirmado('$coddocu','$linkarchivo','$cod_radi',0,'','',$numextdoc);\"> </a></td><td>";

      }
    }
  }
 }
	  if($rs->fields["RADI_NUME_SALIDA"]) {$radicado="true";}
		?>
		</small></td>

	<?
	}else {
	?>
		<td >
		<?php
		if ( $origen!=1  and $linkarchivo and $perm_borrar_anexo == 1 && $anexTipo == 4 )
		{
			   $v = $rs->fields["SGD_PNUFE_CODI"];
			   echo "<a class=\"vinculoTipifAnex\" href=\"JavaScript:void(0);\" onclick=\"borrarArchivo('$coddocu','$linkarchivo','$cod_radi','$v');\"> <img src='img/icono_borrar.png' title='Borrar Archivo'> </a>";

		}else{
       echo "ddd";
		}
		if ( $perm_tipif_anexo == 1 && $anexTipo == 4 && $anexTPRActual == '' )
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo no ha sido tipificado
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi','$anexo',$codserie,$tsub);> <img src='img/icono_clasificar.png' title='Clasificar Documento'> </a> ";

		}elseif ( $perm_tipif_anexo == 1 && $anexTipo == 4 && $anexTPRActual != '' )
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo YA ha sido tipificado antes
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";


		}


	?>
	</small></td>

	<?php
	}
echo "";
	?>

</tr>
<?php
	$rs->MoveNext();
}
}

?>

</table>

<?php
if($verradPermisos == "Full"){
?>
<table  width="100%" align="center" class="table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
  <tr align="center">
     <td ><small>
		<? if ($permRespuesta == 1) { ?>
			<a class="titulos5" href="javascript:respuestaTx2()"> Respuesta en PDF</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
     <? } ?>
     <a class="vinculos" href='javascript:nuevoArchivo(<? if ( $num_archivos==0 && $swRadDesdeAnex==false)  echo "1"; else echo "0";  ?>)' class="timpar">
Anexar Archivo</a>
      </small></td>
  </tr>
</table>
<? }?>
<br><br>
<?php if($entidad == 'CRA'){$opt_ver_anexos_borrados=false;}
if ($opt_ver_anexos_borrados==true){?>
<button type="button" id="clickme"  class="btn btn-mostrar pr3">Mostrar Anexos borrados</button>
<div id="wrap_delete" style="display: none">
<?php
 //PERMISO PARA MIRAR LOS BORRADOS
include "$ruta_raiz/lista_anexos_borrados.php";
?>
</div>
<?php } ?>
<script language="javascript">

$( "#clickme" ).click(function() {
	$( "#wrap_delete" ).toggle( "slow" );
 });

	var swradics  = 0;
	var radicando = 0;

	function editar_anexo (anexo) {
		var valor = 0;
		var url         = "respuestaRapida/index2.php?<?=session_name()?>=" +
											"<?=session_id()?>&radicadopadre=" +
												+ <?=$verrad?> + "&krd=<?=$krd?>&editar=true&anexo=" +anexo;
		var params      = 'width='+screen.width;
				params      += ', height='+screen.height;
				params      += ', top=0, left=0'
				params      += ', scrollbars=yes'
				params      += ', fullscreen=yes';
		window.open(url, "Respuesta Rapida", params);
	}



	function verDetalles(anexo, tpradic, aplinteg, num){
			optAsigna = "";
			if (swradics==0){
					optAsigna="&verunico=1";
			}
			contadorVentanas=contadorVentanas+1;
			nombreventana="ventanaDetalles"+contadorVentanas;
			url="detalle_archivos.php?usua=<?=$krd?>&radi=<?=$verrad?>&anexo="+anexo+"&anex_codigo="+anexo;
			url="<?=$ruta_raiz?>/nuevo_archivo.php?codigo="+anexo+"&<?=session_name()."=".trim(session_id()) ?>&radSalida=<?=$radSalida?>&<?=$verrad?>&contra=<?=$drde?>&radi=<?=$verrad?>&tipo=<?=$tipo?>&ent=<?=$ent?><?=$datos_envio?>&ruta_raiz=<?=$ruta_raiz?>"+"&tpradic="+tpradic+"&aplinteg="+aplinteg+optAsigna;
			window.open(url,nombreventana,'top=0,height=780,width=870,scrollbars=yes,resizable=yes');
			return;
	}

function borrarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado){
			if (confirm('Estas seguro de borrar este archivo anexo ?')){
					contadorVentanas=contadorVentanas+1;
					nombreventana="ventanaBorrar"+contadorVentanas;
					//url="borrar_archivos.php?usua=<?=$krd?>&contra=<?=$drde?>&radi=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch;

					url="lista_anexos_seleccionar_transaccion.php?borrar=1&usua=<?=$krd?>&numrad=<?=$verrad?>&&contra=<?=$drde?>&radi=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"&numfe="+procesoNumeracionFechado+"&dependencia=<?=$dependencia?>&codusuario=<?=$codusuario?>";
					window.open(url,nombreventana,'height=100,width=180');
			}
			return;
	}
<?
if ($db->entidad=="CRA" &&  $_SESSION["usua_perm_firma"]!=0){
?>
function radicarArchivoFirmado(anexo,linkarch,radicar_a,procesoNumeracionFechado,tpradic,aplinteg,numextdoc,anexDepeCreador){
			var title1   = "Transaccion exitosa";
			var radinum;
			var title2   = "Errores en la transaccion";
			var tagalert = $( "<div>" ).addClass("alert alert-block")
											.html("<a class='close' data-dismiss='alert' href='#'>×</a>" +
											"<h4 class='alert-heading'><i class='fa fa-check-square-o'></i></h4>");

		var clave;
	  	clave=prompt('Ingrese su clave de firma digital:','');
			if (confirm('Se asignar\xe1 un n\xfamero de radicado a \xe9ste documento. Est\xe1 seguro  ?')){
					url = "?radicar=1&clave="+clave+"&radicar_a="+radicar_a+"&vp=n&<?="&".session_name()."=".trim(session_id())?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&numfe="+procesoNumeracionFechado+"&tpradic="+tpradic+"&aplinteg="+aplinteg+"&numextdoc="+numextdoc;
                    $('#tableDocument').addClass('widget-body-ajax-loading');
					$.post( "<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php" + url, function( data ) {

							if((data.success !== undefined) && (data.success.length>0)){
									var answer = content = '';
									for(var i=0;i < data.success.length; i++){
											var data_answer = " "+data.success[i]+" ";
											answer  = (answer.length>0)? answer + data_answer : data_answer ;
											radinum = data_answer.match(/[\d]{14}/);
											if((radinum !== undefined)  && (Array.isArray(radinum)) && (radinum.length > 0)){
													radinum = radinum[0];
											}
									}
									content  = $("<div></div>").html(answer);
									newalert = tagalert.clone();
									newalert.find('h4').html(title1);
									newalert.addClass('alert-success');
									newalert.removeClass('alert-danger');
									newalert.find('h4').after(content);
									var tdalert = $('<td colspan="16">').append(newalert);
									var tralert = $('<tr>').append(tdalert);
									$('#' + anexo).after(tralert);

									if(radinum){
											$($('#' + anexo).find('td')[2]).children().children().text(radinum);
									}
							}

							if((data.error !== undefined) && (data.error.length>0)){
									var answer  = '';
									var content = '';
									for(var i=0;i < data.error.length; i++){
											var data_answer = " "+data.error[i]+" ";
											answer = (answer.length>0)? answer + data_answer: data_answer ;
									}
									content  = $("<div></div>").html(answer);
									newalert = tagalert.clone();
									newalert.find('h4').html(title2);
									newalert.find('h4').after(content);
									newalert.addClass('alert-danger');
									newalert.removeClass('alert-success');
									var tdalert = $('<td colspan="16">').append(newalert);
									var tralert = $('<tr>').append(tdalert);
									$('#' + anexo).after(tralert);
							}
                        $('#tableDocument').removeClass('widget-body-ajax-loading');
					});
			};
	}
<?}?>


	function radicarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado,tpradic,aplinteg,numextdoc,anexDepeCreador){
			var title1   = "Transaccion exitosa";
			var radinum;
			var title2   = "Errores en la transaccion";
			var tagalert = $( "<div>" ).addClass("alert alert-block")
											.html("<a class='close' data-dismiss='alert' href='#'>×</a>" +
											"<h4 class='alert-heading'><i class='fa fa-check-square-o'></i></h4>");

			if (confirm('Se asignar\xe1 un n\xfamero de radicado a \xe9ste documento. Est\xe1 seguro  ?')){
					url = "?radicar=1&radicar_a="+radicar_a+"&vp=n&<?="&".session_name()."=".trim(session_id())?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&numfe="+procesoNumeracionFechado+"&tpradic="+tpradic+"&aplinteg="+aplinteg+"&numextdoc="+numextdoc;
                    $('#tableDocument').addClass('widget-body-ajax-loading');
					$.post( "<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php" + url, function( data ) {

							if((data.success !== undefined) && (data.success.length>0)){
									var answer = content = '';
									for(var i=0;i < data.success.length; i++){
											var data_answer = " "+data.success[i]+" ";
											answer  = (answer.length>0)? answer + data_answer : data_answer ;
											radinum = data_answer.match(/[\d]{14}/);
											if((radinum !== undefined)  && (Array.isArray(radinum)) && (radinum.length > 0)){
													radinum = radinum[0];
											}
									}
									content  = $("<div></div>").html(answer);
									newalert = tagalert.clone();
									newalert.find('h4').html(title1);
									newalert.addClass('alert-success');
									newalert.removeClass('alert-danger');
									newalert.find('h4').after(content);
									var tdalert = $('<td colspan="16">').append(newalert);
									var tralert = $('<tr>').append(tdalert);
									$('#' + anexo).after(tralert);

									if(radinum){
											$($('#' + anexo).find('td')[2]).children().children().text(radinum);
									}
							}

							if((data.error !== undefined) && (data.error.length>0)){
									var answer  = '';
									var content = '';
									for(var i=0;i < data.error.length; i++){
											var data_answer = " "+data.error[i]+" ";
											answer = (answer.length>0)? answer + data_answer: data_answer ;
									}
									content  = $("<div></div>").html(answer);
									newalert = tagalert.clone();
									newalert.find('h4').html(title2);
									newalert.find('h4').after(content);
									newalert.addClass('alert-danger');
									newalert.removeClass('alert-success');
									var tdalert = $('<td colspan="16">').append(newalert);
									var tralert = $('<tr>').append(tdalert);
									$('#' + anexo).after(tralert);
							}
                        $('#tableDocument').removeClass('widget-body-ajax-loading');
					});
			};
	}

	function asignarRadicado(anexo,linkarch,radicar_a, numextdoc){
      var title1   = "Transaccion exitosa";
      var radinum;
      var title2   = "Errores en la transaccion";
      var tagalert = $( "<div>" ).addClass("alert alert-block")
                      .html("<a class='close' data-dismiss='alert' href='#'>×</a>" +
                      "<h4 class='alert-heading'><i class='fa fa-check-square-o'></i></h4>");

      if (confirm('Se asignar\xe1 un n\xfamero de radicado a \xe9ste documento. Est\xe1 seguro  ?')){
          url = "?numextdoc="+numextdoc+"&generar_numero=no&radicar=1&radicar_a="+radicar_a+"&vp=n&<?="&".session_name()."=".trim(session_id())?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>";
          $('#tableDocument').addClass('widget-body-ajax-loading');
          $.post( "<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php" + url, function( data ) {

              if((data.success !== undefined) && (data.success.length>0)){
                  var answer = content = '';
                  for(var i=0;i < data.success.length; i++){
                      var data_answer = " "+data.success[i]+" ";
                      answer  = (answer.length>0)? answer + data_answer : data_answer ;
                      radinum = data_answer.match(/[\d]{14}/);
                      if((radinum !== undefined)  && (Array.isArray(radinum)) && (radinum.length > 0)){
                          radinum = radinum[0];
                      }
                  }
                  content  = $("<div></div>").html(answer);
                  newalert = tagalert.clone();
                  newalert.find('h4').html(title1);
                  newalert.addClass('alert-success');
                  newalert.removeClass('alert-danger');
                  newalert.find('h4').after(content);
                  var tdalert = $('<td colspan="16">').append(newalert);
                  var tralert = $('<tr>').append(tdalert);
                  $('#' + anexo).after(tralert);

                  if(radinum){
                      $($('#' + anexo).find('td')[2]).children().children().text(radinum);
                  }
              }

              if((data.error !== undefined) && (data.error.length>0)){
                  var answer  = '';
                  var content = '';
                  for(var i=0;i < data.error.length; i++){
                      var data_answer = " "+data.error[i]+" ";
                      answer = (answer.length>0)? answer + data_answer: data_answer ;
                  }
                  content  = $("<div></div>").html(answer);
                  newalert = tagalert.clone();
                  newalert.find('h4').html(title2);
                  newalert.find('h4').after(content);
                  newalert.addClass('alert-danger');
                  newalert.removeClass('alert-success');
                  var tdalert = $('<td colspan="16">').append(newalert);
                  var tralert = $('<tr>').append(tdalert);
                  $('#' + anexo).after(tralert);
              }

              $('#tableDocument').removeClass('widget-body-ajax-loading');

          });
      };
  }

	function numerarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado){
			if (confirm('Se asignar\xe1 un n\xfamero a \xe9ste documento. Est\xe1 seguro ?'))
			{
					contadorVentanas=contadorVentanas+1;
					nombreventana="mainFrame";
					url="<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php?numerar=1"+"&vp=n&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&numfe="+procesoNumeracionFechado;
					window.open(url,nombreventana,'height=450,width=600');
			}
			return;
	}

	function aasignarRadicado(anexo,linkarch,radicar_a,numextdoc){

    if (radicando>0){
      alert ("Ya se esta procesando una radicacion, para re-intentarlo hagla click sobre la pesta�a de documentos");
      return;
    }

    radicando++;

    if (confirm('Esta seguro de asignarle el numero de Radicado a este archivo ?'))
    {
      contadorVentanas=contadorVentanas+1;
      nombreventana="mainFrame";
      url="<?=$ruta_raiz?>/genarchivo.php?generar_numero=no&radicar_a="+radicar_a+"&vp=n&<?="&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>"+"&numextdoc="+numextdoc;
      window.open(url,nombreventana,'height=450,width=600');
    }
  return;
  }

	function ver_tipodocuATRD(anexo,codserie,tsub){
			<?php
						$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado
												WHERE RADI_NUME_RADI = '$numrad'";
						$rsDepR = $db->conn->Execute($isqlDepR);
						$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
						$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
						$ind_ProcAnex="S";
			?>
			window.open("./radicacion/tipificar_documento.php?<?=session_name()."=".session_id()?>&krd=<?=$krd?>&nurad="+anexo+"&ar="+anexo+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&tsub="+tsub+"&codserie="+codserie+"&texp=<?=$texp?>","Tipificacion_Documento_Anexos","height=500,width=750,scrollbars=yes");
	}

	function noPermiso(){
			alert ("No tiene permiso para acceder");
	}

	function ver_tipodocuAnex(cod_radi,codserie,tsub)
	{

			window.open("./radicacion/tipificar_anexo.php?krd=<?=$krd?>&nurad="+cod_radi+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&tsub="+tsub+"&codserie="+codserie,"Tipificacion_Documento_Anexos","height=300,width=750,scrollbars=yes");
	}

	function sendMail(cod_radi,radPadre,codAnexo,tsub)
  {
      window.open("./respuestaRapida/sendMail.php?nurad="+cod_radi+"&coddepe=<?=$coddepe?>&radPadre="+radPadre+"&codAnexo="+codAnexo+"&tsub=","...:::: Envio de Mail :::...","height=500,width=800,top=200,left=100,scrollbars=yes");
  }

	function vistaPreliminar(anexo,linkarch,linkarchtmp){
        var tagalert = $( "<div>" ).addClass("alert alert-block")
            .html("<a class='close' data-dismiss='alert' href='#'>×</a>" +
                "<h4 class='alert-heading'><i class='fa fa-check-square-o'></i></h4>");

        var title1   = "Transaccion exitosa";

		url  =  "<?=$ruta_raiz?>/genarchivo.php?vp=s&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"&linkarchivotmp="+linkarchtmp+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>";
        $('#tableDocument').addClass('widget-body-ajax-loading');
        $.post( url, function( data ) {

            if((data.success !== undefined) && (data.success.length>0)){
                var answer = content = '';
                for(var i=0;i < data.success.length; i++){
                    var data_answer = " "+data.success[i]+" ";
                    answer  = (answer.length>0)? answer + data_answer : data_answer ;
                    radinum = data_answer.match(/[\d]{14}/);
                    if((radinum !== undefined)  && (Array.isArray(radinum)) && (radinum.length > 0)){
                        radinum = radinum[0];
                    }
                }
                content  = $("<div></div>").html(answer);
                newalert = tagalert.clone();
                newalert.find('h4').html(title1);
                newalert.addClass('alert-success');
                newalert.removeClass('alert-danger');
                newalert.find('h4').after(content);
                var tdalert = $('<td colspan="16">').append(newalert);
                var tralert = $('<tr>').append(tdalert);
                $('#' + anexo).after(tralert);

                if(radinum){
                    $($('#' + anexo).find('td')[2]).children().children().text(radinum);
                }
            }

            if((data.error !== undefined) && (data.error.length>0)){
                var answer  = '';
                var content = '';
                for(var i=0;i < data.error.length; i++){
                    var data_answer = " "+data.error[i]+" ";
                    answer = (answer.length>0)? answer + data_answer: data_answer ;
                }
                content  = $("<div></div>").html(answer);
                newalert = tagalert.clone();
                newalert.find('h4').html(title2);
                newalert.find('h4').after(content);
                newalert.addClass('alert-danger');
                newalert.removeClass('alert-success');
                var tdalert = $('<td colspan="16">').append(newalert);
                var tralert = $('<tr>').append(tdalert);
                $('#' + anexo).after(tralert);
            }
        });
	}

	function nuevoArchivo(asigna){
			contadorVentanas=contadorVentanas+1;
			optAsigna="";
			if (asigna==1){
					optAsigna="&verunico=1";
			}

			nombreventana="ventanaNuevo"+contadorVentanas;
			url="<?=$ruta_raiz?>/nuevo_archivo.php?codigo=&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&usua=<?=$krd?>&numrad=<?=$verrad ?>&contra=<?=$drde?>&radi=<?=$verrad?>&tipo=<?=$tipo?>&ent=<?=$ent?>"+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&tdoc=<?=$tdoc?>&nuevo_archivo=true"+optAsigna;
			window.open(url,nombreventana,'height=730,width=840,scrollbars=yes,resizable=yes');
			return;
	}


	function nuevoEditWeb(asigna){
			contadorVentanas=contadorVentanas+1;
			optAsigna="";
			if (asigna==1){
					optAsigna="&verunico=1";
			}

			nombreventana="ventanaNuevo"+contadorVentanas;
			url="<?=$ruta_raiz?>/edicionWeb/editorWeb.php?codigo=&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&usua=<?=$krd?>&numrad=<?=$verrad ?>&contra=<?=$drde?>&radi=<?=$verrad?>&tipo=<?=$tipo?>&ent=<?=$ent?>"+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&tdoc=<?=$tdoc?>"+optAsigna;
			window.open(url,nombreventana,'height=800,width=700,scrollbars=yes,resizable=yes');
			return;
	}

	function Plantillas(plantillaper1){
			if(plantillaper1==0){
					plantillaper1="";
			}
			contadorVentanas=contadorVentanas+1;
			nombreventana="ventanaNuevo"+contadorVentanas;
			urlp="plantilla.php?<?="krd=$krd&".session_name()."=".trim(session_id()); ?>&verrad=<?=$verrad ?>&numrad=<?=$numrad ?>&plantillaper1="+plantillaper1;
			window.open(urlp,nombreventana,'top=0,left=0,height=800,width=850');
			return;
	}

	function Plantillas_pb(plantillaper1){
			if(plantillaper1==0){
					plantillaper1="";
			}
			contadorVentanas=contadorVentanas+1;
			nombreventana="ventanaNuevo"+contadorVentanas;
			urlp="crea_plantillas/plantilla.php?<?="krd=$krd&".session_name()."=".trim(session_id()); ?>&verrad=<?=$verrad ?>&numrad=<?=$numrad ?>&plantillaper1="+plantillaper1;
			window.open(urlp,nombreventana,'top=0,left=0,height=800,width=850');
			return;
	}

	function respuestaTx2(){
			var valor = sw = 0;
			var params      = 'width='+screen.width;
					params      += ', height='+screen.height;
					params      += ', top=0, left=0'
					params      += ', scrollbars=yes'
					params      += ', fullscreen=yes';

		<?if(!$verrad){?>
					for(i=1;i<document.form1.elements.length;i++){
							if (document.form1.elements[i].checked && document.form1.elements[i].name!="checkAll"){
									sw++;
									valor = document.form1.elements[i].name;
									valor = valor.replace("checkValue[", "");
									valor = valor.replace("]", "");
							}
					}

					if (sw != 1) {
							alert("Debe seleccionar UN(1) radicado");
							return;
					}


					var url         = "respuestaRapida/index2.php?<?=session_name()?>=" +
														"<?=session_id()?>&radicadopadre=" +
															+ valor + "&krd=<?=$krd?>&editar=false";
					window.open(url, "Respuesta Rapida", params);

		<?}else{?>
		window.open("respuestaRapida/index2.php?<?=session_name()?>=<?=session_id()?>&radicado=" +
								'<?php print_r($verrad) ?>' + "&radicadopadre=" + '<?php print_r($verrad) ?>' +
								"&asunto=" + '<?php print_r($rad_asun_res)?>' +
									"&krd=<?=$krd?>&editar=false", "Respuesta Rapida", params);
		<?}?>
	}

	function funlinkArchivo(numrad,rutaRaiz){
		  nombreventana="linkVistArch"+numrad;
			url=rutaRaiz + "/linkArchivo.php?"+"&PHPSESSID=140522122803o127001ADMON&numrad="+numrad;
		    //url=rutaRaiz + "/linkArchivo.php?"+numrad;
			ventana = window.open(url,nombreventana,'scrollbars=1,height=50,width=250');
			//setTimeout(nombreventana.close, 70);
			return;
	}

	function noPermiso(){
			alert ("No tiene permiso para acceder");
	}

	function abrirArchivo(url){
			nombreventana='Documento';
			window.open(url, nombreventana,  'status, width=900,height=500,screenX=100,screenY=75,left=50,top=75');
			return;
	}
 <?php // include_once "$ruta_raiz/js/funtionImage.php"; ?>
