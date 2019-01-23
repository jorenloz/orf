<?php
//ini_set("display_errors",1);
 if(!$ruta_raiz) $ruta_raiz = ".";
 if(!$db) include "$ruta_raiz/conn.php";
	   require_once("$ruta_raiz/class_control/Transaccion.php");
		 require_once("$ruta_raiz/class_control/Dependencia.php");
		 require_once("$ruta_raiz/class_control/usuario.php");

	   error_reporting(7);
	   $trans = new Transaccion($db);
	   $objDep = new Dependencia($db);
	   $objUs = new Usuario($db);
	   $isql = "select USUA_NOMB from usuario where depe_codi=$radi_depe_actu and usua_codi=$radi_usua_actu";
	   $rs = $db->query($isql);
	   $usuario_actual = $rs->fields["USUA_NOMB"];
	   $isql = "select DEPE_NOMB from dependencia where depe_codi=$radi_depe_actu";
	   $rs = $db->query($isql);
	   $dependencia_actual = $rs->fields["DEPE_NOMB"];
	   $isql = "select USUA_NOMB from usuario where depe_codi=$radi_depe_radicacion and usua_codi=$radi_usua_radi";

	   $rs = $db->query($isql);
	   $usuario_rad = $rs->fields["USUA_NOMB"];
	   $isql = "select DEPE_NOMB from dependencia where depe_codi=$radi_depe_radicacion";
	   $rs = $db->query($isql);
	   $dependencia_rad = $rs->fields["DEPE_NOMB"];
?>
<table  width="80%"  align="center"  class="table table-bordered ">
  <tr   align="left" >
    <th class="tdprincipal" width=10% ><small>Usuario Actual</small></th>
    <td  width=15% align="left"><small><?=$usuario_actual?></small></td>
    <th class="tdprincipal"  width=10%><small>Dependencia Actual</small></th>
    <td  width=15%><small><?=$dependencia_actual?></small></td>
  </tr>
