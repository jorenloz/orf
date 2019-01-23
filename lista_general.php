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

    $ruta_raiz = ".";
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

$lkGenerico = "&usuario=$krd&nsesion=".trim(session_id())."&nro=$verradicado"."$datos_envio";

	$sqlremitente = "select SGD_DIR_NOMBRE, SGD_DIR_NOMREMDES from SGD_DIR_DRECCIONES t where t.radi_nume_radi = '$numrad'";
	$rsRemitente = $db->conn->Execute($sqlremitente);
	$SGD_DIR_NOMBRE = $rsRemitente->fields['SGD_DIR_NOMREMDES'];
	$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU, RADI_DATO_001, RADI_DATO_002 from radicado	WHERE RADI_NUME_RADI = '$numrad'";
	$rsDepR = $db->conn->Execute($isqlDepR);
	$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
	$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
	$radi_dato_001 = $rsDepR->fields['RADI_DATO_001'];
	$radi_dato_002 = $rsDepR->fields['RADI_DATO_002'];
	$ind_ProcAnex="N";
?>
<SCRIPT>
function regresar() {	//window.history.go(0);
	window.location.reload();
  //window.location.href='<a href="#" ></a>'
}
function CambiarE(est,numeroExpediente) {
        window.open("<?=$ruta_raiz?>/archivo/cambiar.php?<?=session_name()?>=<?=session_id()?>&numRad=<?=$verrad?>&expediente="+ numeroExpediente +"&est="+ est +"&","Cambio Estado Expediente","height=100,width=100,scrollbars=yes");
}

