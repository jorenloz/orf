<?php
session_start();
$ruta_raiz = ".";

if (!$_SESSION['dependencia']) header ("Location: $ruta_raiz/cerrar_session.php");

include_once "include/tx/sanitize.php";

$krd                = $_SESSION["krd"];
$dependencia        = $_SESSION["dependencia"];
$usua_doc           = $_SESSION["usua_doc"];
$codusuario         = $_SESSION["codusuario"];
$tip3Nombre         = $_SESSION["tip3Nombre"];
$tip3desc           = $_SESSION["tip3desc"];
$tip3img            = $_SESSION["tip3img"];
$tpNumRad           = $_SESSION["tpNumRad"];
$tpPerRad           = $_SESSION["tpPerRad"];
$tpDescRad          = $_SESSION["tpDescRad"];
$usuaPermExpediente = $_SESSION["usuaPermExpediente"];
$nomcarpeta=$_GET["nomcarpeta"];
$verradicado = $_GET['verrad'];

if (!$ent)         $ent           = substr($verradicado, -1 );
if(!$menu_ver_tmp) $menu_ver_tmp  = $menu_ver_tmpOld;
if(!$menu_ver)     $menu_ver      = $menu_ver_Old;
if(!$menu_ver)     $menu_ver      = 3;
if($menu_ver_tmp)	 $menu_ver      = $menu_ver_tmp;

if (!defined('ADODB_ASSOC_CASE')) define('ADODB_ASSOC_CASE', 1);
include "./config.php";
include_once "./include/db/ConnectionHandler.php";

if($verradicado)	$verrad= $verradicado;
if(!$ruta_raiz)	$ruta_raiz=".";
$numrad = $verrad;

$db = new ConnectionHandler('.');
//$db->conn->debug = true;
$db->conn->SetFetchMode(3);

if($carpeta==8){	$info=8;
	$nombcarpeta = "Informados";
}
include_once "$ruta_raiz/config.php";
include_once "$ruta_raiz/class_control/Radicado.php";

$objRadicado = new Radicado($db);
$objRadicado->radicado_codigo($verradicado);
$path = $objRadicado->getRadi_path();

// include_once "$ruta_raiz/tx/verLinkArchivo.php";
// $verLinkArchivo = new verLinkArchivo($db);

/** verificacion si el radicado se encuentra en el usuario Actual*/
include "$ruta_raiz/tx/verifSession.php";

/* Segunda verificacion del radicado*/

$SeguridadRadicado = $objRadicado->getSpubCodigoRad();
$RadiDepeActuRAd   = $objRadicado->getRadiDepeActuRad();
$RadiUsuaActuRad   = $objRadicado->getRadiUsuaActuRad();

?>
<html><head><title>.: Modulo total :.</title>
<?php include_once "htmlheader.inc.php"; ?>
<!-- seleccionar todos los checkboxes-->

<?

if($SeguridadRadicado==0) {
//Publico
}elseif ($SeguridadRadicado==1){
//
?>
<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>¡Aviso!</strong> No tiene permiso para acceder a este radicado; Privado solo jefe y Ususario Actual
</div>

<?
}elseif ($SeguridadRadicado==2){
///
?>
<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>¡Aviso!</strong> No tiene permiso para acceder a este radicado; Privado dependencia (Solo usuarios Dependencias)
</div>

<?

}

?>
<?php include_once "$ruta_raiz/js/funtionImage.php"; ?>
<script language="javascript">
function datosbasicos(){
	window.location='radicacion/NEW.PHP?<?=session_name()."=".session_id()?>&<?="nurad=$verrad&fechah=$fechah&ent=$ent&Buscar=Buscar Radicado&carpeta=$carpeta&nomcarpeta=$nomcarpeta"; ?>';
}
function mostrar(nombreCapa)
{
	document.getElementById(nombreCapa).style.display="";
}
function ocultar(nombreCapa)
{
	if(document.getElementById(nombreCapa) != null)
	document.getElementById(nombreCapa).style.display="none";
}
var contadorVentanas=0
<?

if($dependencia==900 ) $verradPermisos = "Full";

if($entidad=="CNSC" AND $dependencia==600 and ($codusuario==284 || $codusuario==299 || $codusuario==290 || $codusuario==298))  $verradPermisos = "Full";