</table>
<table  width="100%" align="center" class="table table-striped table-hover table-bordered"  >
  <thead>
    <tr class="pr2" align="center">
      <th width=10%>DEPENDENCIA</th>
      <th width=35%>FECHA</th>
      <th   width=15%>TRANSACCIÓN</th>
      <th   width=15%>US. ORIGEN</th>
      <th   width=35%>COMENTARIO</th>
      <th   width=20%>US. DESTINO</th>
    </tr>
  </thead>
  <?
  $sqlFecha = $db->conn->SQLDate("d-m-Y H:i A","a.HIST_FECH");

	$isql = "select $sqlFecha AS HIST_FECH1
      , a.DEPE_CODI
			, a.USUA_CODI
			,a.RADI_NUME_RADI
			,a.HIST_OBSE
			,a.USUA_CODI_DEST
			,a.USUA_DOC
			,a.HIST_OBSE
			,a.SGD_TTR_CODIGO
			,a.HIST_DOC_DEST
			from hist_eventos a
		 where
			a.radi_nume_radi =$verrad
			order by hist_fech desc ";

	$i=1;
	//$db->conn->debug = true;
	$rs = $db->query($isql);
	IF($rs)
	{
    while(!$rs->EOF)
	 {
     $trans = new Transaccion($db);
     $objDep = new Dependencia($db);
     $objUs = new Usuario($db);
		$usua_doc_dest ="";
		$usua_doc_hist = "";
		$usua_nomb_historico = "";
		$usua_destino = "";
		$numdata =  trim($rs->fields["CARP_CODI"]);
		if($data =="") $rs1->fields["USUA_NOMB"];
	   		$data = "NULL";
		$numerot = $rs->fields["NUM"];
		$usua_doc_hist = $rs->fields["USUA_DOC"];
		$usua_codi_dest = $rs->fields["USUA_CODI_DEST"];
		$usua_dest=intval(substr($usua_codi_dest,3,3));
		$depe_dest=intval(substr($usua_codi_dest,0,3));
		$usua_codi = $rs->fields["USUA_CODI"];
		$depe_codi = $rs->fields["DEPE_CODI"];
		$codTransac = $rs->fields["SGD_TTR_CODIGO"];
		$descTransaccion = $rs->fields["SGD_TTR_DESCRIP"];
		$histDoctDest=$rs->fields["HIST_DOC_DEST"];
		$iconoLink = "";
		if($codTransac==67) $iconoLink = "<a href='#' Title='Ver Historico de Prestamos del radicado {$verrad}' onClick='verHistPrestamo($verrad);'><img src='img/icono_prestamo.png' width='23'></a>";
		if(!$codTransac) $codTransac = "0";
		$trans->Transaccion_codigo($codTransac);
		$objUs->usuarioDocto($usua_doc_hist);
		$objDep->Dependencia_codigo($depe_codi);
		/*******************Desarrollo para la CRA********************/
		/******Se evita el listar de los informados en historico******/
		//if ($entidad=="CRA" and $trans->getDescripcion()!='Informar' and $trans->getDescripcion()!='Borrar Informado'){
		/*************************************************************/
		error_reporting(7);
		if($carpeta==$numdata)
			{
			$imagen="usuarios.gif";
			}
		else
			{
			$imagen="usuarios.gif";
			}
		if($i!=10000)
			{
		?>
  <tr > <?
		    $i=1;
			}
			 ?>
    <td  ><small>
	<?=$objDep->getDepe_nomb()?></td>
    <td ></small>
	<?=$rs->fields["HIST_FECH1"]?>
 </td>
<td   ><small>
  <?=$trans->getDescripcion()?> <?=$iconoLink?>
</small></td>
<td   ><small>
   <?=$objUs->get_usua_nomb()?></small>
</td>
		<?
		 /**
			 *  Campo qque se limino de forma Temporal USUARIO - DESTINO
			 * <td class="celdaGris"  >
			 * <?=$usua_destino?> </td>
			 */
		?>
			 <td ><small><?=$rs->fields["HIST_OBSE"]?></small></td>

<td class="listado2"><?php
//$db->conn->debug = true;
$isqln = "select USUA_NOMB from usuario where USUA_DOC='".trim($histDoctDest)."'";
$uprs = $db->query($isqln);
$usuario_actual = $uprs->fields["USUA_NOMB"];
echo $usuario_actual;?></td>

  </tr>
  <?
  	//}
	$rs->MoveNext();
}
}
  // Finaliza Historicos
	?>
</table>
  <?
  //empieza datos de envio
include "$ruta_raiz/include/query/queryver_historico.php";

$isql = "select $numero_salida from anexos a where a.anex_radi_nume=$verrad";
$rs = $db->query($isql);
$radicado_d= "";
while(!$rs->EOF)
	{
		$valor = $rs->fields["RADI_NUME_SALIDA"];
		if(trim($valor))
		   {
		      $radicado_d .= "'".trim($valor) ."', ";
		   }
		$rs->MoveNext();
	}

$radicado_d .= "$verrad";
error_reporting(7);
//$db->conn->debug=true;
include "$ruta_raiz/include/query/queryver_historico.php";
$sqlFechaEnvio = $db->conn->SQLDate("d-m-Y H:i A","a.SGD_RENV_FECH");
$isql = "select $sqlFechaEnvio AS SGD_RENV_FECH,
		a.DEPE_CODI,
		a.USUA_DOC,
		a.RADI_NUME_SAL,
		a.SGD_RENV_NOMBRE,
		a.SGD_RENV_DIR,
		a.SGD_RENV_MPIO,
		a.SGD_RENV_DEPTO,
		a.SGD_RENV_PLANILLA,
		b.DEPE_NOMB,
		c.SGD_FENV_DESCRIP,
		$numero_sal,
		a.SGD_RENV_OBSERVA,
		a.SGD_DEVE_CODIGO,
		u.USUA_LOGIN
		from sgd_renv_regenvio a, dependencia b, sgd_fenv_frmenvio c, usuario u
		where
		a.radi_nume_sal in($radicado_d)
		AND a.depe_codi=b.depe_codi
		AND a.sgd_fenv_codigo = c.sgd_fenv_codigo
		and a.usua_doc=cast (u.usua_doc as numeric)
		order by a.SGD_RENV_FECH desc ";
