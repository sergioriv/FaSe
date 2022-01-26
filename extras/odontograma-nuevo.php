<?php include'../config.php';

$pacienteID = $_POST['pacienteID'];
if(isset($_POST['odontogramaID'])){
	$odontogramaID = $_POST['odontogramaID'];
	$visualizar = 1;
	$action = '';
	$odontogramaRow = $con->query("SELECT * FROM pacienteodontograma WHERE IDOdontograma='$odontogramaID'")->fetch_assoc();
} else {
	$visualizar = 0;
	$odontogramaID = 0;
	$action = 'action="odontograma-paciente-guardar.php"';
}
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Odontograma</h4>
</div>
<form class="form" id="formOdontograma" method="post" <?php echo $action ?> enctype="multipart/form-data">
	<div class="modal-body">
		<div class="divForm">
<?php if($visualizar==0){ ?>
			<div class="container3PartColumns convenciones">
				<span class="text-small">Seleccione la convención y luego clic sobre el área del diente.</span>
				<select id="convencion">
					<option value="dientedefault">Limpiar diente</option>
					<option value="sectordefault">Limpiar sector</option>
					<option value="1">Caries</option>
					<option value="2">Obturado</option>
					<option value="3">Ausente</option>
					<option value="4">Corona en buen estado</option>
					<option value="5">Corona en mal estado</option>
					<option value="6">Edentulo</option>
					<option value="7">Endodoncia en buen estado</option>
					<option value="8">Necesita Endodoncia</option>
					<option value="9">Exodoncia</option>
					<option value="10">Implante</option>
					<option value="11">Necesita Sellante</option>
					<option value="12">Obturado con caries</option>
					<option value="13">Obturado en resina</option>
					<option value="14">Prótesis fija total</option>
					<option value="15">Prótesis parcial</option>
					<option value="16">Resina preventiva</option>
					<option value="17">Diente Sano</option>
					<option value="18">Sellante</option>
				</select>
			</div>
<?php } ?>
			
			<div id="esquemaOdontograma"><?php echo $odontogramaRow['pod_contenido'] ?></div>
			
<?php
	 if($visualizar==0){ ?>
			<textarea name="notaOdontograma" rows="5" class="formulario__modal__input" data-label="Nota"></textarea>	
<?php } else { ?>
			<b>Nota:</b>
			<div class="odontogramaNota"><?php echo $odontogramaRow['pod_nota'] ?></div>
<?php } ?>
		</div>
	</div>

	<div class="modal-footer">  
<?php if($visualizar==0){ ?>
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
			<input type="hidden" name="odontogramaID" value="<?php echo $odontogramaID ?>">
			<input type="hidden" name="pacienteID" value="<?php echo $pacienteID ?>">
			<input type="hidden" name="imageOdontograma" id="imageOdontograma" >
			<input type="hidden" name="dientesafectados" id="dientesafectados" >
			<button class="boton boton-primario">Guardar</button>
<?php } else { ?>
			<a class="boton boton-primario" data-dismiss="modal">Cerrar</a>
<?php } ?>
	</div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.4/html2canvas.min.js"></script>
<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">

var dientesafectados = [];
cargarOdontograma(<?php echo $odontogramaID ?>);

function cargarOdontograma(valOdontograma){
	$.ajax({
			type: "POST",
			url: "extras/odontograma-esquema.php",
			data: {odontogramaID:valOdontograma,pacienteID:<?php echo $pacienteID ?>},
			cache: false,
			success: function(datos){
				$('#esquemaOdontograma').html(datos);
			}
		});
};

<?php if($visualizar==0){ ?>
	$('#formOdontograma').submit(function() {
		var dientesafectados = [];

		var dientes = $("[class*='diente-']");
		$.each(dientes, i => {
			if( !$(dientes[i]).hasClass('diente-sano') )
				dientesafectados.push( $(dientes[i]).attr('diente') );
		})

		$('#dientesafectados').val(dientesafectados);

		

			// Enviamos el formulario usando AJAX
			$.ajax({
	            type: 'POST',
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            // Mostramos un mensaje con la respuesta de PHP
	            success: function(data) {
	                $('#showResultsPcOdontograma').html(data);
	                $("#consultoriosModal").modal('hide');
	            }
	        })        
	        return false;
  			
    	
    });
<?php } ?>
</script>
							