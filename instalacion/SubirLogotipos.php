<?php
session_start();
define('ADODB_ASSOC_CASE', 2);
ini_set("display_errors",1);
include_once "../config.php";

#por Cesar Buelvas (cejebuto@gmail.com)

#Antes  ejecutar 
#sudo chown -R www-data img/


#IMAGEN DE FONDO DEL APLICATIVO
#> img/login_background.jpeg

#LOGOTIPO PARTE SUPERIOR IZQUIERDA
#> img/$entidad.logoEntidad.png

#BANNER PARA ANULADOS
#> img/$entidad.banerPDF.jpg

#FAVICON PARA EL APLICATIVO
#> img/$entidad.favicon.png

?>

SUBIR IMAGEN DE FONDO DEL APLICATIVO (JPGE)
<form enctype="multipart/form-data" action="SubirLogotipos.php" method="POST">
<input name="uploadbackgroudn" type="file" />
<input type="hidden" name = 'action' value="1" />
<input type="submit" value="Subir archivo" />
</form>

<br><br>


SUBIR LOGOTIPO DE LA PARTE SUPERIOR IZQUIERDA (PNG)
<form enctype="multipart/form-data" action="SubirLogotipos.php" method="POST">
<input name="uploadlogo" type="file" />
<input type="hidden" name = 'action' value="2" />
<input type="submit" value="Subir archivo" />
</form>

<br><br>



SUBIR SUBIR EL BANNER PARA ANULADOS banerPDF (JPG)
<form enctype="multipart/form-data" action="SubirLogotipos.php" method="POST">
<input name="uploadbannerpdf" type="file" />
<input type="hidden" name = 'action' value="3" />
<input type="submit" value="Subir archivo" />
</form>

<br><br>


SUBIR SUBIR EL FAVICON (PNG)
<form enctype="multipart/form-data" action="SubirLogotipos.php" method="POST">
<input name="uploadfavicon" type="file" />
<input type="hidden" name = 'action' value="4" />
<input type="submit" value="Subir archivo" />
</form>

<br><br>




<?php

