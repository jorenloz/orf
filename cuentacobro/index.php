<?
session_start();
/**
 * Modulo de Formularios Web para atencion a Ciudadanos.
 * Desarrollado Cesar Buelvas Torres
 * @fecha 2017/01
 *
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
include "../config.php";

$_SESSION["depeRadicaFormularioWeb"]=$depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
$_SESSION["usuaRecibeWeb"]=$usuaRecibeWeb; // Usuario que Recibe los Documentos Web
$_SESSION["secRadicaFormularioWeb"]=$secRadicaFormularioWeb; // Osea que usa la Secuencia sec_tp2_900
$_SESSION["idFormulario"] = sha1(microtime(true).mt_rand(10000,90000));
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include('./funciones.php');
include('./formulario_sql.php');
include('./captcha/simple-php-captcha.php');
$_SESSION['captcha_formulario'] = captcha();


//TamaNo mAximo del todos los archivos en bytes 10MB = 10(MB)*1024(KB)*1024(B) =  10485760 bytes
$max_file_size  = 10485760;

if(!isset($isFacebook)){
	$isFacebook = 0;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>:: Infometrika SAS:: Formulario PQRS</title>
<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


<!--Deshabilitar modo de compatiblidad de Internet Explorer-->
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- CSS -->
<link rel="stylesheet" href="css/structure2.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/fineuploader.css" type="text/css" />
<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="css/screen.css">
<link rel="stylesheet" href="css/style4.css">

<!-- <link rel="stylesheet" href="css/bootstrap.css" type="text/css" /> -->

<!-- <script type="text/javascript"
	src="scripts/wufoo.js"></script> --> 
	<script type="text/javascript" src="prototype.js"></script> <!-- jQuery --> 
	<!-- <script	src="scripts/jquery.js"></script> --> <!-- FineUploader --> 
	<!-- <script	type="text/javascript" src="scripts/jquery.fineuploader-3.0.js"></script>  --> <!--funciones--> 
	<!-- <script	src="scripts/jquery.js"></script>  -->
	<script src="scripts/jquery-3.1.1.min.js"></script> 
	<script src="scripts/jquery.validate.js"></script>
	<script src="scripts/user.controller.js"></script> 
	<script type="text/javascript" src="ajax.js"></script> 

<style type="text/css">
	/* :disabled {background-color:#6FC3C9; } */
	.myreadonly {
		background-color:#6FC3C9;
		border: 2px solid #37858B; 
	}
	.myFile{
		padding: 10px;
		border: none;
	}
	/*fieldset { border:1px solid green }*/
	/*fieldset { border:1px solid green }*/
	fieldset {border-style: dotted dotted none dotted;
		border-color: #1B656B;}

</style>

	
	<!-- Notificaciones -->
<script>


	$( document ).ready(function() {

    	 $("#campo_numid").keypress(function(){
	    	var value = $(this).val();
	    	if ( value.length == 9 ) {
	    		//$("#campo_nombre").focus();
	    		//focusOut();
	    	}
		});

		$("#campo_numid").focusout(function(){
			var value = $(this).val();
	    	if ( value.length >= 6 ) {
	    		$("#campo_nombre").focus();
	    		focusOut();
	    	}
		});

		function focusOut(){
			var value = $("#campo_numid").val();
			if ( value.length >= 6 ) {
	    		loadDataUser(value);
	    	}
			
		}

		$("#campo_numid").on('keyup', function (e) {
		    if (e.keyCode == 13) {
		    	console.log('asdasdasd');
		        $("#campo_nombre").focus();
		    }
		});

	});



	function justNumbers(e)	{
	 var keynum = window.event ? window.event.keyCode : e.which;
	 if ((keynum == 8) || (keynum == 46))
	 //if (keynum == 8)
	 return true;
	 return /\d/.test(String.fromCharCode(keynum));
	}
		//Upload con ajax - subir archivos con ajax 
		//window.onload = createUploader;
</script>
</head>

<body id="public">

<div id="container">
<h1>&nbsp;</h1>
<!-- <form method="post" action="accion.php" enctype="multipart/form-data"> -->
<form id="contactoOrfeo" class="wufoo topLabel" autocomplete="on"
	enctype="multipart/form-data" method="post" action="formulariotx.php" name="quejas"> 
<div class="info">
<?php 
$ent =  $_SESSION['entidad'];
?>
<!-- <center><img src='../img/<?=$ent?>.logoEntidad.png' width=30%></center> -->
<center><img src='../img/IK.logoEntidad.png' width=10%></center>
<p><br> Apreciado ciudadano: </br>
&nbsp <br>Al diligenciar el formulario, tenga en cuenta lo siguiente: </br>
<br>La Ley 1437 de 2011 en su articulo 13  establece que “ Toda actuación que inicie cualquier persona ante las autoridades implica el ejercicio del derecho de petición consagrado en el artículo 23 de la Constitución Política, sin que sea necesario invocarlo. “
<br>
</p>
</div>


<ul>
	<h2>Buzón de cuentas de cobro, equipo Infometrika</h2>
<br> Los campos con (<font color="#FF0000">*</font>
) son obligatorios. </br>
	<table>
		<div class="pub">
		<tr>
			<td style="min-width:200px">
				<li id="li_tipoSolicitud">
					<label class="desc" id="title_tipoSolicitud" for="tipoSolicitud">Tipo de tramite <font color="#FF0000">*</font></label>
					<div>
						<select style="min-width: 100%" id="tipoSolicitud" name="tipoSolicitud" class="field select maximun" tabindex="1">
							<option value="74">CUENTAS DE COBRO</option>
						</select>
					</div>
				</li>
			</td>
	      	<td style="min-width:200px">
				<li id="li_anonimo">
					<label class="desc" id="title_Anonimo" for="anonimo">¿Cuenta anónima?<font color="#FF0000">*</font></label>
					<div>
						<select style="min-width: 100%" id="chkAnonimo" name="anonimo" tabindex="2"
							onChange="if (checkAnonimo()){document.getElementById('campo_asunto').focus(); alert('Si usted opta por presentar su comunicación en forma anónima, no será posible que reciba de manera directa respuesta por parte de la <?=$entidad_largo?>')}else{document.getElementById('tipoDocumento').focus()};">
							<option value=0 selected="selected">No</option>
						</select> 
					</div>
				</li>
			</td>  
			<td style="min-width:200px">
				<li id="li_tipoDocumento">
					<label class="desc" id="title_tipoDocumento" for="tipoDocumento">Tipo de documento <font color="#FF0000">*</font></label>
					<div>
						<select style="min-width: 100%" id="tipoDocumento" name="tipoDocumento"	class="field select maximun" tabindex="3">
							<option value="1">C&eacute;dula de ciudadan&iacute;a</option>
						</select> 
					</div>
				</li>
			</td>
		</tr>
	</table>
	<br>
	<table>		
		<tr>
			<td style="min-width:200px">
				<li id="li_numeroDocumento" class="">
					<label class="desc" id="lbl_numid" for="campo_numid">
						<!-- N&uacute;mero de identificaci&oacute;n (solo n&uacute;meros ) <font color="#FF0000">*</font> -->
						N&uacute;mero de identificaci&oacute;n <font color="#FF0000">*</font>
					</label>
					<div>
						<input required style="border: 2px solid #37858B; min-width: 100%" id="campo_numid" name="numid" type="text" class="field text medium" value="" maxlength="10" tabindex="4"	onkeypress="return justNumbers(event);"  />
					</div> 
				</li>
			</td>  
			<td style="min-width:200px">
				<li id="li_nombre">
					<label class="desc" id="title_Nombre" for="campo_nombre"> Nombres <font color="#FF0000">*</font></label>

					<div>
						<input id="campo_nombre" required style="min-width: 100%" name="nombre_remitente" type="text" class="field text" value="" size="20" tabindex="5" onkeypressS="return alpha(event,letters);" />
					</div>
				</li>
			</td>  
			<td style="min-width:200px">
				<li id="li_apellido">
					<label for="campo_apellido" id="lbl_apellido" class="desc">Apellidos  <font color="#FF0000">*</font></label>
					<div>
						<input required id="campo_apellido" style="min-width: 100%" name="apellidos_remitente" type="text" class="field text" value="" size="20" tabindex="6" onkeypress="return alpha(event,letters);" />
					</div>
				</li>
			</td>
		</tr>
	</table>
	<div id ="mensajeUsuario" align="center" ></div>

<div id = "divInformacionPersonal">
<fieldset>
  <legend> <b> Informacion personal: </b></legend>
  
	
	<table>		
		<tr>
			<td style="min-width:180px">
				<li id="li_pais" class="">
					<label class="desc" id="lbl_pais"	for="label"> País <font color="#FF0000">*</font> </label>
					<div>
						<select required id="slc_pais" style="min-width: 100%" name="pais" class="field select" tabindex="7" onchange="cambia_pais()">
						<?=$pais ?>
						</select>
					<font color="#FF0000"></font>
					</div>
				</li>
			</td>  
			<td style="min-width:180px">
				<li id="li_departamento" class="">
					<label class="desc" id="lbl_deptop" for="label"> Departamento <font color="#FF0000">*</font> </label>
					<div>
						<select required id="slc_depto" style="min-width: 100%" name="depto" class="field select" tabindex="8" onchange="trae_municipio()">
							<option value="" selected="selected">Seleccione</option>
							<?=$depto ?>
						</select> 
						<font color="#FF0000"></font>
					</div>
				</li>
			</td>  
			<td style="min-width:180px">
				<li id="li_municipio" class="">
					<label class="desc" id="lbl_municipio" for="label2"> Municipio <font color="#FF0000">*</font> </label>
					<div id="div-contenidos">
						<select required id="slc_municipio" style="min-width: 100%"  name="muni" class="field select" tabindex="9">
							<option value="" selected="selected">Seleccione..</option>
						</select> 
						<font color="#FF0000"></font>
					</div>
				</li>
			</td>
		</tr>
	</table>


	<li id="li_direccion"> <label class="desc" id="lbl_direccion" for="campo_direccion">Direcci&oacute;n
	</label>
	<div><input required id="campo_direccion" name="direccion" type="text"
		class="field text medium" value="" maxlength="150" tabindex="10"
		onkeypress="return alpha(event,numbers+letters+signs+custom)" />
	&nbsp;</div>
	</li>

	<li id="li_telefono"><label class="desc" id="lbl_telefono" for="campo_telefono">Tel&eacute;fono
	<font color="#FF0000">*</font></label>
		<div>
			<input required id="campo_telefono" name="telefono" type="text" class="field text medium" value="" maxlength="50" tabindex="11" onkeypress="return alpha(event,numbers+alpha)" /> 
		</div>
	</li>

	<li id="li_email"><label class="desc" id="lbl_email" for="campo_email"> E-mail <font
		color="#FF0000">*</font></label>
	<div><input required id="campo_email" name="email" type="text"
		class="field text medium" value="" maxlength="50" tabindex="12"></div>
	</li>
	<br>
</fieldset>
</div>

	<input type="hidden" name="tipoPoblacion">
	<?php /*
	<li id="li_tipoPoblacion"> <label class="desc" id="title_tipoPoblacion" for="tipoPoblacion">Tipo
	de poblaci&oacute;n </label>
	<div><select id="tipoPoblacion" name="tipoPoblacion"
		class="field select maximun" tabindex="14">
		<?=$temas;?>
	</select> &nbsp;<font color="#FF0000"></font></div>
	</li>
	<?if($_GET['form']=='entidades'){
	$isql="select nombre_de_la_empresa, identificador_empresa from bodega_empresas where identificador_empresa!=1 order  BY  nombre_de_la_empresa Asc";
	$isql=$db->conn->Execute($isql);
	$i=0;
	while (!$isql->EOF){
		$entidades.='<option value="'.$isql->fields["IDENTIFICADOR_EMPRESA"].'">'.$isql->fields["NOMBRE_DE_LA_EMPRESA"].'</option>';
		$isql->MoveNext();	
	}
	?>
	<li id="li_entidad" class="   "><label class="desc" id="lbl_entidad"
		for="campo_entidad">Entidad contra la cual se presenta la queja<font color="#FF0000">*</font></label>
	<div><select id="entidad" style="width:300px;" class="field select maximun" name="entidad"
		tabindex="14">
		<?=$entidades;?>
	</select> &nbsp;<font color="#FF0000"></font></div>
	</li>
	<?}?>
	*/ ?>

	<table>		
		<tr>
			<td style="min-width:700px">
				<li id="li_asunto" class="">
					<label class="desc" id="lbl_asunto"	for="campo_asunto">Asunto de la Cuenta de Cobro<font color="#FF0000">*</font></label>
					<div>
						<input style="min-width: 100%"  required id="campo_asunto" name="asunto" type="text"	class="field text large" value="Cuenta de Cobro " maxlength="80" tabindex="15" />
					</div>
				</li>
			</td>  
		</tr>
		<tr>
			<td style="min-width:700px">
				<li id="li_comentario" class="">
					<label class="desc" id="lbl_comentario"	for="campo_comentario">Descripción de la Cuenta de Cobro <font color="#FF0000">*</font></label>
						<textarea style="min-width: 100%"  required id="campo_comentario" name="comentario" class="field textarea small" rows="10" cols="50" tabindex="16" onkeyup="countChar(this)" defaultValue="Escriba ac&aacute; ...">Remito cuenta de cobro del proyecto ....  </textarea>
						<!--<input type="hidden" id="adjuntosSubidos" name="adjuntosSubidos" value="" />-->
				</li>
			</td>
		</tr>
	</table>

	<?php /* 
	<div align="right" id="charNum"></div>
	</li>
	<li id="li_upload">
		<div id="filelimit-fine-uploader"></div>
		<div id="availabeForUpload"></div>
		&nbsp;
	</li> */ ?>
	<br>
		<div align="center" style=" background-color: lightblue; padding-top: 10px; padding-bottom: 10px;  border-radius: 15px;">
			<font color="#37858C"> <b>  Favor anexar los siguientes archivos en formato <i> <font color="#1B656B">PDF </font>
		</div>
	<br>

	<fieldset>
	<legend> <b> <font color="#FF0000"> Formato Cuenta de Cobro *</font>: </b></legend>
		<input name="fileCuentaCobro" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" required class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
	</fieldset>
	<br>
		<div align="center" style=" background-color: lightblue; padding-top: 10px; padding-bottom: 10px;  border-radius: 15px;">
			<font color="#37858C"> <b>  Si usted <font color="1B656B">NO </font> ha enviado con anterioridad el  <i> <font color="1B656B">RUT </font>, <font color="#1B656B"> Certificado de Origen de ingreso </font>	 ó <font color="#1B656B"> Cedula de ciudadania</font> </i>, a Infometrika SAS favor adjuntarlas, de lo contrario omitir este aviso </b>  </font><br>
		</div>

	<div id = "divArchivosPersonales">
		<br>
		<fieldset>
		<legend> <b>  <font > Cedula de ciudadania</font>  </b></legend>
			<input name="fileCedula" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
		</fieldset>
		<br>
		<fieldset>
		<legend> <b> <font >RUT : </font> </b></legend>
			<input name="fileRUT" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
		</fieldset>
	</div>

	<br>
	<fieldset>
	<legend> <b> <font >Pago de Aportes Parafiscales (Salud, ARL Y Pensión) : </font> </b></legend>
		<input name="fileAportes" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
	</fieldset>
	
	<br>
	<fieldset>
	<legend> <b> <font > Certificado de Origen de ingreso : </font> </b></legend>
		<table>		
			<tr>
				<td style="min-width:160px">
					<input name="fileCertOrigen" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
				</td>
				<td style="min-width:160px">
					<div align="right" > <a href="formatos/FORMATO ORIGEN_DE_INGRESOS_2016.pdf" target="_blank">
						Descargar Formato 2016 <i class="fa fa-download" aria-hidden="true"></i></a>
					</div>
					<div align="right" > <a href="formatos/FORMATO_ORIGEN_DE_INGRESOS_2017.pdf" target="_blank">
						Descargar Formato 2017 <i class="fa fa-download" aria-hidden="true"></i></a>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>


	
	<br>
	<fieldset>
	<legend> <b> <font >Formato Legalización de Viaticos : </font> </b></legend>
		<input name="fileLegalizar" accept="application/pdf,image/*,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" class = "myFile" type="file" class="filestyle" data-buttonText="Find file"> 
	</fieldset>
	<br>

	<fieldset>
		<li id="li_imagenVerificacion">
			<legend><label class="desc" id="lbl_captcha" for="campo_captcha">Imagen de	verificaci&oacute;n (Digite en el recuadro las letras o número de la imagen). <font color="#FF0000">*</font></label></legend>

			<div>
				<input required id="campo_captcha" name="captcha" type="text" class="field text small" value="" maxlength="5" tabindex="20" onkeypress="return alpha(event,numbers+letters)" alt="Digite las letras y n&uacute;meros de la im&aacute;gen" />
			<p>
			<?php
		echo '<img id="imgcaptcha" src="' . $_SESSION['captcha_formulario']['image_src'] . '" alt="CAPTCHA" /><br>';
		echo '<a href="#" onClick="return reloadImg(\'imgcaptcha\');">Cambiar imagen<a>'?></p>
	    <input type="hidden" name="pqrsFacebook" value="<?=$isFacebook?>" />
	    <input type="hidden" name="idFormulario" value="<?=$_SESSION["idFormulario"]?>" />
		</div>
		</li>
	</fieldset>

	<li id="li_botones" class="buttons">
		<input style="cursor: pointer" class="a_demo_four" id="saveForm" type="submit" value="Enviar" onclick="return valida_form();" />
		<!-- <input id="saveForm" type="submit" value="Enviar" /> -->
		<!--<input name="button" type="button" id="button" onclick="window.close();" value="Cancelar" />-->
		<input  style="cursor: pointer" class="a_demo_four" type="button" value="Cancelar y Cerrar" onClick="closeWindowns();" />

	</li>
</ul>

	<!--<input type="text" id="slc_pais_2" name="pais_2">-->
	<input type="hidden" id="slc_depto_2" name="depto_2">
	<input type="hidden" id="slc_municipio_2" name="muni_2">
	<input type="hidden" id="campo_direccion_2" name="direccion_2">
	<input type="hidden" id="campo_telefono_2" name="telefono_2">
	<input type="hidden" id="campo_email_2" name="email_2">

</form>

</div>
<!--container-->
</body>
</html>
