<?php

session_start();
define('ADODB_ASSOC_CASE', 2);
ini_set("display_errors",1);


ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);

include_once "../include/db/ConnectionHandler.php";
if (!$db)
 { $db = new ConnectionHandler('..');}

//$db->conn->debug = true;
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
?>

<html>
<head>
</head>

<form action="buildaddaltertable.php" method="post">
   <p><input type="submit" name= "calcular" value ="Calcular Altertables" /></p>
</form>

<body>
<?
if (!isset($_POST['calcular'])) {
	$Mensaje = "";
}else{

		echo "<p>Creacion de Altertables para nivelar la base de datos.</p>";

		$sqli = "
			select 
			t1.table_name as tblnombre, 
			t1.column_name as colnombre,
			t1.data_type as tipodato,
			t1.numeric_precision as presicion, 
			t1.character_maximum_length as largo, 
			t1.is_nullable as isnulo  

			from (select * from information_schema.columns where table_schema = 'original') t1
			left join (select * from information_schema.columns where table_schema = 'public') t2
			on
			t1.table_name = t2.table_name
			and t1.column_name = t2.column_name
			where 
				t1.table_name not in (
				select t1.tablename  from (select * from pg_tables where schemaname = 'original') t1
				left join (select * from pg_tables where schemaname = 'public') t2
				on
				t1.tablename = t2.tablename 
			where 
			t2.tablename is null  
			) and
			t2.column_name is null 
			order by t1.table_name
		";
		$rs = $db->conn->Execute($sqli);

		$sql = "";
		$Tamano = "";
		while(!$rs->EOF){

			$tblnombre = $rs->fields["tblnombre"]; 
			$colnombre = $rs->fields["colnombre"]; 
			$tipodato = $rs->fields["tipodato"]; 
			$presicion = $rs->fields["presicion"]; 
			$largo = $rs->fields["largo"]; 
			$isnulo = $rs->fields["isnulo"]; 
			$Tamano = "";


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
					$Risnulo = "NOT NULL DEFAULT 'foo'";
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

		
			if($tblnombre !='d_ruta_documentos'){
			echo "<br>".$sql = "ALTER TABLE $tblnombre ADD COLUMN $colnombre $tipodato $Tamano $Risnulo ;";
		}
			// ALTER TABLE mytable ADD COLUMN mycolumn character varying(50)


			$tblnombre ='';
			$colnombre ='';
			$tipodato ='';
			$presicion ='';
			$largo ='';
			$isnulo ='';
			$sql='';
			$Tamano = "";

			
			$rs->MoveNext();
		}

}

?>

</body>

</html>


