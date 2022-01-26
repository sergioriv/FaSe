<?php include'config.php';

function __clear($var){
	$replace = array(":", "/", "-");
	return str_replace($replace, "", $var);
}

$hora = $_POST['hora'];
$horaInt = __clear($hora);
$fecha = !empty($_POST['fecha']) ? str_replace('-', '/', $_POST['fecha']) : date('Y/m/d');
$unidad = $_POST['unidad'];
$fechaInt = __clear($fecha);

$fechaArray = explode('/', $fecha);

$fechaAnno = $fechaArray[0];
$fechaMes = $fechaArray[1];
$fechaDia = $fechaArray[2];

$unidadRow = $con->query("SELECT uo_idSucursal FROM unidadesodontologicas WHERE IDUnidadOdontologica = '$unidad'")->fetch_assoc();
$sucursal = $unidadRow['uo_idSucursal'];
$lapsoTiempo = 5;


?>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Crear cita: <?= $fecha ?> - Hora de inicio: <?= $hora ?></h4>
</div>
<form id="formCrearCita" class="form" method="post" action="">
	<div class="modal-body">
		<div class="divForm">

			<select id="pacienteForm" class="formulario__modal__input" data-label="Paciente">
			</select>

			<select id="doctorForm" class="formulario__modal__input" data-label="Doctor">
				<option value="" selected hidden>-- Seleccionar --</option>
				<?php $doctores = $con->query("SELECT IDDoctor, dc_nombres, dc_atencionDe, dc_atencionHasta, dc_horarioLibreDe, dc_horarioLibreHasta FROM doctores WHERE dc_idClinica = '$sessionClinica' AND dc_estado = 1 ORDER BY dc_nombres ASC");
					while($doctoresRow = $doctores->fetch_assoc()){

						$eventoDoctor = '';
						$estadoDoctor = 'disabled';
						$dc_atencionDe = $doctoresRow['dc_atencionDe'];
						$dc_atencionHasta = $doctoresRow['dc_atencionHasta'];
						$dc_horarioLibreDe = $doctoresRow['dc_horarioLibreDe'];
						$dc_horarioLibreHasta = $doctoresRow['dc_horarioLibreHasta'];

						$horarioEspecial = $con->query("SELECT dch_atencionDe, dch_atencionHasta, dch_horarioLibreDe, dch_horarioLibreHasta FROM doctoreshorarios WHERE dch_idDoctor = '$doctoresRow[IDDoctor]' AND dch_fechaInt = $fechaInt");
						if( $horarioEspecial->num_rows >= 1 ) {

							$horarioEspecialRow = $horarioEspecial->fetch_assoc();

							$dc_atencionDe = $horarioEspecialRow['dch_atencionDe'];
							$dc_atencionHasta = $horarioEspecialRow['dch_atencionHasta'];
							$dc_horarioLibreDe = $horarioEspecialRow['dch_horarioLibreDe'];
							$dc_horarioLibreHasta = $horarioEspecialRow['dch_horarioLibreHasta'];

						}

						$verificarCita = $con->query("SELECT IDCita FROM citas WHERE ct_idDoctor = '$doctoresRow[IDDoctor]' AND ct_fechaInicio = '$fechaInt' AND ct_horaCitaDe <= '$horaInt' AND ct_horaCitaHasta >= '$horaInt' ");

						if( $verificarCita->num_rows >= 1 ){
							$eventoDoctor = 'Ocupado';
							$estadoDoctor = 'disabled';
						} else {

							if( $horaInt < __clear($dc_atencionDe) ){ $eventoDoctor = 'Disponible desde las '.$dc_atencionDe; }
							else if( $horaInt < __clear($dc_horarioLibreDe) ){ $eventoDoctor = 'Disponible hasta las '.$dc_horarioLibreDe.' (inicia descanso)'; $estadoDoctor = ''; }
							else if( $horaInt < __clear($dc_horarioLibreHasta) ){ $eventoDoctor = 'Disponible desde las '.$dc_horarioLibreHasta.' (termina descanso)'; }
							else if( $horaInt < __clear($dc_atencionHasta) ){ $eventoDoctor = 'Disponible hasta las '.$dc_atencionHasta; $estadoDoctor = ''; }
							else { $eventoDoctor = 'No disponible (terminó jornada a las '.$dc_atencionHasta.')'; }

						}



						echo '<option '.$estadoDoctor.' value='.$doctoresRow['IDDoctor'].'>'.$doctoresRow['dc_nombres'].' | '.$eventoDoctor.'</option>';
					}
				?>
			</select>

			<select id="tratamientoForm" class="formulario__modal__input" data-label="Tratamiento">
				<option value="" selected hidden>-- Seleccionar paciente --</option>
			</select>

			<div id="content-combo-tratamientoForm">
			    <select name="combo-tratamientoForm" id="combo-tratamientoForm" class="formulario__modal__input" data-label="Tratamientos de Combos">
			      	<option selected hidden value="-1">-- Seleccionar --</option>           	
			    </select>
			</div>

			<input type="hidden" name="tipo-tratamiento" id="tipo-tratamiento" value="0" readonly disabled>
			<input type="hidden" name="tratamiento-ID" id="tratamiento-ID" value="0" readonly disabled>
			<input type="hidden" name="tratamiento-valor" id="tratamiento-valor" value="0" readonly disabled>
			<input type="hidden" name="tratamiento-presupuesto" id="tratamiento-presupuesto" value="0" readonly disabled>

			<div id="duracionCita"></div>

		</div>
	</div>
	   
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript">
validar('#formCrearCita');


