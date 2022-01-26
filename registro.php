<div class="titulo">Registro</div>
<div id="msj-login"></div>
<form class="form" id="formRegistro" action="registro-guardar.php" method="post">
	<input type="text" name="nombre" id="nombre" placeholder="Nombre de la Empresa" required>
	<input type="text" name="nit" id="nit" placeholder="NIT" required>
	<input type="email" name="correo" id="correoS" placeholder="Correo Electrónico" required>
	<input type="password" name="password" id="password" placeholder="Contraseña" required>
	<input type="password" name="confirma" id="confirma" placeholder="Confirmar Contraseña" required>
	<button onclick="validacion()" class="btn-lobby btn-primary" id="registrarse">Siguiente</button>	
</form>

<script src="js/jquery-2-2-0.min.js"></script>
<script type="text/javascript">

	document.getElementById("nombre").focus();
	
	$('#formRegistro').submit(function() {

		
  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#msj-login').html(data);
	            }
	        })        
	        return false;
    	
    });
/*
	$(document).on('click', '#registrarse', function(){

		var formRegistro = new FormData($("#formRegistro")[0]);

		var nombre = 	$('#nombre');
		var nit = 		$('#nit');
		var correo = 	$('#correo');
		var password = 	$('#password');
		var confirma = 	$('#confirma');

		if(confirma.val()!=password.val()){
			confirma.addClass('validar');
			confirma.focus();
		} else { confirma.removeClass('validar'); }
		if(confirma.val()==""){
			confirma.addClass('validar');
			confirma.focus();
		}
		if(password.val()==""){
			password.addClass('validar');
			password.focus();
		} else { password.removeClass('validar'); }
		if(correo.val()==""){
			correo.addClass('validar');
			correo.focus();
		} else { correo.removeClass('validar'); }
		if(nit.val()==""){
			nit.addClass('validar');
			nit.focus();
		} else { nit.removeClass('validar'); }
		if(nombre.val()==""){
			nombre.addClass('validar');
			nombre.focus();
		} else { nombre.removeClass('validar'); }

		if(nombre.val()!="" && nit.val()!="" && correo.val()!="" && password.val()!="" && confirma.val()!="" && (confirma.val()==password.val())){
		   	$.ajax({
		       	url:"registro-guardar.php",  
		        method:"POST",
		        data: formRegistro,
				contentType: false,
				processData: false, 
		        success:function(data){  
					$('#msj-login').html(data);
				}
		    });
		}
	});
*/
</script>