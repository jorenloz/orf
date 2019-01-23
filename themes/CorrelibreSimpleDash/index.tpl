<html lang="es">
    <head>
	<meta charset="utf-8">
	<title> ..:: <!--{$entidad}--> ::.</title>
	<meta charset="utf-8">  
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<!--{$entidad}-->">
	<meta name="author" content="Correlibre:Osmar Castillo oacastillol@gmail.com">
	<!--Si existe un favicon especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png,
	     si no existe se toma el favicon por defecto-->
	<!-- Bootstrap core CSS -->
	<!-- font-awesome CSS -->
	<link href="./estilos/font-awesome.css" rel="stylesheet">
	<!-- Bootstrap core CSS -->
	<link href="themes/<!--{$tema}-->/css/estilo.css" rel="stylesheet">
	<link href="./estilos/correlibre.bootstrap.min.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
	<style>
	 .navbar-inverse {
	     background-color: <!--{$colorFondo}-->;
	     border-color: <!--{$colorFondo}-->;
	 }

	 .navbar-inverse .navbar-nav > li > a {
	     color: #fff;
	 }

	</style>
	<script type="text/javascript">
        var map ={17: false, 49: false, 50: false, 51: false};
        document.addEventListener('keydown', function(e){
			if (e.keyCode in map){
				map[e.keyCode] = true;
				if (map[17] && map[50]) {
					$('#bandejas').trigger('click');
				}
				if (map[17] && map[49]) {
					$('#consultas').trigger('click');

				}
				if (map[17] && map[51]) {
					$('#personales').trigger('click');
				}
			}
        });
        document.addEventListener('keyup', function(e){
			if (e.keyCode in map){
				map[e.keyCode] = false;
			}
		});

	</script>
	<script>
	 function recapagi() {
             location.reload();
	 }
	</script>
    </head>

    <body>
	<div id="wrapper">
	    <!--{if $ambiente != "PRODUCCION" }-->
	    <div style="position:absolute;background:red;left:38%;z-index:1;" align="center" ><b>..:: Ambiente de : <!--{$ambiente}--> ::..</b></div>
	    <!--{/if}--> 
	    
            <!-- Sidebar -->
	    <nav class="navbar navbar-inverse navbar-default" role="navigation">
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">              
			<!--Si existe un logoEntidad especifico para la entidad su nombre debe de ser asi <entidad>.favicon.png, si no existe se toma el favicon por defecto-->
			<!--<a class="navbar-brand"  onclick="recapagi()" href="#" alt="<?=$entidad_largo?>" title="<?=$entidad_largo?>"><?=$entidad?></a>-->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#orfeo-navbar-collapse-1" aria-expanded="false">
			    <span class="sr-only">Toggle navigation</span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" align="center" onclick="recapagi()" href="#" alt="<!--{$entidad_largo}-->" title="<!--{$entidad_largo}-->"><img border=0 src="./img/favicon.png" width="55" height="28"><!--{$entidad}--></a>
		    </div>
		    
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <ul class="nav navbar-nav">
			<!--{if $menuAcciones == 1}-->
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><!--{$acciones.nombre}--> <b class="caret"></b></a>
			    <ul class="dropdown-menu">
				<!--{foreach from=$acciones.menu item=menu}-->
				<!--{if $menu.subMenu == 0}-->
				<li>
				    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--></a>
				</li>
				<!--{elseif $menu.subMenu == 1}-->
				<li class="dropdown-submenu">
				    <a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
				    <ul class="dropdown-menu">
					<!--{foreach from=$menu.sub item=item}-->
					<li>
					    <a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--></a>
					</li>
					<!--{/foreach}-->
				    </ul>
				</li>
				<!--{/if}-->
				<!--{/foreach}-->
			    </ul>
			</li>
			<!--{/if}-->
			<!--{if $menuAdministracion == 1}-->
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><!--{$administracion.nombre}--> <b class="caret"></b></a>
			    <ul class="dropdown-menu">
				<!--{foreach from=$administracion.menu item=menu}-->
				<!--{if $menu.subMenu == 0}-->
				<li>
				    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--></a>
				</li>
				<!--{elseif $menu.subMenu == 1}-->
				<li class="dropdown-submenu">
				    <a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
				    <ul class="dropdown-menu">
					<!--{foreach from=$menu.sub item=item}-->
					<li>
					    <a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--></a>
					</li>
					<!--{/foreach}-->
				    </ul>
				</li>
				<!--{/if}-->
				<!--{/foreach}-->
			    </ul>
			</li>
			<!--{/if}-->
			<!--{if $menuRadicacion == 1}-->
			<li class="dropdown">
			    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><!--{$radicacion.nombre}--> <b class="caret"></b></a>
			    <ul class="dropdown-menu">
				<!--{foreach from=$radicacion.menu item=menu}-->
				<!--{if $menu.subMenu == 0}-->
				<li>
				    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--></a>
				</li>
				<!--{elseif $menu.subMenu == 1}-->
				<li class="dropdown-submenu">
				    <a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
				    <ul class="dropdown-menu">
					<!--{foreach from=$menu.sub item=item}-->
					<li>
					    <a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--></a>
					</li>
					<!--{/foreach}-->
				    </ul>
				</li>
				<!--{/if}-->
				<!--{/foreach}-->
			    </ul>
			</li>
			<!--{/if}-->
			
		    </ul>
		    <div class="collapse navbar-collapse" id="orfeo-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right navbar-user">
			    <li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <!--{$opciones.nombre}--><b class="caret"></b></a>
				<ul class="dropdown-menu">
				    <!--{foreach from=$opciones.menu item=menu}-->
				    <!--{if $menu.subMenu == 0}-->
				    <li>
					<a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--></a>
				    </li>
				    <!--{elseif $menu.subMenu == 1}-->
				    <li class="dropdown-submenu">
					<a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
					<ul class="dropdown-menu">
					    <!--{foreach from=$menu.sub item=item}-->
					    <li>
						<a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--></a>
					    </li>
					    <!--{/foreach}-->
					</ul>
				    </li>
				    <!--{/if}-->
				    <!--{/foreach}-->
				</ul>
			    </li>
			    <li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <!--{$usuario.nombre}--><b class="caret"></b></a>
				<ul class="dropdown-menu">
				    <!--{foreach from=$usuario.menu item=menu key=val}-->
				    <!--{if $val == 'salir'}-->
				    <li class="divider"></li>
				    <!--{/if}-->
				    <!--{if $menu.subMenu == 0}-->
				    <li>
					<!--{if 'noframe'|array_key_exists:$menu}-->
					<a href=<!--{$menu.url}--> class="vinculos"><i class="fa fa-user"></i> <!--{$menu.nombre}--></a>
					<!--{else}-->
					<a href=<!--{$menu.url}--> target='mainFrame' class="vinculos"><!--{if $val == 'salir'}--><i class="fa fa-power-off"></i><!--{/if}--><!--{if $val == 'cambioDeClave'}--><i class="fa fa-key"></i><!--{/if}--><!--{if $val == 'perfil'}--><i class="fa fa-user"></i><!--{/if}--> <!--{$menu.nombre}--></a>
					<!--{/if}-->
				</li>
				<!--{elseif $menu.subMenu == 1}-->
				<li class="dropdown-submenu">
				    <a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
				    <ul class="dropdown-menu">
					<!--{foreach from=$menu.sub item=item}-->
					<li>
					    <!--{if 'noframe'|array_key_exists:$item}-->
					    <a href=<!--{$item.url}--> class="vinculos"><!--{$item.nombre}--></a>
					    <!--{else}-->
					    <a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--></a>
					    <!--{/if}-->
					</li>
					<!--{/foreach}-->
				    </ul>
				</li>
				<!--{/if}-->
				<!--{/foreach}-->
			    </ul>
			</li>
		    </ul>
		    </div>
		</div>
	    </nav>
	</div>
	<style>
	 .panel-body { padding:0px; };
	 .panel-body table tr td { padding-left: 15px };
	 .panel-body .table {margin-bottom: 0px; };
	 .container.custom-container {
	     padding: 0 50px;
	 }
	</style>
	<div class="container-fluid">
	    <div class="row">
		<div class="col-md-2">
		    <div class="panel-group" id="accordion">
			<div class="panel panel-default">
			    <div class="panel-heading">   <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" id="consultas"><span class="glyphicon glyphicon-folder-close">
				</span> <!--{$bandejas.menu.consultas.nombre}--></a>
                            </h4>  </div>
			    <div id="collapseThree" class="panel-collapse collapse">
				<div class="panel-body">
				    <ul class="nav nav-pills nav-stacked">
					<!--{foreach from=$bandejas.menu.consultas.sub item=menu key=n}-->
					<!--{if $menu.subMenu == 0}-->
					<li>
					    <!--{if 'id'|array_key_exists:$menu}-->
					    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ" id="<!--{$menu.id}-->"><!--{$menu.nombre}--><!--{if $val == 'nuevaCarpeta'}--><i class=" fa fa-plus-circle"></i><!--{/if}--></a>
					    <!--{else}-->
					    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--><!--{if $val == 'nuevaCarpeta'}--><i class=" fa fa-plus-circle"></i><!--{/if}--></a>
					    <!--{/if}-->
					</li>
					<!--{/if}-->
					<!--{/foreach}-->
				    </ul>
				</div>
			    </div>
			</div>
			<div class="panel panel-default">
			    <div class="panel-heading">  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" id="bandejas"><span class="glyphicon glyphicon-folder-close">                 </span> <!--{$bandejas.nombre}--></a>
                            </h4> </div>
			    <div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
				<ul class="nav nav-pills nav-stacked">
				    <!--{foreach from=$bandejas.menu item=menu key=n}-->
				    <!--{if $n eq 'personales' || $n eq 'consultas'}-->
				    <!--{continue}-->
				    <!--{/if}-->	    
				    <!--{if $menu.subMenu == 0}-->
				    <li>
					<!--{if 'id'|array_key_exists:$menu}-->
					<a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ" id="<!--{$menu.id}-->"><!--{$menu.nombre}--></a>
					<!--{else}-->
					<a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--></a>
					<!--{/if}-->
				    </li>
				    <!--{elseif $menu.subMenu == 1}-->
				    <li class="dropdown-submenu">
					<a href=<!--{$menu.url}--> class="menu_princ"><!--{$menu.nombre}--></a>
					<ul class="dropdown-menu">
					<!--{foreach from=$menu.sub item=item key=val}-->
					    <li>
						<a href=<!--{$item.url}--> target='mainFrame' class="vinculos"><!--{$item.nombre}--> <!--{if $val == 'nuevaCarpeta'}--><i class=" fa fa-plus-circle"></i><!--{/if}--></a>
					    </li>
					    <!--{/foreach}-->
					</ul>
				    </li>
				    <!--{/if}-->
				    <!--{/foreach}-->
				</ul>
				</div>
			    </div>
			</div>
			<div class="panel panel-default">
			    <div class="panel-heading">   <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" id="personales"><span class="glyphicon glyphicon-folder-close">	</span><!--{$bandejas.menu.personales.nombre}--></a>
                            </h4>  </div>
			    <div id="collapseTwo" class="panel-collapse collapse">
				<div class="panel-body">
				    <ul class="nav nav-pills nav-stacked">
					<!--{foreach from=$bandejas.menu.personales.sub item=menu key=n}-->
					<!--{if $menu.subMenu == 0}-->
					<li>
					    <!--{if 'id'|array_key_exists:$menu}-->
					    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ" id="<!--{$menu.id}-->"><!--{$menu.nombre}--><!--{if $val == 'nuevaCarpeta'}--><i class=" fa fa-plus-circle"></i><!--{/if}--></a>
					    <!--{else}-->
					    <a href=<!--{$menu.url}--> target='mainFrame' class="menu_princ"><!--{$menu.nombre}--><!--{if $val == 'nuevaCarpeta'}--><i class=" fa fa-plus-circle"></i><!--{/if}--></a>
					    <!--{/if}-->
					</li>
					<!--{/if}-->
					<!--{/foreach}-->
				    </ul>
				</div>
			    </div>
			</div>
		    </div>
		</div>
		<div class="col-md-10">
		    <iframe name='mainFrame' id='mainFrame' frameBorder="0" width="99%" height="91%" src='cuerpo.php?swLog=<?=$swLog?>&fechah=<?=$fechah?>&tipo_alerta=1' scrolling='auto'/></iframe>
		</div>
	    </div>
	</div>
<!--
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script>
 function cargarValoresCarpetas(){
     
     url = <!--{$urlCargaValores}-->+"0";
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
     
     url = <!--{$urlCargaValores}-->+"1";
     $.get(url, function(data, status){
	 var obj = JSON.parse(data);
	 if(status="success"){
	     $.each( obj, function( key, value ) {
		 $('#carpetaPersonal_'+key).text(value);
	     });
	 }
     });
 }
 $('#bandejas').click(function(){
     cargarValoresCarpetas();
 });
 $('#personales').click(function(){
     cargarValoresCarpetasPersonales();
 });
 
</script>
    </body>
</html>
