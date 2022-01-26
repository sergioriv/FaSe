<?php include'config.php';

if($sessionRol==3){
	include'doctor.php';
} else
if($sessionRol==2){
	include'sucursal.php';
} else 
if($sessionRol==4){
	include'usuario-inventario.php';
} else {

	$clinicaUserSql = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$sessionUsuario'");
	$clinicaUserRow = $clinicaUserSql->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php echo $clinicaUserRow['cl_nombre']; ?></h4>
</div>
<form class="form" method="post" id="formUsuario" action="usuario-guardar.php" autocomplete="off">
	<div class="modal-body">
		<div class="divForm">
			<div class="container1Part">
				<input type="text" name="correo" id="correo" value="<?php echo $clinicaUserRow['cl_correo'] ?>" class="formulario__modal__input" data-label="Correo Electrónico" required>
			</div>
		
			<div id="consultorioPassword">
				<div class="consultorioPassword">Cambiar Contraseña</div>
			</div>
			<div id="consultorioCambioPassword">
				<div class="container1Part">
					<input type="password" name="passwordActual" id="passwordActual" autocomplete="off" class="formulario__modal__input" data-label="Contraseña Actual">
				</div>
				<div class="container1Part">
					<input type="password" name="newPassword" id="newPassword" autocomplete="off" class="formulario__modal__input" data-label="Nueva Contraseña">
				</div>
				<div class="container1Part">
					<input type="password" name="ConfirmPassword" id="ConfirmPassword" autocomplete="off" class="formulario__modal__input" data-label="Confirmar Contraseña">
				</div>
			</div>
		</div>
		<div id="validarUsuario"></div>
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario" id="consultorioGuardar">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
	$('#consultorioCambioPassword').hide();
	$(document).on('click', '.consultorioPassword', function(){ 
		$('#consultorioPassword').hide();
		$('#consultorioCambioPassword').show();
	});

	$('#formUsuario').submit(function() {

		
  			// Enviamos el formulario usando AJAX
	        $.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#validarUsuario').html(data);
	            }
	        })        
	        return false;
    	
    });
/*	
	$(document).on('click', '#consultorioGuardar', function(){

		var formLogin = new FormData($("#formUsuario")[0]);


	
		   	$.ajax({
		       	url:"usuario-guardar.php",  
		        method:"POST",
			    data: formLogin,
				contentType: false,
				processData: false, 
		        success:function(data){  
					$('#validarUsuario').html(data);
				}
		    });
		
	});
	*/
</script>
<?php } ?>