$rs = $db->query($isql);
?>
 <table width="100%" align="center"   >
  <tr>
    <td height="25" class="titulos4 tdtop" align="center"><b>DATOS DE ENVIO</b></td>
  </tr>
</table>
<table width="80%"  align="center"  class="table table-bordered"  >
  <tr  class="pr2" align="center" >
    <td width=10% ><small><b>RADICADO </b></small></td>
    <td width=10% ><small><b>DEPENDENCIA</b></small></td>
    <td width=15% ><small><b>FECHA </b></small></td>
    <td width=15% ><small><b>DESTINATARIO</b></small></td>
    <td width=15% ><small><b>DIRECCION </b></small></td>
    <td width=15% ><small><b>DEPARTAMENTO </b></small></td>
    <td width=15% ><small><b>MUNICIPIO</b></small></td>
    <td width=15% ><small><b>TIPO DE ENVIO</b></small></td>
    <td width=5%  ><small><b> No. PLANILLA</b></small></td>
    <td width=15% ><small><b>OBSERVACIONES</b></small></td>
 <td  width=15%   ><small><b>Realizo Envio</b></small></td>
  </tr>
  <?
$i=1;
while(!$rs->EOF)
	{
	$radDev = $rs->fields["SGD_DEVE_CODIGO"];
	$radEnviado = $rs->fields["RADI_NUME_SAL"];
	if($radDev)
	{
		$imgRadDev = "<img src='$ruta_raiz/imagenes/devueltos.gif' alt='Documento Devuelto por empresa de Mensajeria' title='Documento Devuelto por empresa de Mensajeria'>";
	}else
	{
		$imgRadDev = "";
	}
	$numdata =  trim($rs->fields["CARP_CODI"]);
	if($data =="")
		$data = "NULL";
	//$numerot = $rs->RecordCount();
	if($carpeta==$numdata)
		{
		$imagen="usuarios.gif";
		}
	else
		{
		$imagen="usuarios.gif";
		}
	if($i==1)
		{
   ?>
  <tr > <?  $i=1;
			}
			 ?>
    <td  >
	<small><?=$imgRadDev?><?=$radEnviado?></small></td>
    <td  >
	<small><?=$rs->fields["DEPE_NOMB"]?></small></td>
    <td ><small>
	<?
		echo "<a class=vinculos href='./verradicado.php?verrad=$radEnviado&krd=$krd' target='verrad$radEnviado'><span class='timpar'>".$rs->fields["SGD_RENV_FECH"]."</span></a>";
	?></small> </td>
    <td ><small>
	<?=$rs->fields["SGD_RENV_NOMBRE"]
	?> </small></td>
    <td   >
	<small><?=$rs->fields["SGD_RENV_DIR"]?> </small></td>
    <td   >
	 <small><?=$rs->fields["SGD_RENV_DEPTO"] ?> </small></td>
    <td   >
	 <small><?=$rs->fields["SGD_RENV_MPIO"] ?> </small></td>
    <td   >
	 <small><?=$rs->fields["SGD_FENV_DESCRIP"] ?> </small></td>
    <td   >
	 <small><?=$rs->fields["SGD_RENV_PLANILLA"] ?> </small></td>
    <td   >
	 <small><?=$rs->fields["SGD_RENV_OBSERVA"] ?> </small></td>
   <td   >
         <small><?=$rs->fields["USUA_LOGIN"] ?> </small></td>

  </tr>
  <?
	$rs->MoveNext();
  }

  // Finaliza Historicos
	?>
</table>
<script>
 function verHistPrestamo(radicado){
  window.open('prestamo/historico.php?datoRadicado=QWER2345SDB134123412C1234VFG5SERSH654E465G45G6235G63456&radicado='+radicado, 'Historico de Prestamo de '+radicado , 'width=650,height=500,addressbar=no,top=200,left=300');
 }
</script>
</body>
</html>
