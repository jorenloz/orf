<!DOCTYPE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title> ..:: Respuesta Rapida ::..</title>
        <meta   http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link   href="../estilos/jquery.treeview.css" type="text/css"  rel="stylesheet" />
        <link   href="../estilos/jquery-ui.css"       type="text/css"  rel="stylesheet" />
        <script src="../js/libs/jquery-2.0.2.min.js"  type="text/javascript"></script>
        <script src='../js/jquery.form.js'            type="text/javascript" language="javascript"></script>
        <script src='../js/jquery.MetaData.js'        type="text/javascript" language="javascript"></script>
        <script src='../js/jquery.MultiFile.pack.js'  type="text/javascript" language="javascript"></script>
        <script src='../js/jquery.blockUI.js'         type="text/javascript" language="javascript"></script>
        <script src='../js/jquery.treeview.js'        type="text/javascript" language="javascript"></script>
        <script src='../js/libs/jquery-ui-1.10.4.js'  type="text/javascript" language="javascript"></script>
        <script src="../js/functionImage.js"          type="text/javascript" ></script>
      <link href="../estilos/bootstrap.min.css" rel="stylesheet">
      <!-- font-awesome CSS -->
      <link href="../estilos/font-awesome.css" rel="stylesheet">
      <!-- Bootstrap core CSS -->
      <link href="../estilos/font-awesome.min.css" rel="stylesheet">
      <link href="../estilos/smartadmin-production.css" rel="stylesheet">
      <link href="../estilos/smartadmin-skins.css" rel="stylesheet">
      <link href="../estilos/demo.css" rel="stylesheet">
      <link href="../estilos/siim_temp.css" rel="stylesheet">        

        <script language="javascript">

        $(document).ready(function () {
        });

        function valFo(el){
          var result = true;
          var destin = el.destinatario.value;
          var salida = destin.split(";");

          if (destin == ""){
            alert('El campo destinatario es requerido');
            el.destinatario.focus();
            result = false;
          };

          for(i = 0; i < salida.length; i++){
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(salida[i])){
              result = true;
            }else{
              alert('El destinatario es incorrecto:  ' + salida[i]);
              el.destinatario.focus();
              result = false;
              break;
            }
          }

          return result;
        };
      </script>

      <style type="text/css">

            HTML, BODY{
                font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
                margin: 0px;
                height: 100%;
            }

            #load{
                position:absolute;
                z-index:1;
                border:3px double #999;
                background:#f7f7f7;
                width:300px;
                height:300px;
                margin-top:-150px;
                margin-left:-150px;
                top:50%;
                left:50%;
                text-align:center;
                line-height:300px;
                font-family: verdana, arial,tahoma;
                font-size: 14pt;
            }

            img {
                border: 0 none;
            }

            .MultiFile-label{
                float: left;
                margin: 3px 15px 3px 3px;
            }

            .linkCargar{
                background: url(../estilos/images/flechaAzul.gif) no-repeat;
                cursor: pointer;
                padding-bottom: 17px;
                padding-left: 17px;
            }

        </style>
      <title>..:: Generacion de Documentos en Linea ::..</title>
    </head>
    <body>

        <!--{foreach key=idCarpeta item=carpeta from=$carpetas}-->
          <!--{foreach key=id item=archivo from=$carpeta}-->
              <span id='<!--{$archivo.id}-->' style="display:none;">
                <!--{$archivo.ruta}-->
              </span>
          <!--{/foreach}-->
        <!--{/foreach}-->

        <div id="load" style="display:none;">Enviando.....</div>
        <form id="form1" name="form1" class="smart-form" method="post" enctype="multipart/form-data" action='../respuestaRapida/sendMail.php?<!--{$sid}-->' onsubmit="return valFo(this)">
            <input type="hidden" name="usuanomb"   value='<!--{$usuanomb}-->'>
            <input type="hidden" name="usualog"    value='<!--{$usualog}-->'>
            <input type="hidden" name="editar"     value='<!--{$editar}-->'>
            <input type="hidden" name="radPadre"   value='<!--{$radPadre}-->'>
            <input type="hidden" name="radicado"   value='<!--{$radicado}-->'>
            <input type="hidden" name="nurad"      value='<!--{$nurad}-->'>
            <input type="hidden" name="usuacodi"   value='<!--{$usuacodi}-->'>
            <input type="hidden" name="depecodi"   value='<!--{$depecodi}-->'>
            <input type="hidden" name="codigoCiu"  value='<!--{$codigoCiu}-->'>
            <input type="hidden" name="codigoCiu"  value='<!--{$codigoCiu}-->'>
            <input type="hidden" name="codigoCiu"  value='<!--{$codigoCiu}-->'>
            <input type="hidden" name="rutaPadre"  value='<!--{$rutaPadre}-->'>
            <input type="hidden" name="anexo"  value="<!--{$anexo}-->">
             <input type="hidden" name="codAnexo"  value="<!--{$anexo}-->">
            <input type="hidden" name="path_anex"  value="<!--{$path_anex}-->">

            <table border="0" width="100%" align="center"  class="table table-bordered"  >
	      <tr>
		<td colspan="3"  align="center" width="30">
		  <label class="select">
		    <section class="col col-8">
		    <label class="label">
		      Correos Electronicos a Enviar
		    </label>
		    <label class="input">
		      <textarea type="text" id="email" name="email"  disabled rows="2" cols="50"><!--{$email}--></textarea>
		    </label>
		    <!--{if $MOSTRAR_ERROR}-->
		    <strong>           <BR><BR><BR>
		      DEBE SELECCIONAR UN TIPO DE RADICADO
		    </strong>
		    <!--{/if}-->
		    </td>
		    <td width="29%"  align="center" >
		    <div  style="float: left; width: 40px;" >
		    <p>
		    <!--{if $envioOk=="ok"}-->
		      <label class="label">
			Este radicado ya fue enviado <br>por correo electronico
		      </label>
		      <!--{else}-->
		    <!--{if $email==""}-->
		     <label class="label">
			Este radicado no podra ser enviado <br> hasta que no se verifique el correo <br> de los destinatarios.
		      </label>
		      <!--{else}-->
		    <input type="submit" name="sendMail" value="Enviar Correo" class="btn btn-primary">
		
		    <!--{/if}--> 
		    <!--{/if}--> 
		    
		    <br> 
		    <br> 
		    	    
		    <input type="submit" name="cancelar" value="Cerrar Ventana" class="btn btn-warning" onClick=window.parent.close();>
		    </p><br>
		    <p>
		    </div>
		    </td>
		  </label>
		</p>
		</td>
			      
	      </tr>
	      </tr>
	      <tr>
	      <tr>
		<td colspan="3" >
		  Radicado a enviar:
		  <span ><a href="#" onClick="funlinkArchivo('<!--{$nurad }-->', '..' );"><!--{$nurad }--></a></span>
		</td>
		</tr>
		
	      <td colspan="3" >
	      Adjuntos 
	      </td>

	      <td rowspan="5" valign="middle">
	      <img src="../img/flujo/mailtux.gif" valign="middle">
	      </td>
	      <tr>
	      <td colspan="2">
	      
	  <table border="0" width="100%" align="center"  class="table table-bordered" >
	      <tr>
	      <td>Item
	      </td>
	      <td>Código
	      </td>
	      <td>Descripción
	      </td>
	      </tr>
	      
	      <!--{foreach key=anex_codigo item=anexo from=$anex}-->
	      <!--{if in_array($anex[$anex_codigo],$adjuntosAnex)}-->
	      <tr> 
	      <td>
	      <input type="checkbox" name="anex_codigo[<!--{$anex[$anex_codigo]}-->]" id="anex_codigo" value="anexCodigo" checked disabled /> 
	      </td>
	      <td>
	      <span ><a href="#" onClick="funlinkArchivo('<!--{$anex[$anex_codigo]}-->', '..' );"><!--{$anex[$anex_codigo]}--></a></span>
	      </td>
	      <td>
	      <span ><!--{$desc_anex[$anex_codigo]}--></span>
	      </td>
	      </tr>
	      <!--{/if}-->
	      <!--{/foreach}-->
	      

	      </table>

	      </td>

	      </tr>

	      </form>
	      </tr>
	      <tr>

	      </tr>
    </table>

</body>

<script>
jQuery(window).bind(
    "beforeunload", 
    function() { 
        window.parent.opener.$.fn.cargarPagina('./lista_anexos.php', 'tabs-c');
    }
)
</script>
</html>
