
<?PHP

error_reporting(7);
$ruta_raiz = "..";

require_once($ruta_raiz."/include/PHPMailer_v5.1/class.phpmailer.php");
//require_once($ruta_raiz."/include/PHPMailer/class.phpmailer.php");


if($codTx!=6){
$query = "select u.USUA_EMAIL
                from usuario u
                where u.USUA_CODI ='$usuaCodiMail' and  u.depe_codi='$depeCodiMail'";
//$db->conn->debug = true;

$rs=$db->conn->query($query);
$mailDestino = $rs->fields["USUA_EMAIL"];
//echo "$mailDestino";



}
//$rs=$db->conn->query($queryPath);

$queryPath = "select RADI_NUME_RADI, RADI_PATH
                from RADICADO
                where RADI_NUME_RADI IN($radicadosSelText 0)";

$rsPath = $db->conn->query($queryPath);

$linkImagenesTmp = "";
if($rsPath){
  while(!$rsPath->EOF){
  $radicado = $rsPath->fields["RADI_NUME_RADI"];
  $radicadoPath = $rsPath->fields["RADI_PATH"];
  if(trim($radicadoPath)){
    $linkImagenesTmp .= "<a href='".$servidorOrfeoBodega."$radicadoPath'>Imagen Radicado $radicado </a><br>";
  }else{
    $linkImagenesTmp .= "Radicado $radicado sin documetno Asociado<br>";
  }
  $rsPath->MoveNext();
  }
}
  
$rutaRaiz = $ruta_raiz;
include $rutaRaiz."/conf/configPHPMailer.php";

//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

  if($codTx==6) {
    // echo "<br><br> Envio de Correo por Respuesta Rapida. <br><br>";
    $linkImagenes = $linkImagenesTmp;
    $admPHPMailer    = $correoSalienteRR;
    $userPHPMailer   = $correoSalienteRR;
    $passwdPHPMailer =$passwordCorreoSalienteRR;
    $mensaje = file_get_contents($rutaRaiz."/conf/MailRespuestaRapida.html");
    $asuntoMail =  $asuntoMailRespuestaRapida;
    $mailDestino = trim($mails);
  }
  
  if($codTx==8) {
    $linkImagenes = $linkImagenesTmp;
    $mensaje = file_get_contents($rutaRaiz."/conf/MailInformado.html");
    $asuntoMail =  $asuntoMailInformado;
  }
  if($codTx==9){
    $linkImagenes = $linkImagenesTmp;
    $mensaje = file_get_contents($rutaRaiz."/conf/MailReasignado.html");
    $asuntoMail =  $asuntoMailReasignado;
    
  }
  if($codTx==2){
    $mensaje = file_get_contents($rutaRaiz."/conf/MailRadicado.html");
    $asuntoMail =  $asuntoMailRadicado;
  }

$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

try {
$mail->Host       = $hostPHPMailer; // SMTP server
$mail->Port       = $portPHPMailer; // set the SMTP port for the GMAIL server
$mail->SMTPDebug  = $debugPHPMailer;                     // enables SMTP debug information (for testing) 2 debuger
$mail->SMTPAuth   = true;
//$mail->SMTPSecure = "tls";          // enable SMTP authentication
$mail->SMTPSecure = $SMTPSecure;

$mail->Username   = $userPHPMailer; // SMTP account username
$mail->Password   = $passwdPHPMailer;        // SMTP account password

//  $mail->AddReplyTo('name@yourdomain.com', 'First Last');

//$mail->AddReplyTo('sgdorfeo@correlibre.org', 'sgdorfeo');
  
  
  $emails = explode(";", $mailDestino);
  foreach($emails as $key => $emailDestino) {
    //if($emailDestino) echo "<br>**** $emailDestino ****<br>";
    if($emailDestino) $mail->AddAddress(trim($emailDestino), "$emailDestino");  
    
  }
  //$mail->AddAddress($emails[1], $emails[1]);  
  //if($emailDestino) $mail->AddAddress($emailDestino, "$emailDestino");  
  
  $mail->SetFrom($admPHPMailer, $admPHPMailer);
  $mail->Subject = "$entidad: $asuntoMail";
  $mail->AltBody = 'Para ver correctamente el mensaje, por favor use un visor de mail compatible con HTML!'; // optional - MsgHTML will create an alternate automatically
  $mensaje      = str_replace("*RAD_S*", $radicadosSelText, $mensaje);
  $mensaje      = str_replace("*USUARIO*", $krd, $mensaje);
  $linkImagenes = str_replace("*SERVIDOR_IMAGEN*",$servidorOrfeoBodega,$linkImagenes);
  $mensaje      = str_replace("*IMAGEN*", $linkImagenes, $mensaje);
  $mensaje      = str_replace("*ASUNTO*", $asu, $mensaje);
  $mensaje      = str_replace("*ENTIDAD_LARGO*", $_SESSION["entida_largo"], $mensaje);
  $mensaje      = str_replace("*DEPENDENCIA_NOMBRE*", $_SESSION["depe_nomb"], $mensaje);
  $mensaje      = str_replace("*RADICADO_PADRE*", $radPadre, $mensaje);
  $nom_r        = $nombre_us1 ." ". $prim_apel_us1." ". $seg_apel_us1. " - ". $otro_us1;
  $mensaje = str_replace("*NOM_R*", $nom_r, $mensaje);
  $mensaje = str_replace("*RADICADO_PADRE*", $radicadopadre, $mensaje);
  
  $mensaje = str_replace("*MENSAJE*", $observa, $mensaje);
  $mail->MsgHTML($mensaje);
  if($emailRespaldo) $mail->AddBCC($emailRespaldo, "Respaldo RRTA ($emailRespaldo)");
  
  
  // Envio de adjuntos en respuesta Rapida.
  if($codTx==6 ){
    
   $ext = array_pop(explode(".",$pathRadicado));
    
   $mail->AddAttachment($pathRadicado , 'Respuesta N.'.$nurad.".".$ext);
   
   //var_dump($adjuntosAnex);
   if(!empty($adjuntosAnex) ){ 
     foreach($adjuntosAnex as $key => $anexCodigo){
      if($path_anex[$anexCodigo]){
       $pathAnexo = $ruta_raiz."/bodega/".$path_anex[$anexCodigo];
       $pathAnexo = $ruta_raiz.$path_anex[$anexCodigo];
       $mail->AddAttachment($pathAnexo , "Anexo_".substr($pathAnexo,-9,10));      // attachment
      }
    }
   } 
  }
  
  
  if($mail->Send()){
   echo "<br><b>Enviado correctamente a:</b>  $mailDestino</br>\n";
   $envioOk = "ok";
  }else{
   $envioOk = "Error"; 
   echo "<font color=red><b>No se envio Correo a:</b> $mailDestino</font><br>";
    
  }
} catch (phpmailerException $e) {
  echo $e->errorMessage() . " " .$mailDestino; //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage() . " " .$mailDestino; //Boring error messages from anything else!
}
?>
