<html lang="es">
    <head>
  
        <meta charset="utf-8">
        <title> ..:: INFOMETRIKA ::.</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="INFOMETRIKA">
        <!--Si existe un favicon especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png,
       si no existe se toma el favicon por defecto-->
        <link rel="shortcut icon" href="./img/INFOMETRIKA.favicon.png" onClick="this.reload();">
        <!-- Bootstrap core CSS -->
    <link href="./estilos/correlibre.bootstrap.min.css" rel="stylesheet">
          <!-- font-awesome CSS -->
        <link href="./estilos/font-awesome.css" rel="stylesheet">
        <!-- Bootstrap core CSS -->
        <link href="./estilos/siim_temp.css" rel="stylesheet">

<style>
.navbar-inverse {
  background-color: 8cacc1;
    border-color: 8cacc1;
}

.navbar-inverse .navbar-nav > li > a {
  color: #fff;
}

</style>
        <script type="text/javascript" src="./js/jquery.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap.js"></script>
        <script>
            function recapagi() {
                location.reload();
            }
        </script>
    </head>

    <body>
      <div id="wrapper">
            <div style="position:absolute;background:red;left:38%;" align="center" ><b>..:: Ambiente de : correlibre ::..</b></div>
      
        <!-- Sidebar -->
          <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
  <!--Si existe un logoEntidad especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png, si no existe se toma el favicon por defecto-->
              <!--<a class="navbar-brand"  onclick="recapagi()" href="#" alt="FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE" title="FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE">INFOMETRIKA</a>-->
              <a class="navbar-brand" align="center" onclick="recapagi()" href="#" alt="FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE" title="FUNDACION PARA EL DESARROLLO DEL CONOCIMIENTO LIBRE"><img border=0 src="./img/INFOMETRIKA.favicon.png" width="55" height="28"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse">

                <ul class="nav navbar-nav">

                            
           <li class="dropdown">
           <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Acciones <b class="caret"></b></a>
           
