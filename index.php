<?php session_start();
$_SESSION['consultoriosValidar']=1;
$_SESSION['consultoriosLobbyCorreo']=0;
$_SESSION['consultoriosLobbyClinica']=0;
$_SESSION['consultoriosLobbyRol']=0;
$_SESSION['consultoriosLobbyUsuario']=0;
$_SESSION['consultoriosLobbyIDUsuario']=0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header-lobby.php'; ?>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>

<body>
	<div class="contenedor-lobby">
		<div class="lobby-left">
			<div class="logoppal">
			<a href="#"><img src="img/logo-lobby.png"></a>
			</div>
			<div id="info">
			  <h4>Sistema de Administración de Historias Clínicas y Gestión de Citas para Consultorios Dentales</h4>
			</div>
			<br>
			<div id="caracteristicas">
				<h4>Principales características:</h4>
				<br>
				<ul class="list-unstyled" style="padding-left: 35px; padding-right: 35px;">
				<li>FaSe te permite, el control total de tu consultorio en tiempo real.</li>
				<li>FaSe te permite, llevar el control de inventarios de tu consultorio o clínica.</li>
				<li>FaSe te permite, enviar correos automáticos a tus pacientes y doctores.</li>
				<li>FaSe te permite, agendar online, las citas de tus pacientes.</li>
				<li>FaSe te permite, llevar la Historia Clínica de tus pacientes.</li>
				<li>FaSe te permite, llevar cuentas y saldos de tus pacientes de manera controlada y organizada.</li>
				<li>FaSe te permite, registrar cada paciente con su respectiva foto, para mayor recordación y fácil ubicación.</li>
				<li>FaSe te permite, con un solo clic generar RIPS.</li>
				<li>FaSe te permite, saber en tiempo real el estado de tu cartera.</li>
				<li>FaSe te permite, generar reportes SHIRO y mucho más... </li>
				</ul>
			</div>
			<br>

			<br>
		  <div style="text-align: center; padding-left: 35px; padding-right: 35px; font-size: 12px">
				Copyright © 2018 MantizTechnology S.A.S.<br>
			  <strong><a href="https://mantiztechnology.com/" target="_blank" style="text-decoration: none">www.mantiztechnology.com</a></strong><br>
				Todos los derechos reservados. <br>
				Puedes contactarnos para aclarar tus dudas a través del whatsapp <strong>304 2020019</strong><br>
				Teléfonos:<br>
				57 + <strong>304 2020019</strong><br>
				57 + <strong>301 7007119</strong><br>
				e-mail: info@mantiztechnology.com
		  </div>
			
		</div>		
		
		<div class="lobby-right" id="formLobby">
			
			<div class="logoppalright" style="text-align: center;">
			<a href="./"><img src="img/logo-lobby2.png"></a>
			</div>
			
			<div class="titulo" id="lobbyTitulo"></div>
			<div id="msj-login"></div>
			<div class="contenedorForm" id="lobbyForm">
				<form class="form" action="validar.php" id="login" method="post">
					<input type="text" value="" name="correo" id="loginCorreo" placeholder="Correo Electrónico" required>
					<button class="btn-lobby btn-primary" id="iniciar">Siguiente</button>
					<a id="registro" class="btn-lobby btn-secondary">Regístrate para acceder al demo</a>
					<a href="https://acortar.link/accesodemofase" target="_blank" class="btn-lobby btn-mantiz">¿Cómo acceder al DEMO?</a>
				</form>
			</div>
			
				<div class="copyright-right">
				  <div style="text-align: center; padding-left: 35px; padding-right: 35px; font-size: 12px">
						Copyright © 2018 MantizTechnology S.A.S.<br>
					  <strong><a href="https://mantiztechnology.com/" target="_blank" style="text-decoration: none">www.mantiztechnology.com</a></strong><br>
						Todos los derechos reservados. <br>
						Para más información contáctenos al whatsapp <strong>304 2020019</strong><br>
						Teléfonos:<br>
						57 + <strong>304 2020019</strong><br>
						57 + <strong>301 7007119</strong><br>
						e-mail: info@mantiztechnology.com
				  </div>
			</div>
			
			
		</div>
	</div>
</body>
</html>

<script src="js/jquery-2-2-0.min.js"></script>
<script type="text/javascript" src="js/validar.js"></script>
<script type="text/javascript">

	$('#lobbyTitulo').html('Sistema de Administración de Historias Clínicas y Gestión de Citas para Consultorios Dentales');
	$('#loginCorreo').focus();

$(document).ready(function(){

	$('#login').submit(function() {

		
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

	$(document).on('click', '#registro', function(){   
		$('#formLobby').load('registro.php');
	});

});

</script>