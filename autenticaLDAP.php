<?php
function checkldapuser($username,$password){
  include "config.php";
  //Cambiamos la versión del LDAP a la version 3 soportada por defecto por Microsoft
  // y soportar UTF8 en usuarios y contraseñas.	
  ldap_set_option($ldapServer, LDAP_OPT_PROTOCOL_VERSION, 3); 
  
  

  if($connect=ldap_connect($ldapServer, $ldapPort)){ 
		
		
	 $username = strtolower("$username@$dominioLdap");
   $ldapbind = ldap_bind($connect, $username, $password);
		
   if ($ldapbind) {
     return '1';
      
      } else {                                 
       $mensajeError = "No se ha podido Conectar al Servidor Ldap con el Usuario $username";
       return $mensajeError;
    }
  }else{
	 $mensajeError = "No se ha podido Conectar al Servidor Ldap ";
   return $mensajeError;
	
	
	}
	
}	

  

?>