$(document).ready(function() {

	$('#pacienteForm').select2({
		placeholder: '-- Seleccionar --',
		ajax: {
			url: 'json-pacientes.php',
			dataType: 'json',
			delay: 250,
			processResults: function (data){
				return {
					results: data.items,
					  "pagination": {
					    "more": data.pag
					  }
				};
			},
			cache: true
		}
	});

	$( "#pacienteForm" ).change(function() {
		var pacienteForm = $(this).val();

		$.ajax({
			url: 'extras/tratamientosCita.php',
			type: 'POST',
			data: {pacienteID:pacienteForm},
			success:function(data){  
				$('#tratamientoForm').html(data);
			}
		})
	});

	var pacienteForm = 	$('#pacienteForm');
	var doctorForm = 	$('#doctorForm');
	var tratamientoForm = 	$('#tratamientoForm');
	var tipo_tratamiento = $('#tipo-tratamiento');
	var tratamientosCombo = $('#combo-tratamientoForm');
	var tratamientoID = $('#tratamiento-ID');
	var tratamientoPrecio = $('#tratamiento-valor');
	var tratamientoPresupuesto = $('#tratamiento-presupuesto');
	var tratamientoValor = 0;

	pacienteForm.change(function() { crearCita(); });
	doctorForm.change(function() { crearCita(); });
//	tratamientoForm.change(function() { crearCita(); });
//	tratamientosCombo.change(function() { crearCita(); });



	$('#content-combo-tratamientoForm').hide();


//		tipo_tratamiento.val(0);
//		tratamientoID.val(0);
//		tratamientoPrecio.val(0);

	$( "#tratamientoForm" ).change(function() {
		$('#tratamientoForm option:selected').each(function() {

			$('#combo-tratamientoForm').html('');

			if( $( this ).attr('data-combo') == 1 ){
				$('#content-combo-tratamientoForm').show();
				$( "#combo-tratamientoForm" ).attr('required','required');
				var combo = $( this ).val();
				tipo_tratamiento.val(2);
				tratamientoID.val(0);
				tratamientoPrecio.val(0);
				tratamientoPresupuesto.val(0);
				$('#duracionCita').html('');

				$.ajax({
					url: 'extras/tratamientosCombos.php',
					type: 'POST',
					data: {combo:combo},
					success:function(data){  
						$('#combo-tratamientoForm').html(data);
					}
				});

			} else {
				$('#content-combo-tratamientoForm').hide();				
				$( "#combo-tratamientoForm" ).removeAttr('required');
				tipo_tratamiento.val( $( this ).attr('data-presupuesto') );
				tratamientoID.val( $( this ).val() );
				tratamientoPrecio.val( $( this ).attr('data-valor') );
				tratamientoPresupuesto.val( $( this ).attr('data-tratamiento') );



				crearCita();
			}
			
		})
	});

				$( "#combo-tratamientoForm" ).change(function() {
					$('#combo-tratamientoForm option:selected').each(function() {
						tratamientoID.val( $( this ).val() );
						tratamientoPrecio.val( $( this ).attr('data-valor') );

						if( $(this).val() != -1 ){
							crearCita();
						}
					});

					 
				});



		function crearCita(){

			if(pacienteForm.val() == ''){ pacienteForm.addClass('validar'); }
				else { pacienteForm.removeClass('validar'); }
			if(doctorForm.val() == ''){ doctorForm.addClass('validar'); }
				else { doctorForm.removeClass('validar'); }
			if(tratamientoForm.val() == -1){ tratamientoForm.addClass('validar'); }
				else {
					tratamientoValor = tratamientoForm.val();
					tratamientoForm.removeClass('validar');
				}
			if(tipo_tratamiento.val() == 2){
				if(tratamientosCombo.val() == -1){ tratamientosCombo.addClass('validar'); }
					else {
						tratamientoValor = tratamientosCombo.val();
						tratamientosCombo.removeClass('validar');
					}
			} else { tratamientosCombo.addClass('validar'); }


		    if(pacienteForm.val() != '' && doctorForm.val() != '' && tratamientoValor != 0)
		    {
		    	if(tipo_tratamiento.val() == 2 && tratamientosCombo.val() == -1){
		    		$('#duracionCita').html('');
		    	} else {

			    	$.ajax({
			        	url:"cita-duracion.php",
			            method:"POST",  
			            data:{
			            	id:'<?= $horaInt ?>',
			            	sucursal:'<?= $sucursal ?>',
			            	unidad:'<?= $unidad ?>',
			            	doctor:doctorForm.val(),
			            	tratamiento:tratamientoID.val(),
			            	tipoTratamiento:tipo_tratamiento.val(),
			            	tratamientoPrecio:tratamientoPrecio.val(),
			            	tratamientoPresupuesto:tratamientoPresupuesto.val(),
			            	paciente:pacienteForm.val(),
			            	fecha:'<?= $fechaInt ?>',
			            	fechaAnno:'<?= $fechaAnno ?>',
			            	fechaMes:'<?= $fechaMes ?>',
			            	fechaDia:'<?= $fechaDia ?>',
			            	hora:'<?= $hora ?>',
			            	dash:1
			            },  
			            cache: false,
						success:function(data){  
							$('#duracionCita').html(data);
						}
			    	});
		    	}  
			}

		}


	


});
</script>