function modFlujo(numeroExpediente,texp,codigoFldExp)
{

window.open("<?=$ruta_raiz?>/flujo/modFlujoExp.php?<?=session_name()?>=<?=session_id()?>&codigoFldExp="+codigoFldExp+"&numRad=<?=$verrad?>&texp="+texp+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>","TexpE<?=$fechaH?>","height=250,width=750,scrollbars=yes");
}

function verVinculoDocto(){
    window.open("./vinculacion/mod_vinculacion.php?verrad=<?=$verrad?>&codusuario=<?=$codusuario?>&dependencia=<?=$dependencia?>","Vinculacion_Documento","height=500,width=750,scrollbars=yes");
}
function update_cExp(){
	$.post("<?=$ruta_raiz?>/include/tx/comiteExpertos.php",{numRad: <?=$numrad?>});
}
function cambiarFechaVencimiento(){

window.open("<?=$ruta_raiz?>/tx/cambiarFechaVencimiento.php?<?=session_name()?>=<?=session_id()?>&radi_fech_vcmto=<?=$radi_fech_vcmto?>&numRad=<?=$verrad?>&codusua=<?=$codusua?>","TexpE<?=$fechaH?>","height=250,width=750,scrollbars=yes");


}

</script>
<BODY>
<table class="table bordered">
<tr  >
<td class="tdprincipal"><small><b>Asunto</b></small></td><td><small><?=$ra_asun ?></small></td>
<td class="tdprincipal"><small><b>Fecha </b></small></td><td><small><?=$radi_fech_radi ?>&nbsp;&nbsp;</small></td>

<td class="tdprincipal"><small><b>Fecha Vencimiento</b></small></td><td><small> <?=$radi_fech_vcmto ?> &nbsp;&nbsp;</small>
 <?php
   if($codusuario==1){
 ?>
<input type=button name=CambiarFechaV value='...'  class='btn btn-primary btn-xs btn-rd'  onClick='cambiarFechaVencimiento();'>
 <?php } ?>
</td>


</tr>
<tr  cellspace=0 cellpad=0>
<td class="tdprincipal"><small><b>  Folios</b></small></td><td><small><?=$radi_nume_folio?>/<?=$radi_nume_hoja ?> </small></td><td class="tdprincipal"><small><b>   Anexos</b></small></td><td><small> <?=$radi_nume_anexo?></small></td><td></td><td></td>
<tr>
<td class="tdprincipal"><small><b>
Descripci&oacute;n Anexos</b></small></td><td><small> <?=$radi_desc_anex ?></small></td><td class="tdprincipal"><small><b> Anexo/Asociado</b></small></td><td><small>
	<?PHP
	if($radi_tipo_deri!=1 and $radi_nume_deri)
	   {	echo $radi_nume_deri;
           	 /*
		  * Modificacion acceso a documentos
		  * @author Liliana Gomez Velasquez
		  * @since 10 noviembre 2009
		 */
		 $resulVali = $verLinkArchivo->valPermisoRadi($radi_nume_deri);
     $verImg = $resulVali['verImg'];
		 if ($verImg == "SI"){
		        echo "<br>(<a class='vinculos' href='$ruta_raiz/verradicado.php?verrad=$radi_nume_deri &session_name()=session_id()' target='VERRAD$radi_nume_deri_".date("Ymdhi")."'>Ver Datos</a>)";}
                 else {
                      echo "<br>(<a class='vinculos' href='javascript:noPermiso()'> Ver Datos</a>)";
                 }
	   }
	 if(($verradPermisos == "Full" and $coddepe!='999') or $datoVer=="985")
		{
	?>
		<input type=button name=mostrar_anexo value='...'  class="btn btn-primary btn-xs btn-rd" onClick="verVinculoDocto();">
	<?
		}
	?>
</small></td><td class="tdprincipal"><small><b>Referencia / Oficio</b></small></td><td><small><?=$cuentai ?></small></td>
</tr>

    <?
		$muniCodiFac = "";
		$dptoCodiFac = "";
		if($sector_grb==6 and $cuentai and $espcodi)
		{	if($muni_us2 and $codep_us2)
			{	$muniCodiFac = $muni_us2;
				$dptoCodiFac = $codep_us2;
			}
			else
			{	if($muni_us1 and $codep_us1)
				{	$muniCodiFac = $muni_us1;
					$dptoCodiFac = $codep_us1;
				}
			}
	?>
		<a href="./consultaSUI/facturacionSUI.php?cuentai=<?=$cuentai?>&muniCodi=<?=$muniCodiFac?>&deptoCodi=<?=$dptoCodiFac?>&espCodi=<?=$espcodi?>" target="FacSUI<?=$cuentai?>"><span class="vinculos">Ver Facturacion</span></a>
	<?
		}
	?>
<tr><td class="tdprincipal"><small><b>Imagen</b></small></td><td><small>	<span class='vinculos'><?=$imagenv ?></span> </small></td>
<!--
<td><small><b>Estado Actual</b></small></td><td><small>
		<span ><?=$descFldExp?></span>&nbsp;&nbsp;&nbsp;
		<?
			if($verradPermisos == "Full" or $datoVer=="985")
	  		{
	  	?>
  <input type=button name=mostrar_causal value='...' class="btn btn-primary btn-xs btn-rd" onClick="modFlujo('<?=$numExpediente?>',<?=$texp?>,<?=$codigoFldExp?>)">
		<?
			}
		?>
  </Td>
--!>
  <td class="tdprincipal"><small><b>
	Nivel de Seguridad</b></small></td><td><small>
	<?
		if($nivelRad==0)
		{	echo "P&uacute;blico";	}
		elseif ($nivelRad == 1)
		{	echo "Privado solo jefe y Ususarios Actuales de Radicados";	}
		elseif ($nivelRad == 2)
		{	echo "Privado dependencia (Solo usuarios Dependencias)";	}
		if(($verradPermisos == "Full" and $coddepe!='999') or $datoVer=="985")
	  	{	$varEnvio = "krd=$krd&numRad=$verrad&nivelRad=$nivelRad";
	?>
		<input type=button name=mostrar_causal value='...' class="btn btn-primary btn-xs btn-rd" onClick="window.open('<?=$ruta_raiz?>/seguridad/radicado.php?<?=$varEnvio?>','Cambio Nivel de Seguridad Radicado', 'height=270, width=600,left=350,top=300')">
	<?
		}
	?>
</small></td><td></td><td></td></tr>
	<tr>
	<td class="tdprincipal"><small><b>Clasificaci&oacute;n Documental</b></small></td><td><div class="centrar">
  <table class="table2">
  <td>
  <small>
	<?
		if(!$codserie) $codserie = "0";
		if(!$tsub) $tsub = "0";
		if(trim($val_tpdoc_grbTRD)=="///") $val_tpdoc_grbTRD = "";
	?>
		<?=$serie_nombre ?><font color=black><br></font><?=$subserie_nombre ?><font color=black><br></font><?=$tpdoc_nombreTRD ?>
	<?
    if(($verradPermisos == "Full" and $coddepe!='999') or $datoVer=="985" or $dependencia == '999' ) {
      ?>
    </td>
    <td>
          <input type=button name=mosrtar_tipo_doc2 title="Asigne una TRD a su documento." value='...' class="btn btn-primary btn-xs btn-rd" onClick="ver_tipodocuTRD(<?=$codserie?>,<?=$tsub?>);">
      </td>


        </small>
      </table>
    </div>
      </td>
      <?$termino=$db->conn->Execute("select SGD_TPR_TERMINO from sgd_tpr_tpdcumento tp, radicado r where tp.SGD_TPR_CODIGO=r.TDOC_CODI and r.radi_nume_radi=$verrad");
      $termino=$termino->fields["SGD_TPR_TERMINO"]?>
        <td class="tdprincipal"><small><b>Término </b></small></td><td><small><?=$termino?></small></td>
        <?  if ($verradPermisos == "Full"  or $datoVer=="985" ) { ?>
              <!--<input type=button name="mostrar_causal" value="..." class="btn btn-primary btn-xs" onClick="window.open(<?=$datosEnviar?>,'Tipificacion_Documento','height=300,width=750,scrollbars=no')">-->
      <?
        }
    }
	  ?>
</small></td><td></td><td></td></tr></table>
</form>
<table width="80%" class="table table-bordered ">
<tr>
 <th  class='alert-info pr2'>Nombre </th>
 <th  class='alert-info pr2'>Persona </th>
 <th  class='alert-info pr2'>Direccion</th>
 <th  class='alert-info pr2'>Ciudad / Departamento</th>
 <th  class='alert-info pr2'>Mail</th>
 <th  class='alert-info pr2'>Telefono</th>
</tr>

<?
 // trae datos.. este array se genera en busca_direcciones.php
    $jkeys = array_keys($dirMuni);
    for ($x = 0; count($jkeys)>$x; $x++){
      if(!empty($dirMuni[$jkeys[$x]])){
        for ($s = 0; count($dirMuni[$jkeys[$x]])>$s; $s++){
          $nomb = $nomRemDes[$jkeys[$x]][$s];
			if (empty($nomb)){
	          $nomb = current($nomRemDes["x"]);
			}
          $nombOtro = $nomOtro[$jkeys[$x]][$s];
			if (empty($nombOtro)){
	          $nombOtro = current($nomOtro["x"]);
			}
          $apellido = $apell[$jkeys[$x]][$s];
          $dirD = $dirDireccion[$jkeys[$x]][$s];
          $dirM = $dirMuni[$jkeys[$x]][$s];
          $dirP = $dirDpto[$jkeys[$x]][$s];
          $dirE = $dirEmail[$jkeys[$x]][$s];
          $dirT = $dirTel[$jkeys[$x]][$s];
          echo "
            <tr>
            <td>$nombOtro $apellido</small></td>
            <td>$nomb </small></td>
            <td>$dirD</small></td>
            <td>$dirM/$dirP</small></td>
            <td>$dirE</small></td>
            <td>$dirT</small></td>
            </tr>";
        }
      }
    }
    ?>
      <tr>
        <td> <?=$nombret_us3 ?> -- <?=$cc_documento_us3?></small></td>
        <td> <?=$direccion_us3 ?></small></td>
        <td> <?=$dpto_nombre_us3."/".$muni_nombre_us3 ?></small></td>
        <td><?=$email["x3"] ?> </small></td>
        <td><?=$telefono["x3"] ?> </small></td>
      </tr>
      </table>

<table width="150" class="table table-bordered ">
<?php
 if(trim($radi_dato_001)){
?>
	<tr>
	 <th  class='alert-info'>Apoderado </th><td><?=$radi_dato_001?></td>
	</tr>
	<?php
	}
  if(trim($radi_dato_002)){
	?>
	<tr>
	 <th  class='alert-info'>Demandante</th><td><?=$radi_dato_002?></td>
	</tr>
	<?php
	}
	?>
</table>