if($carpeta==8 || $carpeta==66 )  $verradPermisos = "Full";

if($verradPermisos == "Full" or $datoVer=="985")
{	if($datoVer=="985")
	{
?>

function  window_onload(){
  <?	if($verradPermisos == "Full" or $datoVer=="985"){ ?>
  window_onload2();
  <?  } ?>
}

<?
}
}
else
{
?>
function changedepesel(xx)
{
}
<?
}
?>

function ver_tipodocuTRD(codserie,tsub)
 {
   <?php
 		//echo "ver_tipodocumental(); ";
 		$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado WHERE RADI_NUME_RADI = '$numrad'";
 		$rsDepR = $db->conn->Execute($isqlDepR);
 		$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
 		$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
 		$ind_ProcAnex="N";
   ?>
   window.open("./radicacion/tipificar_documento.php?nurad=<?=$verrad?>&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>&tsub="+tsub+"&codserie="+codserie,"Tipificacion_Documento","height=600,width=850,scrollbars=yes");
 }


function ver_temas(){
window.open("./tipo_documento.php?verrad=<?=$verrad?>","Temas","height=350,width=450,scrollbars=yes");
}



</script>
<script src="tooltips/jquery-ui.js"></script>
<link rel="stylesheet" href="tooltips/tool.css">
<script src="tooltips/tool.js"></script>
<link rel="stylesheet" href="estilos/cogeinsas.css">
</head>
<body >
<?
	$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
	$fechah=date("dmy_h_m_s") . " ". time("h_m_s");
	$check=1;
	$numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;
    include_once("ver_datosrad.php");
    $_SESSION['dir_doc_us1'] = $cc_documento_us1;
    $_SESSION['dir_doc_us2'] = $cc_documento_us2;
	if($verradPermisos == "Full" or $datoVer=="985" or $_SESSION["nivelus"] == 5) {
   // No hace ni un carajo!!! Mal escrito Atte Cmauricio
// Es la manera de trabajar con operadores logicos , con negacion y tres or. si funciona
  } else {
 			$numRad = $verrad;
 			if($nivelRad==1) include "$ruta_raiz/seguridad/sinPermisoRadicado.php";
 			if($nivelRad==1) die("-");
 		}
 ?>
<table width=100%   class="table table-striped table-bordered table-hover dataTable ">
 <tr>
   <td >
