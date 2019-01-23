<?	
	switch($db->driver)
	{
	case 'mssql':
		$qeryObtenerGrupo = "select  convert(char(14), RADI_NUME_SAL) as RADI_NUME_SAL ,SGD_RENV_CODIGO    from sgd_renv_regenvio 
			   WHERE radi_nume_grupo=$grupo
			   and sgd_depe_genera  = '$dependencia'
				 $qFiltro 
				 order by radi_nume_sal asc ";
	break;
	case 'oracle':
	case 'oci8':
	case 'oci805':
	case 'ocipo':
		$qeryObtenerGrupo = "select  RADI_NUME_SAL,SGD_RENV_CODIGO    from sgd_renv_regenvio 
			   WHERE radi_nume_grupo=$grupo
			   and sgd_depe_genera  = '$dependencia'
				 $qFiltro 
				 order by radi_nume_sal asc ";
	break;
default:
	$qeryObtenerGrupo = 
		"select  renv.RADI_NUME_SAL,renv.SGD_RENV_CODIGO    from sgd_renv_regenvio renv, radicado r
		   WHERE
		    renv.radi_nume_sal=r.radi_nume_radi
		    and r.sgd_eanu_codigo is null
		    and renv.radi_nume_grupo=$grupo
				and renv.sgd_depe_genera  = $dependencia
        and renv.sgd_renv_planilla = '00'
				$qFiltro order by sgd_renv_depto,sgd_renv_mpio asc ";
	break;
	}
?>
