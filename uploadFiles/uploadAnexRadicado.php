<?php
session_start();
/**
  * Se añadio compatibilidad con variables globales en Off
  * @autor Jairo Losada 2009-05
  * @licencia GNU/GPL V 3
  */
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tip3Nombre=$_SESSION["tip3Nombre"];
$tip3desc = $_SESSION["tip3desc"];
$tip3img =$_SESSION["tip3img"];
foreach ($_POST as $key => $valor)   ${$key} = $valor; 
$ruta_raiz = "..";
if(!isset($_SESSION['dependencia']))	include "$ruta_raiz/rec_session.php";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler($ruta_raiz);
error_reporting(7);
$verrad = "";

function return_bytes($val)
{	$val = trim($val);
	$ultimo = strtolower($val{strlen($val)-1});
	switch($ultimo)
	{	// El modificador 'G' se encuentra disponible desde PHP 5.1.0
		case 'g':	$val *= 1024;
		case 'm':	$val *= 1024;
		case 'k':	$val *= 1024;
	}
	return $val;
}
?>
<HTML>
<head>
<link rel="stylesheet" href="<?=$ruta_raiz?>/estilos/cogeinsas.css">
<?php include_once "$ruta_raiz/js/funtionImage.php"; ?>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$ruta_raiz?>/estilos/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$ruta_raiz?>/radiMail/css/smartadmin-production-plugins.min.css">      
<link rel="stylesheet" type="text/css" media="screen" href="<?=$ruta_raiz?>/radiMail/css/smartadmin-skins.min.css">                   
<link rel="stylesheet" type="text/css" media="screen" href="<?=$ruta_raiz?>/radiMail/css/smartadmin-rtl.min.css">                     
<script src="<?=$ruta_raiz?>/js/plugin/dropzone/dropzone.min.js"></script>  
<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>  
</head>
<BODY>
<form action="uploadAnexRadicado.php?krd=<?=$krd?>&<?=session_name()?>=<?=session_id()?>" method="POST" name="formulario">

<table class="table table-bordered smart-form" >                                                                          
    <tr>
    <tr/>
    <tr><td width="100%" >
    <table align="center" cellspacing="0" cellpadding="0" width="100%" class="table">
    <tr class="tablas"><td class="etextomenu" >
    <span class="etextomenu">
	<small>Buscar radicado No.  <small>
    <label class="input">
    <i class="icon-append fa fa-search"></i>
    <input name="valRadio" type="text" size="60" value="<?=$valRadio?>">
	 <input name="asocImgRad" type="text" size="60" value="Subir+Anexo" style="display:none">
    </label>
    <footer><input type="submit" value="Siguiente" name="Buscar" valign="middle" class="btn btn-success" />
      

    </form>
     </span>
    </td></tr>
    </table>
    </td>
  </tr>
</table>           
</FORM>
<? 
	if($valRadio){
	?>	<!-- Widget ID (each widget will need unique ID)-->                                                                           
            <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-0" data-widget-editbutton="false">                     
                <!-- widget options:                                                                                                
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">                                       
                data-widget-colorbutton="false"                                                                                     
                data-widget-editbutton="false"                                                                                      
                data-widget-togglebutton="false"                                                                                     
                data-widget-deletebutton="false"                                                                                     
                data-widget-fullscreenbutton="false"                                                                                 
                data-widget-custombutton="false"                                                                                     
                data-widget-collapsed="true"                                                                                          
                data-widget-sortable="false"                                                                                          
                -->                                                                                                                   
                <header>                                                                                                              
                    <span class="widget-icon"> <i class="fa fa-cloud"></i> </span>                                                    
                    <h2>Dropzone! Radicado No. <?=$valRadio?> </h2>                                                                                            
                </header>                                                                                                             
                <!-- widget div-->                                                                                                    
                <div>                                                                                                                 
                    <!-- widget edit box -->                                                                                         
                    <div class="jarviswidget-editbox">                                                                               
                        <!-- This area used as dropdown edit box -->                                                                  
                    </div>                                                                                                          
                    <!-- end widget edit box -->                                                                                                              <!-- widget content -->                                                                                                                   <div class="widget-body">    
                        <form action="uploadAnex.php?<?=$encabezado?>" class="dropzone" id="mydropzone" enctype="multipart/form-data">
							<textarea name="observa" id="observa" cols=70 rows=3 class="tex_area" maxlength='100' style=" position:absolute; bottom:5px; right:10px;"> Anexo: </textarea> 
                            <input type=checkbox name=chkNivel checked class=ebutton style="display:none">                            
                            <input type='hidden' name=depsel8 value='<?=$depsel8?>'>                                                  
                            <input type='hidden' name=codTx value='<?=$codTx?>'>                                                     
                            <input type='hidden' name=EnviaraV value='<?=$EnviaraV?>'>                                                
                            <input type='hidden' name=fechaAgenda value='<?=$fechaAgenda?>'>                                        
                            <input type=hidden name=enviar value=enviarsi>    
                            <input type=hidden name=enviara value='9'>   
                            <input type=hidden name="Realizar"  value="Realizar">                                                    
                            <input type=hidden name=carpeta value=12>                                                           
                            <input type=hidden name=carpper value=10001>                                   
                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo return_bytes(ini_get('upload_max_filesize')); ?>"><br>                                                                                                  
                            <input type="hidden" name="replace" value="y">                                                          
							<input type="hidden" name="valRadio" value="<?=$valRadio?>">
							<input name="check" type="hidden" value="y" checked>                                                     
                            <input type='hidden' name=depsel value='<?=$depsel?>'> 			
                                </form>                                                                                             
                    </div>                                                                                                          
                    <!-- end widget content -->                                                                                                          </div>                                                                                                
                <!-- end widget div -->
            </div>                                                                                                                    
            <!-- end widget -->            							
<?	}
?>
</BODY>
</HTML>