<?
 if($krd)
	{
	$isql = "select * From usuario where USUA_LOGIN ='$krd' and USUA_SESION='". substr(session_id(),0,29)."' ";
	$rs = $db->conn->query($isql);
	if (($krd))
	{
  ?><small>
	DOCUMENTO N.
	<?
	$ent=substr($verrad, -1);
	if ($mostrar_opc_envio==0 and $carpeta!=8 and !$agendado and $verradPermisos=="Full" and ($ent!=2 or $_SESSION["usua_perm_modifica"]==1)){

    echo "<a title='Click para modificar el Documento' href='./radicacion/NEW.php?nurad=$verrad&Buscar=BuscarDocModUS&".session_name()."=".session_id()."&Submit3=ModificarDocumentos&Buscar1=BuscarOrfeo78956jkgf' notborder >$verrad <img src='img/icono_modificar_radicado.png' title='Modificar Datos/Remitentes del Radicado'></a>";
	}else echo $verrad;

  if($tpPerRad[2]==1 or $tpPerRad[2]==3){
	 $varEnvio  = session_name()."=".session_id()."&nurad=$verrad&ent=$ent";
	 echo "<a href=\"javascript:void(0);\" onClick=\"window.open ('./radicacion/stickerWeb/index.php?$varEnvio&alineacion=Center','sticker$verrad','menubar=0,resizable=0,scrollbars=0,width=450,height=180,toolbar=0,location=0');\" class='btn btn-link'><img src='img/icono_radicar.png'></a>";
  }
    /*
     *  Modificado: 15-Agosto-2006 Supersolidaria
     *  Muestra el numero del expediente al que pertenece el radicado.
	 */
	if( $numExpediente && $_GET['expIncluido'][0] == "" )
    {
        echo "<span class=noleidos>&nbsp;&nbsp;&nbsp;PERTENECIENTE AL EXPEDIENTE No. ". ( $_SESSION['numExpedienteSelected'] != "" ?  $_SESSION['numExpedienteSelected'] : $numExpediente )."</span>";
	}
    else if( $_GET['expIncluido'][0] != "" )
	{
        echo "<span class=noleidos>&nbsp;&nbsp;&nbsp;PERTENECIENTE AL EXPEDIENTE No. ".$_GET['expIncluido'][0]."</span>";
        $_SESSION['numExpedienteSelected'] = $_GET['expIncluido'][0];
	}

	?>
	</small></td>
	<td class="botonSuperior" >
			<div>
      	<a class="vinculos " href='./solicitar/Reservas.php?radicado=<?="$verrad"?>'>Solicitados</a>
			</div>

	</td>

    <td class="botonSuperior">
      <a class="vinculos" href='./solicitar/Reservar.php?radicado=<?="$verrad&sAction=insert"?>&numExpediente=<?=$numExpediente?>'>Solicitar Fisico</a>
    </td>
  </tr>
</table>
<?
$datosaenviar = "fechaf=$fechaf&mostrar_opc_envio=$mostrar_opc_envio&tipo_carp=$tipo_carp&carpeta=$carpeta&nomcarpeta=$nomcarpeta&datoVer=$datoVer&ascdesc=$ascdesc&orno=$orno";
?>
<form name="form1" id="form1" action="<?=$ruta_raiz?>/tx/formEnvio.php?<?=session_name()?>=<?=session_id()?>" method="GET" class="smart-form">
<?php
if($verradPermisos=="Full" && !($carpeta==66 || $carpeta==8 ) )
{
}
?>
<input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'>
<input type=hidden name=enviara value='9'>
<input type=hidden name=codTx value=''>
<input id="chkr" type="checkbox" value="CHKANULAR" name="checkValue[<?=$verradicado?>]" checked  >
 <?
echo "<input type='hidden' name='fechah' value='$fechah'>";
// Modificado Infom�trika 22-Julio-2009
// Compatibilidad con register_globals = Off.
print "<input type='hidden' name='verrad' value='".$verrad."'>";
if($flag==2)
	{
	echo "<CENTER>NO SE HA PODIDO REALIZAR LA CONSULTA<CENTER>";
	}
else
	{
	$row = array();
	$row1 = array();
	if($info)
	{
		$row["INFO_LEIDO"]=1;
		$row1["DEPE_CODI"] = $dependencia;
		$row1["USUA_CODI"] = $codusuario;
		$row1["RADI_NUME_RADI"] = $verrad;
		$rs = $db->update("informados", $row, $row1);
	}
	elseif (($leido!="no" or !$leido) and $datoVer!=985)
	{
		$row["RADI_LEIDO"]=1;
		$row1["radi_depe_actu"] = $dependencia;
		$row1["radi_usua_actu"] = $codusuario;
		$row1["radi_nume_radi"] = $verrad;
		$rs = $db->update("radicado", $row, $row1);
	}
}
include_once("ver_datosrad.php");
include_once ("ver_datosgeo.php");
$tipo_documento .= "<input type=hidden name=menu_ver value='".$menu_ver."'>";
$hdatos = session_name()."=".session_id()."&leido=$leido&nomcarpeta=$nomcarpeta&tipo_carp=$tipo_carp&carpeta=$carpeta&verrad=$verrad&datoVer=$datoVer&fechah=fechah&menu_ver_tmp=";
	}else {
	?>
</form>
<form name='form11' action='enviar.php' method='GET'>
<input type="hidden" name="depsel">$nomRemDes
<input type="hidden" name="depsel8">
<input type="hidden" name="carpper">
<CENTER>
	<span class="titulosError">SU SESION HA TERMINADO O HA SIDO INICIADA EN OTRO EQUIPO</span><BR>
	<span class="eerrores">
	</CENTER>
<?PHP
	}
}else {
  header ("Location: $ruta_raiz/cerrar_session.php");
}
 echo "<div class='actions2'>";
 include_once("$ruta_raiz/tx/txOrfeo.php");
 echo "</div>";
