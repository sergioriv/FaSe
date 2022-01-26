<?php include'config.php';

$id = $_POST['id'];
$doctorID = $_POST['doctor'];

?>
<style type="text/css">
	.contenedorAgenda #horasCalendario {
		display: initial;
	}
</style>
<form class="form form-calendario" id="formNuevoHorario" method="post" action="doctor-nuevo-horario-guardar.php">

					<div class="tituloRange">Horario de atención</div>
		            <div class="containerPartRange">
						<span id="horaInicioHorarioNuevo">--</span>
						<input id="rangoHorarioNuevo" type="text" value="" data-slider-min="0" data-slider-max="1425" data-slider-step="15" data-slider-value="[0,0]"/>
						<span id="horaFinalHorarioNuevo">--</span>
			        </div>

			        <input type="hidden" id="horarioNuevoDcDe" name="horarioNuevoDcDe">
					<input type="hidden" id="horarioNuevoDcHasta" name="horarioNuevoDcHasta">
					<input type="hidden" id="horarioNuevoLibreDcDe" name="horarioNuevoLibreDcDe">
					<input type="hidden" id="horarioNuevoLibreDcHasta" name="horarioNuevoLibreDcHasta">

					<div class="tituloRange">Bloque de descanso <i>(opcional)</i></div>
		            <div class="containerPartRange">
						<span id="horaInicioHorarioNuevoLibre">--</span>
						<input id="rangoHorarioNuevoLibre" type="text" value="" data-slider-min="0" data-slider-max="1425" data-slider-step="15" data-slider-id="sliderRangeCoral" data-slider-value="[0,0]"/>
						<span id="horaFinalHorarioNuevoLibre">--</span>
			        </div>

			        <input type="hidden" name="fecha" value="<?php echo $id ?>">
			        <input type="hidden" name="doctorID" value="<?php echo $doctorID ?>">

	<button class="boton boton-primario">Guardar</button>

</form>

<script>
// Rango de Horario
$('#rangoHorarioNuevo').slider({});
$('#rangoHorarioNuevoLibre').slider({});

$("#rangoHorarioNuevo").on("slide", function(slideEvt) {
	sumar_minutos( slideEvt.value[0], 'horaInicioHorarioNuevo' , 'horarioNuevoDcDe' );
	sumar_minutos( slideEvt.value[1], 'horaFinalHorarioNuevo' , 'horarioNuevoDcHasta' );
});
$("#rangoHorarioNuevoLibre").on("slide", function(slideEvt) {
	sumar_minutos( slideEvt.value[0], 'horaInicioHorarioNuevoLibre' , 'horarioNuevoLibreDcDe' );
	sumar_minutos( slideEvt.value[1], 'horaFinalHorarioNuevoLibre' , 'horarioNuevoLibreDcHasta' );
});

sumar_minutos( 0, 'horaInicioHorarioNuevo' , 'horarioNuevoDcDe' );
sumar_minutos( 0, 'horaFinalHorarioNuevo' , 'horarioNuevoDcHasta' );
sumar_minutos( 0, 'horaInicioHorarioNuevoLibre' , 'horarioNuevoLibreDcDe' );
sumar_minutos( 0, 'horaFinalHorarioNuevoLibre' , 'horarioNuevoLibreDcHasta' );

	function sumar_minutos(valorMinuto, contenedor, input) {

		 var min_a_sumar= valorMinuto // toma los minutos
		 var fecha = new Date(0,0);
		 var nuevosMinutos = fecha.setMinutes(fecha.getMinutes() + valorMinuto);
		 var horaFinal = fecha.getHours();
		 var minutoFinal = fecha.getMinutes();
		 horaFinal = horaFinal < 10 ? '0'+horaFinal : horaFinal;
		 minutoFinal = minutoFinal < 10 ? '0'+minutoFinal : minutoFinal;

		 document.getElementById(contenedor).innerHTML = horaFinal+':'+minutoFinal;
		 document.getElementById(input).value = horaFinal+':'+minutoFinal;

	}



$('#formNuevoHorario').submit(function() {
					
			$.ajax({
		        type: 'POST',
		        url: $(this).attr('action'),
		        data: $(this).serialize(),
		        // Mostramos un mensaje con la respuesta de PHP
		        success: function(data) {
		            $('#showHorariosPersonalizados').html(data);
		            $("#consultoriosModal").modal('hide');
		    	}
		    })        
		    return false;
	});
</script>