if (isset($_POST['action'])){


$action = $_POST['action'];


switch ($action) {
	case '1':
		echo "Background <br><br>";


			$uploadbackgroudnload="true";
			$uploadbackgroudn_size=$_FILES['uploadbackgroudn'][size];
			//echo $_FILES[uploadbackgroudn][name];
			echo "<br>";


			if ($_FILES[uploadbackgroudn][size]>3000000)
			{$msg=$msg."El archivo es mayor que 3 Megas, debes reduzcirlo antes de subirlo<BR>";
			$uploadbackgroudnload="false";}


			if (!($_FILES[uploadbackgroudn][type] =="image/jpeg"))
			{$msg=$msg." Tu archivo tiene que ser JPEG. Otros archivos no son permitidos<BR>";
			$uploadbackgroudnload="false";}


			$file_name=$_FILES[uploadbackgroudn][name]; 
			$file_name = explode('.', $file_name);

			$file_extencion = $file_name[1]; 


			$file_name='login_background.'.$file_extencion ;
			$add="/img/$file_name";

			if($uploadbackgroudnload=="true"){


			//$destFile = $_SERVER['DOCUMENT_ROOT'].$add;
			$destFile = $ABSOL_PATH.$add;

			chmod($ABSOL_PATH.'/img', 0777);

			try {
			    //throw exception if can't move the file
			    if (!move_uploaded_file ($_FILES[uploadbackgroudn][tmp_name],$destFile)) {
			        throw new Exception('Could not move file');
			    }
			    chmod($destFile, 0775);
			    echo "Upload Complete!";
			} catch (Exception $e) {
			    die ('File did not upload: ' . $e->getMessage());
			}


			}else
			{
				echo $msg;
			}

		break;


	case '2':
		echo "Logo  <br><br> ";


			$uploadlogoload="true";
			$uploadlogo_size=$_FILES['uploadlogo'][size];
			//echo $_FILES[uploadlogo][name];
			echo "<br>";


			if ($_FILES[uploadlogo][size]>3000000)
			{$msg=$msg."El archivo es mayor que 3 Megas, debes reduzcirlo antes de subirlo<BR>";
			$uploadlogoload="false";}


			if (!($_FILES[uploadlogo][type] =="image/png"))
			{$msg=$msg." Tu archivo tiene que ser PNG Otros archivos no son permitidos<BR>";
			$uploadlogoload="false";}


			$file_name=$_FILES[uploadlogo][name]; 
			$file_name = explode('.', $file_name);

			$file_extencion = $file_name[1]; 


			$file_name=$entidad.'.logoEntidad.'.$file_extencion ;
			$add="/img/$file_name";

			if($uploadlogoload=="true"){


			$destFile = $ABSOL_PATH.$add;

			chmod($ABSOL_PATH.'/img', 0777);

			try {
			    //throw exception if can't move the file
			    if (!move_uploaded_file ($_FILES[uploadlogo][tmp_name],$destFile)) {
			        throw new Exception('Could not move file');
			    }
			    chmod($destFile, 0775);
			    echo "Upload Complete!";
			} catch (Exception $e) {
			    die ('File did not upload: ' . $e->getMessage());
			}


			}else
			{
				echo $msg;
			}

		break;
	case '3':
		echo "Anulados <br><br> ";

			
			$uploadbannerpdfload="true";
			$uploadbannerpdf_size=$_FILES['uploadbannerpdf'][size];
			//echo $_FILES[uploadbannerpdf][name];
			echo "<br>";


			if ($_FILES[uploadbannerpdf][size]>3000000)
			{$msg=$msg."El archivo es mayor que 3 Megas, debes reduzcirlo antes de subirlo<BR>";
			$uploadbannerpdfload="false";}


			if (!($_FILES[uploadbannerpdf][type] =="image/jpeg"))
			{$msg=$msg." Tu archivo tiene que ser JPG Otros archivos no son permitidos<BR>";
			$uploadbannerpdfload="false";}


			$file_name=$_FILES[uploadbannerpdf][name]; 
			$file_name = explode('.', $file_name);

			$file_extencion = $file_name[1]; 


			$file_name=$entidad.'.banerPDF.'.$file_extencion ;
			$add="/img/$file_name";

			if($uploadbannerpdfload=="true"){


			$destFile = $ABSOL_PATH.$add;

			chmod($ABSOL_PATH.'/img', 0777);

			try {
			    //throw exception if can't move the file
			    if (!move_uploaded_file ($_FILES[uploadbannerpdf][tmp_name],$destFile)) {
			        throw new Exception('Could not move file');
			    }
			    chmod($destFile, 0775);
			    echo "Upload Complete!";
			} catch (Exception $e) {
			    die ('File did not upload: ' . $e->getMessage());
			}


			}else
			{
				echo $msg;
			}

		break;

		case '4':
		echo "<h3> Favicon </h3>  <br><br> ";


			$uploadfaviconload="true";
			$uploadfavicon_size=$_FILES['uploadfavicon'][size];
			//echo $_FILES[uploadfavicon][name];
			echo "<br>";


			if ($_FILES[uploadfavicon][size]>3000000)
			{$msg=$msg."El archivo es mayor que 3 Megas, debes reduzcirlo antes de subirlo<BR>";
			$uploadfaviconload="false";}


			if (!($_FILES[uploadfavicon][type] =="image/png"))
			{$msg=$msg." Tu archivo tiene que ser PNG Otros archivos no son permitidos<BR>";
			$uploadfaviconload="false";}


			$file_name=$_FILES[uploadfavicon][name]; 
			$file_name = explode('.', $file_name);

			$file_extencion = $file_name[1]; 


			$file_name=$entidad.'.favicon.'.$file_extencion ;
			$add="/img/$file_name";

			if($uploadfaviconload=="true"){


			$destFile = $ABSOL_PATH.$add;

			chmod($ABSOL_PATH.'/img', 0777);

			try {
			    //throw exception if can't move the file
			    if (!move_uploaded_file ($_FILES[uploadfavicon][tmp_name],$destFile)) {
			        throw new Exception('Could not move file');
			    }
			    chmod($destFile, 0775);
			    echo "Upload Complete!";
			} catch (Exception $e) {
			    die ('File did not upload: ' . $e->getMessage());
			}


			}else
			{
				echo $msg;
			}

		break;




	default:
		echo "ninguno <br><br> ";
		break;
}



} //fin del if set.


?>