?>
</form>
<!-- row -->
 <input type=hidden name=reftab id=reftab >
	<div class="well well-sm well-light">
		<div id="tabs">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a data-toggle="tab" href="#tabs-d">Informaci&oacute;n del Radicado</a>
				</li>
				<li  data-toggle="tab" class="nav-item">
					<a href="#tabs-b">Historico</a>
				</li>
				<li  data-toggle="tab" class="nav-item">
					<a href="#tabs-c">Documentos Anexos</a>
				</li>
				<li  data-toggle="tab" class="nav-item">
					<a href="#tabs-a">Expediente</a>
				</li>
        <? if (!empty($servidorGis)){ ?>
                <li  class="nav-item">
              <a data-toggle="tab" href="#tabs-gis">Gis</a> 
                </li>
        <?}?>
			</ul>
			<ul>
			<div id="tabs-a">
			  <center><img src="img/ajax-loader.gif" align=center></center>
				<?php  // include "./expediente/lista_expedientes.phppp"; ?>
			</div>
			<div id="tabs-d">
					<?php include "lista_general.php"; ?>
			</div>
			<div id="tabs-b">
				<p>
					<center><img src="img/ajax-loader.gif" align=center></center>
				</p>
			</div>

			<div id="tabs-c">
				<p>
					<?php
            if($recargartab){
                // include "./lista_anexos.php";
            }else{
				      echo "<center><img src='img/ajax-loader.gif' align=center width=70></center>";
            }
          ?>
				</p>
			</div>

<? if (!empty($servidorGis)){ ?>
				<div id="tabs-gis" width="100%">
				<?php echo "$servidorGis"; include "./gis/verGis.php"; ?>
			</div>
<?}?>
			</ul>
		</div>
  </div>

  <script>
</script>

<script type="text/javascript">
$( document ).ready(function(){

    $.fn.cargarPagina = function (pagina,nombreDiv){
        $.post( pagina,{verradicado:"<?=$verradicado?>",verradPermisos:"<?=$verradPermisos?>",permRespuesta:"<?=$permRespuesta?>"}, function( data ) {
            $('#'+ nombreDiv).html(data);
        })
    };

	function cargarPagina(pagina,nombreDiv){
        $.post( pagina,{verradicado:"<?=$verradicado?>",verradPermisos:"<?=$verradPermisos?>",permRespuesta:"<?=$permRespuesta?>"}, function( data ) {
          $('#'+ nombreDiv).html(data);
        });
    }
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	$('#tabs').tabs();
    $( "#tabs" ).on( "tabsactivate", function( event, ui ) {
        //window.location.href = ui.newTab.find('a.ui-tabs-anchor').attr('href');
        if($(ui.newTab).attr('aria-controls')=='tabs-b') cargarPagina('./ver_historico.php','tabs-b');
        if($(ui.newTab).attr('aria-controls')=='tabs-c') cargarPagina('./lista_anexos.php','tabs-c');
        if($(ui.newTab).attr('aria-controls')=='tabs-a') cargarPagina('./expediente/lista_expedientes.php','tabs-a');
        console.log(window.location.href);
	} );
	var map2 ={16: false, 65: false, 69: false, 72: false, 73: false};         
	document.addEventListener('keydown',  function( e ) {
		//window.location.href =ui.newTab.find('a.ui-tabs-anchor').attr('href');
		if (e.keyCode in map2){
			map2[e.keyCode] = true;
			if(map2[16] && map2[65]){
				$('#ui-id-3').trigger('click');
			}
			if(map2[16] && map2[69]){
				$('#ui-id-4').trigger('click');
			}
			if(map2[16] && map2[72]){
				$('#ui-id-2').trigger('click');
			}
			if(map2[16] && map2[73]){
				$('#ui-id-1').trigger('click');
			}
		}
	} );
	document.addEventListener('keyup', function(e){
		if (e.keyCode in map2){
			map2[e.keyCode] = false;
		}
	});
	
	var tabTitle = $("#tab_title"), tabContent = $("#tab_content"), tabTemplate = "<li style='position:relative;'> <span class='air air-top-left delete-tab' style='top:7px; left:7px;'><button class='btn btn-xs font-xs btn-default hover-transparent'><i class='fa fa-times'></i></button></span></span><a href='#{href}'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; #{label}</a></li>", tabCounter = 2;

    $('#chkr, #depsel, #carpper, #Enviar').hide();
    cargarPagina('./ver_historico.php','tabs-b');
  });

</script>



</body>
</html>
