<?php include'config.php';

$horaInt = $_POST['id'];
$hora = $_POST['hora'];
$horaHasta = $_POST['horaHasta'];
$fecha = $_POST['fecha'];
$fechaAno = $_POST['fechaAnno'];
$fechaMes = $_POST['fechaMes'];
$fechaDia = $_POST['fechaDia'];
$sucursal = $_POST['sucursal'];
$doctor = $_POST['doctor'];
$tratamiento = $_POST['tratamiento'];
$paciente = $_POST['paciente'];
$citaID = $_POST['citaID'];


$duracionSql = $con->query("SELECT * FROM horas, citas WHERE horas.hr_horaInt > '$horaInt' AND citas.ct_horaCitaDe = horas.hr_horaInt AND citas.ct_idClinica = '$sessionClinica' AND citas.ct_fechaInicio = '$fecha' AND citas.ct_idDoctor = '$doctor' AND citas.ct_idSucursal = '$sucursal'");
$duracionRow = $duracionSql->fetch_assoc();

if($duracionRow['hr_horaInt']==0){ $horaFinalCita = $horaHasta; }
else { $horaFinalCita = $duracionRow['hr_hora']; }

$minutos = (strtotime($hora)-strtotime($horaFinalCita))/60;
$minutos = abs($minutos);

?>
<form class="form form-calendario" method="post" action="cita-guardar.php">

<div class="tituloHora"><?php echo $hora ?></div>
<div class="range-slider">
  <input class="range-slider__range" type="range" value="15" min="15" max="<?php echo $minutos ?>" step="15" name="duracion"> 
  <span class="range-slider__value">15</span>
</div>

	<input type="hidden" name="anno" value="<?php echo $fechaAno ?>">
	<input type="hidden" name="mes" value="<?php echo $fechaMes ?>">
	<input type="hidden" name="dia" value="<?php echo $fechaDia ?>">
	<input type="hidden" name="hora" value="<?php echo $hora ?>">
	<input type="hidden" name="horaInt" value="<?php echo $horaInt ?>">
	<input type="hidden" name="sucursal" value="<?php echo $sucursal ?>">
	<input type="hidden" name="doctor" value="<?php echo $doctor ?>">
	<input type="hidden" name="tratamiento" value="<?php echo $tratamiento ?>">
	<input type="hidden" name="paciente" value="<?php echo $paciente ?>">
	<input type="hidden" name="citaID" value="<?php echo $citaID ?>">

	<button class="boton boton-primario">Guardar</button>
		
</form>


<script type="text/javascript">
	// RANGE
			  var rangeSlider = function(){
			  var slider = $('.range-slider'),
			      range = $('.range-slider__range'),
			      value = $('.range-slider__value');
			    
			  slider.each(function(){

			    value.each(function(){
			      var value = $(this).prev().attr('value');
			      $(this).html(value);
			    });

			    range.on('input', function(){
			      $(this).next(value).html(this.value);
			    });
			  });
			};

			rangeSlider();
</script>