<ul class="dropdown-menu">

             <li>
       <a href="anulacion/cuerpo_anulacion.php?c=20170628050622&&tpAnulacion=1&fechah=20170628050622" target='mainFrame' class="menu_princ">Anulaci&oacute;n</a>
       </li>
                             <li class="dropdown-submenu">
                        <a href="#" onclick="return false;">Editor Flujos</a>
                        <ul class="dropdown-menu">
                          <li><a href='./Administracion/flujos/texto_version2/creaProceso.php?c=20170628050622&&accion=1' class="vinculos" target='mainFrame'>Crear Proceso</a></li>
              <li><a href='./Administracion/flujos/texto_version2/seleccionaProceso.php?c=20170628050622&&accion=2' class="vinculos" target='mainFrame'>Editar Flujo</a></li>
                        </ul>
                      </li>
                                            <li><a href="radicacion/formRadEnvios.php?c=20170628050622&&fechah=20170628050622&usr=d41d8cd98f00b204e9800998ecf8427e&primera=1&ent=1" target='mainFrame' class="menu_princ">Envios</a></li>
                                  <li class="dropdown-submenu">
                <a href="#" onclick="return false;">TRD</a>
                <!--<a href="#" onclick="return false;">Clasificaci&oacute;n Documental</a>-->
                <ul class="dropdown-menu">
                    <li><a href='./trd/admin_series.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Series </a></li>
                    <li><a href='./trd/admin_subseries.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Subseries </a></li>
                    <li><a href='./trd/cuerpoMatriTRD.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Matriz Relaci&oacute;n </a></li>
                    <li><a href='./trd/admin_tipodoc.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Tipos Documentales </a></li>
                    <li><a href='./trd/procModTrdArea.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Modificacion TRD Area </a></li>
                    <li><a href='./trd/informe_trd.php?c=20170628050622&&fechah=20170628050622' class="vinculos" target='mainFrame'>Listado Tablas de Retencion Documental </a></li>
                </ul>
            </li>
                  <li><a href="envios/cuerpoMarcaEnviar.php?porEnviar=1&c=20170628050622&&fechaf=20170628050622&usua_perm_impresion=&carpeta=8&nomcarpeta=Documentos pendientes de Env&iacute;o&orderTipo=desc&orderNo=3" target='mainFrame' class="menu_princ">Por Enviar</a></li>
                    <li><a href="radicacion/edtradicado.php?c=20170628050622&&fechah=20170628050622&primera=1&ent=2" target='mainFrame' class="menu_princ">Modificaci&oacute;n</a></li>

                <li class="dropdown-submenu">
            <a href="#" onclick="return false;">Prestamo</a>
            <ul class="dropdown-menu">
                <li><a href="./prestamo/prestamo.php?opcionMenu=1" target='mainFrame'>Prestamo de documentos</a></li>
                <li><a href="./prestamo/prestamo.php?opcionMenu=2" target='mainFrame'>Devolucion de documentos</a></li>
                <li><a href="./prestamo/prestamo.php?opcionMenu=0" target='mainFrame'>Generacion de reportes</a></li>
                <li><a href="./prestamo/prestamo.php?opcionMenu=3" target='mainFrame'>Cancelar solicitudes</a></li>
            </ul>
        </li>
        
    
    
          
          <li class="dropdown-submenu"><a tabindex="-1" target="mainFrame"> Archivo </a>
            <ul class="dropdown-menu">
              
              <li><a href='./expediente/cuerpo_exp.php?c=20170628050622&&fechaf=20170628050622&carpeta=8&nomcarpeta=Expedientes&orno=1&adodb_next_page=1' target='mainFrame'>1. Archivo (0)</a></li>
                                  <li><a  href='archivo/reporte_archivo.php?c=20170628050622&&adodb_next_page&nomcarpeta&fechah=$fechah&$orno&carpeta&tipo=1' target='mainFrame'>3. Reporte por Radicados Archivados</a></li>
                                    <li><a href='archivo/busqueda_central.php?c=20170628050622&&krd=$krd&fechah=$fechah&$orno&adodb_next_page' target='mainFrame'>9.Busqueda Archivo Central</a>  </li>
                      <li><a href='archivo/busqueda_Fondo_Gestion.php?c=20170628050622&&krd=$krd&fechah=$fechah&$orno&adodb_next_page' target='mainFrame'>10.Busqueda Archivo Fondo Gestion</a>  </li>
                                    <li><a href='archivo/insertar_Fondo_Gestion.php?c=20170628050622&&krd=$krd&fechah=$fechah&$orno&adodb_next_page' target='mainFrame'>12.Insertar Archivo Fondo Gestion</a>  </li>
                                                                  
        </ul>
      </li>
            </ul>
    </li>

    
     <!-- Comienzo de administracion del sistema -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Administraci&oacute;n <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="./Administracion/usuario/index.php?"  class="vinculos"
                        target="mainFrame">Usuarios y Perfiles</a>
                </li> 
                  <li>
      <a href="./Administracion/tbasicas/adm_tarifas.php?" class="vinculos" target='mainFrame'>
          Tarifas
      </a>
  </li>


  <li>
  <a href="./Administracion/tbasicas/adm_dependencias.php?" class="vinculos" target="mainFrame">
    Dependencias
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_nohabiles.php?" class="vinculos" target='mainFrame'>
                        Dias no habiles
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_fenvios.php?" class="vinculos" target='mainFrame'>
                        Env&iacute;o de correspondencia
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_mensajeRapido.php?" class="vinculos" target='mainFrame'>
                        Mensajes Rapidos
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_tsencillas.php?" class="vinculos" target='mainFrame'>
                        Tablas sencillas
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_trad.php?" class="vinculos" target='mainFrame'>
                        Tipos de radicaci&oacute;n
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_paises.php?" class="vinculos" target='mainFrame'>
                        Pa&iacute;ses
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_dptos.php?" class="vinculos" target='mainFrame'>
                        Departamentos
                    </a>
                </li>

                <li>
                    <a href="./Administracion/tbasicas/adm_mcpios.php?" class="vinculos" target='mainFrame'>
                        Municipios
                    </a>
                </li>



                <li>
                    <a href="./Administracion/tbasicas/adm_plantillas.php?" class="vinculos" target='mainFrame'>
                        Plantillas
                    </a>
                </li>

            </ul>
        </li>
     <!-- Fin de administracion del sistema -->


    <li class="dropdown">
    
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" onClick="cargarValoresCarpetas();"> Bandejas <b class="caret"></b></a>
      
      <ul class="dropdown-menu">
        <li class="dropdown-submenu"><a tabindex="-1" target="mainFrame"> Consultas </a>
        <ul class="dropdown-menu">
          <li><a tabindex="-1"href="busqueda/busquedaPiloto.php?c=20170628050622&&&etapa=1&s_Listado=VerListado&fechah=20170628050622"target="mainFrame"> Consulta Clasica</a></li>
          <li><a tabindex="-1"href="./busqueda/busquedaExp.php?c=20170628050622&&&etapa=1&s_Listado=VerListado&fechah=20170628050622"target="mainFrame"> Consulta Expedientes</a></li>
        </ul>
      </li>
      <li><a tabindex="-1" href="./estadisticas/vistaFormConsulta.php?c=20170628050622&&&fechah=20170628050622" target="mainFrame"> Estadisticas </a></li>              
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=General&carpeta=9999&tipo_carpt=0" target="mainFrame" >General (Todos)</a></li>
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Entrada&carpeta=9998&tipo_carpt=0&order=14" target="mainFrame" id='carpetap_9998'>asdfasdfasdfasd</a></li>
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Salida&carpeta=1&tipo_carpt=0&order=14" target="mainFrame" id='carpetap_1'></a></li>
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Devueltos&carpeta=12&tipo_carpt=0&order=14" target="mainFrame" id='carpetap_12'></a></li>
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Radicados Antiguos&carpeta=13&tipo_carpt=0&order=14" target="mainFrame" id='carpetap_13'></a></li>        
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Radicados Antiguos&carpeta=13&tipo_carpt=0&order=14" target="mainFrame" >General (Todos)</a></li>
      <li><a href="cuerpo.php?c=20170628050622&20170628050622&nomcarpeta=Documenos para Visto Bueno&carpeta=11&tipo_carpt=0" target="mainFrame" >Visto Bueno (2330 / 0)</a></li>        
      <li><a href="bandejaInformados.php?c=20170628050622&&<?=mostrar_opc_envio=1&orderNo=2&
                     carpeta=8&nomcarpeta=Informados&orderTipo=desc&adodb_next_page=1" target="mainFrame" >Informados (0)</a></li>                <li><a href="cuerpoTx.php?c=20170628050622&&20170628050622&nomcarpeta=Ultimas Transacciones del Usuario&tipo_carpt=0" target="mainFrame">Transacciones</a></li>                                <li class="divider"></li>
        <li class="dropdown-submenu">
          <a tabindex="-1"  target="mainFrame" onmouseover="cargarValoresCarpetasPersonales();" > Personales </a>          <ul class="dropdown-menu">
            <li><a tabindex="-1" href="crear_carpeta.php?c=20170628050622&&fechah=20170628050622&adodb_next_page=1" target="mainFrame"> Nueva Carpeta <i class=" fa fa-plus-circle"></i></a></li>            <li><a tabindex="-1" href="cuerpo.php?c=20170628050622&&fechah=20170628050622&tipo_carp=1&carpeta=14&nomcarpeta=Ultimas Transacciones del Usuario" target="mainFrame"  id='carpetaPersonal_14'>  </a></li><li><a tabindex="-1" href="cuerpo.php?c=20170628050622&&fechah=20170628050622&tipo_carp=1&carpeta=15&nomcarpeta=Ultimas Transacciones del Usuario" target="mainFrame"  id='carpetaPersonal_15'>  </a></li><li><a tabindex="-1" href="cuerpo.php?c=20170628050622&&fechah=20170628050622&tipo_carp=1&carpeta=16&nomcarpeta=Ultimas Transacciones del Usuario" target="mainFrame"  id='carpetaPersonal_16'>  </a></li>          </ul>
        </li>
      </ul>
          
            
  </li>

      
                <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Radicaci&oacute;n<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="radicacion/chequear.php?c=20170628050622&&primera=1&ent=1&depende=900" target='mainFrame'> Salida </a></li><li><a href="radicacion/chequear.php?c=20170628050622&&primera=1&ent=2&depende=900" target='mainFrame'> Entrada </a></li><li class="dropdown-submenu">
                    <a href="#" onclick="return false;" target="mainFrame">Masiva</a>
                    <ul class="dropdown-menu">
                      <li><a tabindex="-1" href="./radsalida/masiva/upload2PorExcel.php?c=20170628050622&&fechah=20170628050622" target="mainFrame">Masiva externa</a></li><li><a tabindex="-1" href="./radsalida/cuerpo_masiva_recuperar_listado.php?c=20170628050622&&fechah=20170628050622" target="mainFrame">Recuperar Listado</a></li>
                    </ul>
                   </li><li><a href="uploadFiles/orfeocloud/orfeoCloud.php?c=20170628050622&&primera=1&ent=2&depende=900" target="mainFrame">Cargar Archivos Owncloud</a></li><li><a href="uploadFiles/uploadFileRadicado.php?c=20170628050622&&primera=1&ent=2&depende=900" target="mainFrame">Asociar Imagenes</a></li><li><a href="radiMail/index.php" target="mainFrame">e-Mail</a></li>          </ul>
        </li>
        
      </ul>

    <ul class="nav navbar-nav navbar-right navbar-user">

      <li class="dropdown">
        <a href="#" onclick="return false;" data-toggle="dropdown" class="dropdown-toggle"> Opciones <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="plantillas.php?fechah=20170628050622&info=false" target="mainFrame"> Plantillas </a></li>
          <li><a href="http://manual-siim2.infometrika.net" target="mainFrame"> Ayuda </a></li>
          <li><a href="formatos.php?fechah=20170628050622&info=false" target="mainFrame"> Formatos </a></li>
        </ul>
      </li>

      <li class="dropdown user-dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> ADMINISTRADOR <b class="caret"></b></a>
        <ul class="dropdown-menu">
                    <li><a href="mod_datos.php?&fechah=20170628050622&info=false" target="mainFrame"><i class="fa fa-user"></i> Perfil </a></li>

        <li><a href="index_frames.php?&fechah=20170628050622&info=false&cambioKrd=ADMON&cambioUsuaCodi=1&cambioDepeCodi=900" ><i class="fa fa-user"></i> 900 - Adminstracion Sistema (ADMON) - whotezts@outlook.com </a></li>
                  <li> <a href='contraxx.php?&fechah=20170628050622' target=mainFrame> Cambio de clave </a></li>
            <li class="divider"></li>
      <li><a href="cerrar_session.php?"><i class="fa fa-power-off"></i> Salir </a></li>
    </ul>
  </li>

</ul>

  </div>
    <!-- /.navbar-collapse -->
  </nav>

  <iframe name='mainFrame' id='mainFrame' frameBorder="0" width="99%" height="91%" src='cuerpo.php?swLog=&fechah=20170628050622&tipo_alerta=1' scrolling='auto'/></iframe>

</div>
    <script>
    function cargarValoresCarpetas(){
      
      url = "./include/tx/json/getRegistrosCarpetaGen.php?codUsuario=1&depeCodi=900&carpetaPer=0";
      $.get(url, function(data, status){
          var obj = JSON.parse(data);
          if(status="success"){
          $.each( obj, function( key, value ) {
            $('#carpetap_'+key).text(value);
          });
          }
      });
    }
    function cargarValoresCarpetasPersonales(){
      
      url = "./include/tx/json/getRegistrosCarpetaGen.php?codUsuario=1&depeCodi=900&carpetaPer=1";
      $.get(url, function(data, status){
          var obj = JSON.parse(data);
          if(status="success"){
          $.each( obj, function( key, value ) {
            $('#carpetaPersonal_'+key).text(value);
          });
          }
      });
    }
    </script>
  </body>
</html>
