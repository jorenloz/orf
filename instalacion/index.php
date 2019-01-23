<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$phpVersion='5.3.0';
// phpLibraries = {"LibName":["description",isOptional],...}
$phpLibraries=array("pgsql"=>array("Se usa para manejar postgres", false),
                    "gd"=>array("------------", false),
                    "curl"=>array("-----------", false),
                    "xsl"=>array(" Permite la descarga en xsl",false),
                    "pdo_pgsql"=>array("-----------", true),
                    "mcrypt"=>array("----",true),
                    "zip"=>array("----",true),
                    "imap"=>array("-----------", true),);
$dbName="orfeo";
$dbServ="127.0.0.1";
$dbServPort="5432";
$dbUser="postgres";
$dbPass="123";
$entidad="CORRELIBRE - ARGO PROJECT --";
$entidadC= 'FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE';
$entidadT = "000000" ;
$entidadD = "Calle ## No. ## - ## CIUDAD";
$PATHO=substr(realpath(dirname(__FILE__)),0,-11);
function checkedLib($name, $libsAdd){
    if (!extension_loaded ( $name )){
	$libsAdd->append($name);
    }
    return $libsAdd;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../estilos/bootstrap.css" rel="stylesheet">
    </head>
    <body>
	<h1><?php //Si esta viendo este mensaje necesita de la libreria libapache2-mod-php7 ?> </h1>
	<style>
	 #colorFont{
	     color: #000;
	 }
	</style>
	<script>
	 function valueOrPH(idTag){
	     $('#'+idTag).prop('disabled', true);
	     var valorTag = $('#'+idTag).val();
	     if (valorTag == ""){
		 return $('#'+idTag).attr('placeholder');
	     }
	     else{
		 return $('#'+idTag).val();
	     }
	 }
	 function cleanNotificaciones(){
	     $('#notificaciones').removeClass('alert-info alert-success alert-danger');
	     $('#notificaciones').html(" ");	     
	     $('#notificaciones').hide();
	 }
	 function procesando(str){
	     cleanNotificaciones();
	     $('#notificaciones').addClass('alert-info');
	     $('#notificaciones').html(str+' ...procesando...');
	     $('#notificaciones').show();
	 }
	 function notificandoError(str){
	     cleanNotificaciones();
	     $('#notificaciones').addClass('alert-danger');
             $('#notificaciones').html("Solucione el "+str+" si desea continuar");
	     $('#notificaciones').show();
	     $('#notificaciones').append(' <button class="btn btn-warning" onclick="window.location.reload();">solucionado</button>');
	 }
	 function notificandoOk(str){
	     //	     console.log("notificando ok: "+str);	     
	     cleanNotificaciones();
	     $('#notificaciones').addClass('alert-success');
             $('#notificaciones').html(str);
	     $('#notificaciones').show();
	 }
	 function instalar(){
	     procesando("Instalando la base de datos");
             $.post("./installp2.php?",
                    {dbNm: valueOrPH('databaseName'),
                     dbSrv: valueOrPH('servDB'),
                     dbSrvP: valueOrPH('servDBPort'),
                     user: valueOrPH('userDB'),
                     pass: valueOrPH('passDB'),
                     type:"i"},
                    function(data){
			if(data[0]==0){
			    notificandoError(data.substring(1));
			}else if(data[0]==1){
                            notificandoOk(data.substring(1));
			    setTimeout(function(){
				configEntidad();
			    }, 2000);
			}else{
                            console.log(data);
			}
                    });
	 }
	 function configurar(){
	     procesando("Configurando la base de datos");
	     $.post("./installp2.php?",
                    {dbNm: valueOrPH('databaseName'),
                     dbSrv: valueOrPH('servDB'),
                     dbSrvP: valueOrPH('servDBPort'),
                     user: valueOrPH('userDB'),
                     pass: valueOrPH('passDB'),
                     type:"c"},
                    function(data){
			if(data[0]==1){
			    notificandoOk(data.substring(1));
			    setTimeout(function(){
				configEntidad();
			    },2000);
			}else if(data[0]==0){
			    notificandoError(data.substring(1));
			}else{

			}
                    });
	 }
 	 function configurarEntidad(){
	     procesando("Configurando la entidad en orfeo");
	     $.post("./installp2.php?",
                    {envOr: valueOrPH('envOr'),
                     theme: valueOrPH('theme'),
                     entidad: valueOrPH('entidad'),
                     entidadC: valueOrPH('entidadC'),
                     entidadT: valueOrPH('entidadT'),
                     entidadD: valueOrPH('entidadD'),
                     type:"o"},
                    function(data){
			if(data[0]==1){
			    notificandoOk(data.substring(1));
			    finalConfig();
			}else if(data[0]==0){
			    notificandoError(data.substring(1));
			}else{

			}
                    });
	 }
	 function urlO(){
	     console.log(document.URL);
	     var i = document.URL.indexOf('orfeo');
	     var i = document.URL.indexOf('/',i);
	     console.log(i);
	     var newUrl = document.URL.substring(0,i);
	     console.log(newUrl);
	     return newUrl;     
	 }
	 function finalConfig(){	     
	     cleanNotificaciones();
	     hiddenDiv('confEntidad');
	     hiddenDiv('confFinal');
	     $('#urldir').html(urlO()+"/instalacion/carpeta_bodega.php");
	 }

     function crearBandeja(){
         var anio = $('#anioEntidad').val();
         window.open(urlO()+"/instalacion/carpeta_bodega.php?anoCrear="+anio, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400");
     }
	 function Finalizar(){
         var urlOrfeo = urlO();
         var salida = urlOrfeo.replace("localhost","127.0.0.1");
	     window.location.href = salida;
	 }
	 function hiddenDiv(idTag){
	     $('#'+idTag).toggle();
	 }
	 function instalarBd(){
	     hiddenDiv('questionDB');
	     hiddenDiv('confdb');
	     hiddenDiv('servDBG');	     
	     hiddenDiv('installdb');
	     hiddenDiv('installdbmsj');
	 }
	 function configurarBd(){
	     hiddenDiv('questionDB');
	     hiddenDiv('confdb');
	     hiddenDiv('configdb');
	     hiddenDiv('configdbmsj');
	 }
	 function configEntidad(){
	     hiddenDiv('confdb');
	     $("#installdb").prop('disabled', true);
	     $('#confEntidad').prop('disabled', true);
	     cleanNotificaciones();
         hiddenDiv('confEntidad');
         $.get("./installp2.php?",{themes:"1"}).done(function( json ) {
             console.log(json);
             $.each(json, function(i, value) {
                 $('#theme').append($('<option>').text(value).attr('value', value));
             });
         });
     }
	</script>
	<?php if (version_compare(PHP_VERSION, $phpVersion) === -1) {
	    echo "<h1>Error </h1><p>Esta  versión de orfeo funciona en php version $phpVersion o superior.<br/>";
	    echo 'Se encuentra la version ' . PHP_VERSION . '. Por favor actualice la version de PHP.</p>';
	    return;
	} 
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	    echo '<h1>Error </h1><p>Orfeo requiere linux como sistema operativo</p> ';
	    return;

	}
	$libsNeed = new ArrayObject(array());
	foreach ($phpLibraries as $library => $descr){
	    $libsNeed=checkedLib($library,$libsNeed);
	}
	//	    var_dump($libsNeed);
	?>
	<div class="container">
	    <div class="row">
		<div class=" col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
		    <h1>Bienvenido al configurador de Orfeogpl</h1>
		    <div class="alert alert-info alert-dismissable" >
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<p>Recuerde que se requiere de postgres como motor de base de datos, por favor instalelo y cree un usuario, pues esto se requiere en pasos posteriores.Si usted ya lo tiene instalado y con el usuario configurado omita este mensaje y continue.<br/>
			    <code id="colorFont">sudo apt-get update </code><br/>
			    <code id="colorFont"> sudo apt-get install postgresql postgresql-contrib </code><br/>
			    <p>Para crear el usuario puede ejecutar las siguientes instrucciones:</p>
			    <code id="colorFont">sudo -i -u postgres</code><br/>
			    <code id="colorFont">createuser --interactive</code><br/>
		    </div>
		    <p>A continuación se listan las librerias necesarias para el total funcionamiento de Orfeo, algunas son opcionales pero no podra usar determinada función.</p>
		    <div id="librerias">
			<?php
			$optionals=true;
			foreach ($libsNeed as $library){
			    $libDescription=$phpLibraries[$library][0];
			    $libNeeded=$phpLibraries[$library][1];
			    $optionals&=$libNeeded;
			    echo '<div  class=" alert';
			    if($libNeeded){
				echo ' alert-warning alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Puede instalar la librreria';
			    }else{
				echo ' alert-danger alert-dismissable\">Por favor instale la libreria ';
			    }
			    echo " <b>$library</b> requerida para  $libDescription  <br/>  <code  id=\"colorFont\">sudo apt-get install php-$library </code> </div>";
			}
			if(count($libsNeed) > 0 ){
			    echo "<p>Recuerde que debe reiniciar el servidor apache para que los cambios surjan efecto<br/> <code id=\"colorFont\">sudo systemctl restart apache2.service</code></p>";
			}
			if(!$optionals){
			    echo ' <button class="btn btn-warning" onclick="window.location.reload();">solucionado</button>';
			    echo '<script src="../js/jquery.min.js"></script>';
			    echo '<script src="../js/bootstrap.js"></script>';
			    exit;
			}
			?>
		    </div>
		</div>
	    </div>
	    <div class="row">
		<div class="col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		    <p><b>Actual path:</b> 
			<?php 
			echo $PATHO;
			$configFile=$PATHO."config.php";
			$existsConfigFile=file_exists($configFile);
			if(!$existsConfigFile){
			    $salidaT=array();
			    $outputShell=exec("cp $configFile.postgres $configFile",$salidaT,$error);
			    if($error == 1){
				echo "<p class=\"alert alert-danger\">Para continuar debe dar permisos de escritura al usuario http o equivalente sobre la carpeta de orfeo <br/> <code id=\"colorFont\" > sudo chown www-data -R $PATHO </code> <br/>O si lo desea realice lo siguiente <br/> <code id=\"colorFont\" > sudo cp $configFile.postgres $configFile  </code> ";
				exit;
			    }
			}
			$isEditable=is_writable($configFile);
			if(!$isEditable){
			    echo "<p class=\"alert alert-danger\">Para continuar debe dar permisos de escritura al usuario http o equivalente sobre el archivo de configuracion de orfeo <br/> <code id=\"colorFont\"> sudo chown www-data $configFile </code>";
			    exit;
			}
			?>
		    </p>
		    <div id="notificaciones" class="alert alert-danger" style="display:none"></div>
		    <div id="questionDB">
			<div class="lead">¿Desea que se cree la base de datos?
			    <div class="btn-group pull-right" >
				<button type="button" class="btn btn-primary" onClick="instalarBd();">Si</button>
				<button type="button" class="btn btn-info" onClick="configurarBd();" autofocus>No</button>
			    </div>
			</div>
                    </div>
		    <div id="confdb" style="display: none;">
			<h3>Configuración base de datos</h3>
			<form   method="post">
			    <div class="form-group form-inline">
				<label for="databaseName">Escriba el nombre de la base de datos. <span id="installdbmsj" style="display: none;">(No es necesario que exista)</span><span id="configdbmsj" style="display: none;">(Es necesario que exista con la estructura de orfeo)</span>:</label> 
				<input type="text" name="databaseName" id="databaseName" placeholder="<?php echo $dbName ?>"><br/>
			    </div>
			    <div id="servDBG" class="form-group form-inline">
				<label for="servDB">Servidor donde se aloja la base de datos :</label> 
				<input type="text" name="servDB" id="servDB" placeholder="<?php echo $dbServ ?>">
				<label for="servDBPort">Puerto:</label> 
				<input type="text" name="servDBPort" id="servDBPort" placeholder="<?php echo $dbServPort ?>"><br/>
			    </div>
			    <div class="form-group form-inline">
				<label for="userDB">Nombre de usuario para acceder a la base de datos. (Ya debe existir) :</label> 
				<input type="text" name="userDB" id="userDB" placeholder="<?php echo $dbUser ?>"><br/>
			    </div>
			    <div class="form-group form-inline">
				<label for="passDB">Contraseña para acceder a la base de datos. (Ya debe existir) :</label> 
				<input type="password" name="passDB" id="passDB" placeholder="<?php echo $dbPass ?>"><br/>
			    </div>
			    <button type="button" id="installdb"  class="btn btn-success pull-right" style="display: none;" onkeypress="instalar();" onClick="instalar();">Instalar</button>		
			    <button type="button" id="configdb" class="btn btn-success pull-right" style="display: none;" onkeypress="configurar();" onClick="configurar();">Configurar</button>
			</form>
		    </div>
                    <div id="confEntidad" style="display:none" >
			<form>
			    <div class="form-group form-inline">
				<label for="envOr">Seleccione el entorno de trabajo:</label> 
				<select type="text" name="envOr" id="envOr" >
				    <option value="desarrollo">Desarrollo</option>
				    <option value="prueba">Prueba</option>
				    <option value="orfeo">Orfeo</option>
				</select>
			    </div>
                <div class="form-group form-inline">
				<label for="theme">Seleccione el tema:(puede adicionar los que desee en la carpeta themes)</label> 
				<select type="text" name="theme" id="theme" >
				</select>
			    </div>
			    <div class="form-group form-inline">
				<label for="entidad">Nombre de la entidad :</label> 
				<input type="text" name="entidad" id="entidad" placeholder="<?php echo $entidad ?>"><br/>
			    </div>
			    <div class="form-group form-inline">
				<label for="entidadC">Nombre completo de la entidad :</label> 
				<input type="text" name="entidadC" id="entidadC" placeholder="<?php echo $entidadC ?>"><br/>
			    </div>
			    <div class="form-group form-inline">                
				<label for="entidadT">Teléfono de la entidad :</label> 
				<input type="tel" name="entidadT" id="entidadT" placeholder="<?php echo $entidadT ?>">
				<label for="entidadD">Direccion de la entidad :</label> 
				<input type="text" name="entidadD" id="entidadD" placeholder="<?php echo $entidadD ?>"><br/>
			    </div>
			    <button type="button" class="btn btn-info pull-right" onClick="configurarEntidad();" onkeypress="configurarEntidad();">Continuar</button>
			</form>
                    </div>
		    <div id="confFinal" style="display:none" >
			<p><?php
			   //
			   $valor = php_ini_loaded_file();
			   $actual = ini_get("short_open_tag");
			   if(!$actual){
			       echo "Por favor cambie el valor de la etiqueta <code>short_open_tag = Off</code> por <code id=\"colorFont\">short_open_tag = On</code>  en el archivo <code id=\"colorFont\">$valor</code> ";
			   }			   
			   ?>
			</p>
			<p>Por ultimo debe crear la carpeta bodega <code id="colorFont" ><?php echo $PATHO."bodega"; ?></code> se recomienda que sea un enlace simbolico, una vez termine de crear sus dependencias ejecute el script <code id="urldir" ></code> </p>
                   <form>
                   <label for="anioEntidadL">Año al que se le creara la bodega :</label> 
                   <input type="text" name="anioEntidad" id="anioEntidad"><button type="button" class="btn btn-info pull-right" onClick="crearBandeja();" onkeypress="crearBandeja();">Continuar</button>
			</form>
			<p>Para ingresar a orfeo se debe hacer con el usuario <code id="colorFont">ADMON</code> Contraseña <code id="colorFont">_pruebas_</code> si esto no funciona asegurese que tiene acceso a la dirección del servidor correctamente, tambien si en la base de datos se encuentran los registros en caso que no en la carpeta instalacion existe un archivo <code id="colorFont">.backup</code> la cual es una imagen de la base de datos que puede restaurar con el comando <code id="colorFont" >pg-restore</code></p>
			<button type="button" class="btn btn-success pull-right" onclick="Finalizar();" onkeypress="Finalizar();">Finalizar</button>
		    </div>
		</div>
	    </div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/bootstrap.js"></script>
    </body>
</html>
