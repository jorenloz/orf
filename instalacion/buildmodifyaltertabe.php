<?php

session_start();
define('ADODB_ASSOC_CASE', 2);
ini_set("display_errors",1);


ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);



include_once "../include/db/ConnectionHandler.php";
if (!$db) $db = new ConnectionHandler("..");
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$db->conn->debug = true;
?>

<html>
<head>
</head>

<form action="buildmodifyaltertabe.php" method="post">
   <p><input type="submit" name= "calcular" value ="Calcular Altertables" /></p>
</form>

<body>
<?
if (!isset($_POST['calcular'])) {
	$Mensaje = "";
}else{

		echo "<p>Modificacion de Altertables para nivelar la base de datos.</p>";

		//Consulta en donde traigo todos los calculars que no aparecen en SGD_DIR_DRECCIONES
		$sqli = "
				select 

					t1.table_name as tblnombre, 
					t1.column_name as colnombre,
					t1.data_type as tipodato,
					t1.numeric_precision as presicion1, 
					t1.character_maximum_length as largo1, 
					t2.numeric_precision as presicion2, 
					t2.character_maximum_length as largo2, 
					t1.is_nullable as isnulo  

				from (select * from information_schema.columns where table_schema = 'original') t1
				join (select * from information_schema.columns where table_schema = 'public') t2
				on
				t1.table_name = t2.table_name and
				t1.column_name = t2.column_name
				where
				t1.data_type != t2.data_type
				or t1.numeric_precision != t2.numeric_precision
				or  t1.character_maximum_length !=t2.character_maximum_length
				order by t1.table_name
		";

		$rs = $db->conn->Execute($sqli);

		$sql = "";
		$Tamano = "";
		while(!$rs->EOF){

			$tblnombre = $rs->fields["tblnombre"]; 
			$colnombre = $rs->fields["colnombre"]; 
			$tipodato = $rs->fields["tipodato"]; 
			$presicion1 = intval($rs->fields["presicion1"]); 
			$largo1 = intval($rs->fields["largo1"]); 
			$presicion2 = intval($rs->fields["presicion2"]); 
			$largo2 = intval($rs->fields["largo2"]); 

			$isnulo = $rs->fields["isnulo"]; 
			$Tamano = "";


			if ($presicion1>=$presicion2){
				$presicion = $presicion1;
			}else{
				$presicion = $presicion2;
			}

			if ($largo1>=$largo2){
				$largo = $largo1;
			}else{
				$largo = $largo2;
			}



			if($tipodato=='numeric' or $tipodato=='smallint' or $tipodato=='bigint' or $tipodato=='integer'){
		
				$Tamano = '';
				if($colnombre=="ID" or $colnombre=="id"){
					$tipodato ='SERIAL';
					$Tamano = '';
				}

			if($tipodato=='numeric'){

					if ($presicion==''){
						
						$Tamano = '';		
					}else{
						$Tamano = '('.$presicion.',0)';		
					}
				}
					if ($isnulo=='NO'){
						$Risnulo = "NOT NULL";
					}else{
						$Risnulo = '';
					}

			}elseif ($tipodato=="character varying" or $tipodato=="character") {
				//echo "<br>"."Soy caracter"; 
				$Tamano = $largo;

				if ($isnulo=='NO'){
					//$Risnulo = "NOT NULL DEFAULT 'foo'";
					$Risnulo = "NOT NULL";
				}else{
					$Risnulo = '';
				}

				if ($Tamano!=""){
					$Tamano = "( ".$Tamano." )";
				}

			}elseif ($tipodato=='text' or $tipodato=='boolean' or $tipodato=='timestamp without time zone' or $tipodato=="date") {
				//echo "<br>"."Soy especial";
				$Tamano = '';
				$Risnulo = '';
			}

			echo "<br>".$sql = "ALTER TABLE $tblnombre ALTER COLUMN $colnombre TYPE $tipodato $Tamano;";

			if($Risnulo!=''){
			echo "<br>".$sql = "ALTER TABLE $tblnombre ALTER COLUMN $colnombre SET NOT NULL;";
			}
			//echo "<br>".$sql = "ALTER TABLE $tblnombre ADD COLUMN $colnombre $tipodato $Tamano $Risnulo ;";

			$tblnombre ='';
			$colnombre ='';
			$tipodato ='';
			$presicion1 ='';
			$presicion2 ='';
			$largo1 ='';
			$isnulo ='';
			$sql='';
			$Tamano = "";

			
			$rs->MoveNext();
		}
echo "</pre>";
}

?>

</body>

</html>
