<?php

$ruta_raiz = "..";
 include_once    ("$ruta_raiz/include/db/ConnectionHandler.php");
 if (!$db) $db = new ConnectionHandler($ruta_raiz);
 $db->conn->debug = true;
 
 $dmpOrfeo = file_get_contents ("bd/20170609_baseOrfeo45.sql");
 
 $sqls = explode(";",$dmpOrfeo);
 
 foreach($sqls as $id => $value) {
 
     //  echo "<hr> $id >>". $value . "<hr>";
     //$db->conn->Execute($value);
 }
 
 $db->conn->Execute($dmpOrfeo);
 
 
 




?>