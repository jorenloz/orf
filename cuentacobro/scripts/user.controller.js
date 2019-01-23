jQuery.extend(jQuery.validator.messages, {
  required: "Este campo es obligatorio.",
  remote: "Por favor, rellena este campo.",
  email: "Por favor, escribe una dirección de correo válida",
  url: "Por favor, escribe una URL válida.",
  date: "Por favor, escribe una fecha válida.",
  dateISO: "Por favor, escribe una fecha (ISO) válida.",
  number: "Por favor, escribe un número entero válido.",
  digits: "Por favor, escribe sólo dígitos.",
  creditcard: "Por favor, escribe un número de tarjeta válido.",
  equalTo: "Por favor, escribe el mismo valor de nuevo.",
  accept: "Por favor, escribe un valor con una extensión aceptada.",
  maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
  minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
  rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
  range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
  max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
  min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
});


	/*Funciones pertenecientes a cargar los datos del usuario si existe */
	function loadDataUser (value) {
		//console.log(value);
		$.ajax({
		  	beforeSend:function()
		  	 {
					$("#campo_direccion").val("");
					$("#campo_email").val("");
					$("#campo_telefono").val("");
					$("#slc_depto").val("");
					$("#slc_municipio").val("");
		  	 },
		   type: "POST",
		   url: "controler.user.ajax.php",
		   data:({ value:value }),
	   	   dataType: 'json',

		   success: function(resp,resp2){   

				if (resp['STATUS']==true){
					//console.log('Guardado con exito','success','bottomRight','location.reload()');	
					console.log('Cargar informacion');	
					//$("#campo_numid").attr('disabled','disabled');

						$("#campo_numid").attr('readonly','readonly');
						$("#campo_numid").addClass('myreadonly');
						$("#campo_nombre").attr('readonly','readonly');
						$("#campo_nombre").addClass('myreadonly');
						$("#campo_apellido").attr('readonly','readonly');
						$("#campo_apellido").addClass('myreadonly');
					
					
					//$("#mensajeCedula").html('<font color="green">Usuario encontrado en la base de datos de infometrika! <i class="fa fa-check-circle-o" aria-hidden="true"></i> </font>');
					
					$("#mensajeUsuario").css({"background-color":"#A8F8B9", "padding-top":"10px", "padding-bottom":"10px", "border-radius":"15px", "margin-top":"10px", "margin-bottom":"10px"});
					//$("#mensajeUsuario").html('<font color="green">Usuario en ORFEO <i class="fa fa-check-circle-o" aria-hidden="true"></i> </font>');
					$("#mensajeUsuario").html('<font color="#1B656B"> Este usuario ya se encuentra registrado en la base de datos de <b> ORFEO </b> <i class="fa fa-check-circle-o" aria-hidden="true"></i> </font><br>');
					//$("#mensajeUsuario").html('<font color="#1B656B"> Debido a que el usuario existe en ORFEO , esta parte del formulario (<b>Informacion personal</b>) puede omitirla, pero se recomienda <b> Actualizar Los Datos </b></font><br><br>');


					$("#campo_nombre").val(resp['NOMBRES']);
					$("#campo_apellido").val(resp['APELLIDOS']);
					$("#campo_direccion_2").val(resp['DIRECCION']);
					$("#campo_email_2").val(resp['MAIL']);
					$("#campo_telefono_2").val(resp['TELEFONO']);
					$("#slc_depto_2").val(resp['DEPARTAMENTO']);
					$("#slc_municipio_2").val(resp['MUNICIPIO']);


					$("#campo_asunto").val("Cuenta de Cobro "+myTrim(resp['APELLIDOS'])+" "+myTrim(resp['NOMBRES']));

					/* Focus */
					$("#slc_pais").focus();

					/**/
					$('#divInformacionPersonal').hide("linear");

						if (resp['EXPEDIENTE']!="") {
							$('#divArchivosPersonales').hide("linear");	
						}

				}else{
					console.log('No existen datos disponibles para esta cedula en ORFEO');	
				}
		   },

		   error: function(jqXHR,estado,error){ //colocar un case
		   		//alert("NO se pudo Guardar");
		   		console.log('OCURRIO UN ERROR FATAL : ','error','bottomRight');
		   		console.log(jqXHR);
		   },

		    timeout: 10000

		  });
	}

	//Validamos el Formulario 
	function validarFormulario (){
		var mensaje = "";
		var error = 0;
		var formulario = document.getElementById('contactoOrfeo');


		$("#contactoOrfeo").validate({
		  rules: {
		    email: {
		      email: true
		    }
		  }
		});

		var valdForm =  $("#contactoOrfeo").valid();

		if (formulario.captcha.value.length != 5 || !validar_captcha()) {
			mensaje += '\n-Código de verificación inválido.';
			error = 1;
		}

		if (valdForm==false) {
			return false;
		}
		
		if (error == 1) {
			alert(mensaje);
			return false;
		} 

		return true;

    } 

    function myTrim(x) {
   	 return x.replace(/^\s+|\s+$/gm,'');
	}