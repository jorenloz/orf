<!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-12" align=left>
<!--widget content -->
<div class="widget-body" >
<div class="tree smart-form fa-folder-open">
	<ul>
	<li>
	<span class="alert-success"  ><i class="fa fa-folder-open" ></i><?=$numExpediente?></span>
	<ul>
	<?
	
 error_reporting(1);
 //print_r($datosExp); exit;
 
	foreach($datosExp as $key2 => $value){
		
	?>
	<li  >
		<span >
		<?
			//if(isset($value['SEXPEDIENTES'])) {
			//var_dump($value);
			$sExp = "";
			$sExp = $value['SEXPEDIENTES'];
			
			if($sExp!=0){ 
			$SExps = "<table><TR><TD COLSPAN=5></small><b>Expedientes Adicionales :</b></small><TD></TR>";
			foreach($sExp as $valueExpedientes){
				//var_dump($valueExpedientes);
				if($valueExpedientes["NUMERO"]!=$numeroExpediente){
				 $SExps .= "<tr><td><small> ***".$valueExpedientes["NUMERO"]."&nbsp; </small> </td><td> <small>".$valueExpedientes["PARAM1"]."&nbsp; </small></td><td><small>".$valueExpedientes["PARAM2"]."</small></td></tr>";
				}
			}
			$SExps .= "</table>";
			}
			
		$numRadicado = $value["NUM_RADICADO"];
		$pathRadicado = $value["PATH_RADICADO"];
		if (!$pathRadicado) $pathRadicado= "null";
		$fechaRad = "<a href='verradicado.php?verrad=$numRadicado&".session_name()."=".session_id()."&nomcarpeta=".$nomcarpeta."#tabs-a' title='Ver Datos del Radicado $numRadicado'>".$value["FECHA_RADICADO"] . "</a>";
		if(isset($value['SEXPEDIENTES'])){
		echo "<TABLE  WIDTH='1050'><TR><TD WIDTH=30><i class='fa fa-lg fa-minus-circle'></i> </TD>
		<TD width=0 align=left>";
		echo /* $numRadicado .*/ "</td><TD width=160 align=left>";
		$resulVali = $verLinkArchivo->valPermisoRadi($numRadicado);
		$valImg = $resulVali['verImg'];
		$extRad = array_pop(explode(".",$pathRadicado));		
	 if(trim($pathRadicado)){
	 if($valImg == "SI" ){
	    if ($pathRadicado=="null"){
	      echo "<b> $numRadicado </b>";
	    }else{
	      echo "<b><a href='javascript:void(0)' onclick=\"funlinkArchivo('$numRadicado','.');\"><img src='./img/icono_$extRad.jpg' title='$extRad' width='25'> $numRadicado </a>";
	    }  
	 }else{

	  echo "<b><a href='javascript:noPermiso()' ><img src='./img/icono_$extRad.jpg' title='$extRad' width='25'>$numRadicado</a>";
	 }
	 }
	 echo "</TD>";
		echo "<TD width=100>$fechaRad </TD><TD width=120>".$value["TIPO_DRADICADO"]."</TD><TD width=450>".$value["ASUNTO_RADICADO"]."</TD>
		<TD width=350>$SExps</TD></TR>
		</TABLE>";  } ?>
		</span>
	<ul>
	<?
	
	$carpetaDep = intval(substr($value["NUM_RADICADO"],4,$digitosDependencia));
	$rutaAnexos = "".substr($value["NUM_RADICADO"],0,4). "/$carpetaDep/docs/";
	$numeroRadicadoAnexo = $value["NUM_RADICADO"];
	$anexos = $value["ANEXOS"];
	
	if($anexos){
		foreach($anexos as $valueAnexos){
    
		$anexoPath = $valueAnexos["ANEX_PATH"];
		if(strtoupper(trim($valueAnexos["ANEX_BORRADO"]))!="S"){
        
      $resulValiAnex = $verLinkArchivo->valPermisoRadi($numeroRadicadoAnexo);
      $valImg = $resulValiAnex['valImg'];
      $valImg = "SI";
      $extAnexo = array_pop(explode(".",$anexoPath));
      $radiNumeSalida = $valueAnexos["RADI_SALIDA"];
      $pathRadiNumeSalida = $valueAnexos["RADI_PATH_SALIDA"];
      ?>
      <li style="display:none">
      <span>
      <i class="fa fa-clock-o"></i>
        <?=$valueAnexos["ANEX_NUMERO"]?> - <?=$valueAnexos["ANEX_FECH"]?> - <?=$valueAnexos["RADI_SALIDA"]?>
      <?php
          $resulValiRs = $verLinkArchivo->valPermisoRadi($radiNumeSalida);
          $valImgRs = $resulValiRs['verImg'];
          $extRadSalida = strtolower(array_pop(explode(".",$pathRadiNumeSalida)));
        if($pathRadiNumeSalida and $extRadSalida!='docx'  and $extRadSalida!='doc'  and $extRadSalida!='odt'){	
        if(($valImgRs == "SI" or $verradPermisos == "Full")  ){
          echo "<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$radiNumeSalida','.');\"><img src='./img/icono_$extRadSalida.jpg' title='Imagen $extRadSalida' width='25'> </a>";
          }else{
          echo "<a  href='javascript:noPermiso()' class=\"vinculos\" ><img src='./img/icono_$extRadSalida.jpg' title='Imagen $extRadSalida' width='25'> </a>";
        }
        }
        ?>
        <? if($valueAnexos["ANEX_PATH"]) {
        if($valImg == "SI" ){
          echo "<b><a class=\"vinculos\" href=\"#2\" onclick=\"funlinkArchivo('$anexoPath','.');\"><img src='./img/icono_$extAnexo.jpg' title='$extAnexo' width='25'> </a>";
        }else{
          echo "<a class=\"vinculos\" href='javascript:noPermiso()' ><img src='./img/icono_$extAnexo.jpg' title='Sin permiso .. ($extAnexo)' width='25'></a>";
        }
      echo "	- ". $valueAnexos["DESCRIPCION"];
    } ?>
    </span>

  <?
    }
	}
			?>
		</li>	
		<?
		}
		?>
		</ul>
	</li>
	<?
	
	}
	$url = $servidorPyForms."seleccion_predios/get_predios_list?expediente=$numExpediente";
	//$predios2 = file_get_contents($url);
	//$predios = str_replace('"',"'",$predios2);
	//$arrPredios = json_decode($predios2);
	if(is_array($arrPredios)){
		?>
	<li  style="display:none">
		<span  class="alert-success">
		<? echo "Predios"; ?></span>
		<ul>
		<?
		foreach($arrPredios as $key => $valor){
			$isqlPredio = "SELECT *
				FROM LOTE4686
				WHERE chip = '".$valor->chip."' ";
				$rsPredio = $db->conn->Execute($isqlPredio);
				if($rsPredio){
					$propietarios = $rsPredio->fields["PROPIETARIOS_ACTUALES"];
					$areaLeng = $rsPredio->fields["SHAPE_LENG"];
					$area = $rsPredio->fields["SHAPE_AREA"];
					$direccion = $rsPredio->fields["FUENTE_DIRECCION"];
					$matricula = $rsPredio->fields["MATRICULA_INMOBILIARIA"];
					$avaluoCatastral = $rsPredio->fields["AVALUO_CATASTRAL_TERRENO"];
					$avaluoComercial = $rsPredio->fields["AVALUO_COMERCIAL"];
				}
			$linkFichaPredial ="reportePredios('$numeroExpediente','".$valor->chip."','','fichaPrejuridica');";
		?>
	<li style="display:none">
		<a href="verradicado.php?verrad=<?=$verrad?>&<?=session_name()?>=<?=session_id()?>&nomcarpeta=<?=nomcarpeta?>&prediosExp=<?=$predios?>#tabs-gis">Gis</a>
		<a href="#" onClick="<?=$linkFichaPredial?>" ><?="Chip : ".$valor->chip; ?> </a> - <?="Propietarios : ".$propietarios ?> - <?="Direccion : ".$direccion ?>  - <?="Matricula : ".$matricula ?>
		</a>
	</li>	
	<?
	}
	?>
	</ul>
</li>
<? } ?>
		</ul>
	</div>
</div>
		<!-- end widget content -->
</article>
	<!-- WIDGET END -->
	

