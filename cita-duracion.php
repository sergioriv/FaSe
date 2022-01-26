<?php include'config.php';

$horaInt = (int)$_POST['id'];
$hora = $_POST['hora'];
$horaHasta = $_POST['horaHasta'];
$fecha = $_POST['fecha'];
$fechaAno = $_POST['fechaAnno'];
$fechaMes = $_POST['fechaMes'];
$fechaDia = $_POST['fechaDia'];
$sucursal = $_POST['sucursal'];
$unidad = $_POST['unidad'];
$doctor = $_POST['doctor'];
$tratamiento = $_POST['tratamiento'];
$tipoTratamiento = $_POST['tipoTratamiento'];
$tratamientoPrecio = $_POST['tratamientoPrecio'];
$tratamientoPresupuesto = $_POST['tratamientoPresupuesto'];
$paciente = $_POST['paciente'];
$citaID = $_POST['citaID'];
$lapsoTiempo = 5;

$sucursalRow = $con->query("SELECT sc_atencionHasta FROM sucursales WHERE IDSucursal = '$sucursal'")->fetch_assoc();
$sucursalHorarioHasta = str_replace(":","",$sucursalRow['sc_atencionHasta']);

$doctorRow = $con->query("SELECT IDDoctor, dc_horarioLibreDe, dc_horarioLibreHasta, dc_atencionHasta FROM doctores WHERE IDDoctor = '$doctor'")->fetch_assoc();

$horarioEspecialSql = $con->query("SELECT * FROM doctoreshorarios WHERE dch_idDoctor = '$doctor' AND dch_fechaInt = '$fecha' ORDER BY IDDocHorario DESC");

if($horarioEspecialSql->num_rows >= 1){

	$horarioEspecial = $horarioEspecialSql->fetch_assoc();
	$horarioLibreDe = str_replace(":", "", $horarioEspecial['dch_horarioLibreDe']);
	$horarioLibreHasta = $horarioEspecial['dch_horarioLibreHasta'] == '00:00' ? str_replace(":", "", $horarioEspecial['dch_atencionHasta']) : str_replace(":", "", $horarioEspecial['dch_horarioLibreHasta']);
	$horarioAMostrarDe = $horarioEspecial['dch_horarioLibreDe'];
	$horarioAMostrarHasta = $horarioEspecial['dch_horarioLibreHasta'] == '00:00' ? $horarioEspecial['dch_atencionHasta'] : $horarioEspecial['dch_horarioLibreHasta'] ;

	$horarioAtencionHasta = str_replace(":", "", $horarioEspecial['dch_atencionHasta']);
	$horarioAtencionHastaMostrar = $horarioEspecial['dch_atencionHasta'];

} else {
	$horarioLibreDe = str_replace(":", "", $doctorRow['dc_horarioLibreDe']);
	$horarioLibreHasta = $doctorRow['dc_horarioLibreHasta'] == '00:00' ? str_replace(":","",$doctorRow['dc_atencionHasta']) : str_replace(":", "", $doctorRow['dc_horarioLibreHasta']) ;
	$horarioAMostrarDe = $doctorRow['dc_horarioLibreDe'];
	$horarioAMostrarHasta = $doctorRow['dc_horarioLibreHasta'] == '00:00' ? $doctorRow['dc_atencionHasta'] : $doctorRow['dc_horarioLibreHasta'] ;

	$horarioAtencionHasta = str_replace(":", "", $doctorRow['dc_atencionHasta']);
	$horarioAtencionHastaMostrar = $doctorRow['dc_atencionHasta'];
}

if($horarioAtencionHasta <= $sucursalHorarioHasta){
	$duracionCita_atencionHasta = $horarioAtencionHasta;
	$duracionCita_atencionHastaMostrar = $horarioAtencionHastaMostrar;
} else {
	$duracionCita_atencionHasta = str_replace(":","",$sucursalRow['sc_atencionHasta']);
	$duracionCita_atencionHastaMostrar = $sucursalRow['sc_atencionHasta'];
}

$duracionCita = $con->query("SELECT * FROM citas WHERE ct_idClinica = '$sessionClinica' AND (ct_idUnidad = '$unidad' OR ct_idDoctor = '$doctor') AND ct_fechaInicio = '$fecha' AND ct_horaCitaDe > '$horaInt' AND ct_estado IN(0,1) ORDER BY ct_horaCitaDe ASC")->fetch_assoc();

if($duracionCita['ct_horaCitaDe'] <= $horarioLibreDe){
	
	if($duracionCita['ct_horaCitaDe'] == $horarioLibreDe){
		$horaSiguiente = $horarioLibreDe;
		$horaSiguienteMostar = $horarioAMostrarDe;
	} else{
		$horaSiguiente = $duracionCita['ct_horaCitaDe'];
		$horaSiguienteMostar = $duracionCita['ct_horaCita'];
	}

} else {

	if($horaInt < $horarioLibreDe){
		$horaSiguiente = $horarioLibreDe;
		$horaSiguienteMostar = $horarioAMostrarDe;
	} else
	if($duracionCita['ct_horaCitaDe'] < $duracionCita_atencionHasta){
		$horaSiguiente = $duracionCita['ct_horaCitaDe'];
		$horaSiguienteMostar = $duracionCita['ct_horaCita'];
	} else {
		$horaSiguiente = $duracionCita_atencionHasta;
		$horaSiguienteMostar = $duracionCita_atencionHastaMostrar;
	}

}
if(!$duracionCita){

	if($horaInt > $horarioLibreDe){
		$horaSiguiente = $duracionCita_atencionHasta;
		$horaSiguienteMostar = $duracionCita_atencionHastaMostrar;
	} else {
		$horaSiguiente = $horarioLibreDe;
		$horaSiguienteMostar = $horarioAMostrarDe;
	}
}

$minutos = (strtotime($hora)-strtotime($horaSiguienteMostar));
$minutos = abs($minutos/60);

?>
<form id="formCita" class="form form-calendario" method="post" action="cita-guardar.php">

<div class="tituloHora" id="tituloHora"></div>
<span class="tituloHora" style="color: var(--colorSecondary)">Duración de la Cita</span>
<div class="range-slider" onmousemove="sumar_minutos();" >

  <input  class="range-slider__range" type="range" value="<?php echo $lapsoTiempo ?>" min="<?php echo $lapsoTiempo ?>" max="<?php echo $minutos ?>" step="<?php echo $lapsoTiempo ?>" name="duracion" id="duracion"> 
  <span  id="range-slider" class="range-slider__value"><?php echo $lapsoTiempo ?></span>
</div>

<div class="content_option_predef">
	
	<?php

	$minutos < 90 ? $duracionPredf = $minutos : $duracionPredf = 90 ;

	for ($i=15; $i <= $duracionPredf;) { 
		
		echo '<span class="option_predef" data-value="'.$i.'">'.$i.' min</span>';
		$i+=15;
	}

	?>
</div>

	<input type="hidden" name="annoCita" value="<?php echo $fechaAno ?>">
	<input type="hidden" name="mesCita" value="<?php echo $fechaMes ?>">
	<input type="hidden" name="diaCita" value="<?php echo $fechaDia ?>">
	<input type="hidden" id="horaCita" name="horaCita" value="<?php echo $hora ?>">
	<input type="hidden" name="horaInt" value="<?php echo $horaInt ?>">
	<input type="hidden" name="sucursal" value="<?php echo $sucursal ?>">
	<input type="hidden" name="unidad" value="<?php echo $unidad ?>">
	<input type="hidden" name="doctor" value="<?php echo $doctor ?>">
	<input type="hidden" name="tratamiento" value="<?php echo $tratamiento ?>">
	<input type="hidden" name="tipoTratamiento" value="<?php echo $tipoTratamiento ?>">
	<input type="hidden" name="tratamientoPrecio" value="<?php echo $tratamientoPrecio ?>">
	<input type="hidden" name="tratamientoPresupuesto" value="<?php echo $tratamientoPresupuesto ?>">
	<input type="hidden" name="paciente" value="<?php echo $paciente ?>">
	<input type="hidden" name="citaID" value="<?php echo $citaID ?>">
	<input type="hidden" name="dash" value="<?php echo $_POST['dash'] ?>">
	<input type="hidden" name="dashDate" id="dashDate">

	<p>&nbsp</p>
 	<a class="boton boton-primario" id="confirmarCita">Guardar</a>
	
		
</form>

<!--<script type="text/javascript" src="https://momentjs.com/downloads/moment.js" ></script>-->
<script type="text/javascript">


<?php if($_POST['dash']==1){ ?>

	$('#dashDate').val( $( "#dashComparativoFechaInput" ).val() );

<?php } ?>


//predefinidas
//

	$('.option_predef').on( "click", function() {

		$('.range-slider__range').val( $(this).attr('data-value') );
		$('#range-slider').html( $(this).attr('data-value') );

		sumar_minutos();

	});

	

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

	sumar_minutos(); //LLamar para que sea estable
			rangeSlider();

	
 function sumar_minutos() {

 var tiempo= $("#horaCita").val(); //Variable hora
 var min_a_sumar= document.getElementById("range-slider").innerHTML; // toma los minutos
     
 //SEPARAR HORA 
var tiempo_1= tiempo.split(":");
//SEPARA VALORES DE TIEMPO ( HORA : MINUTOS) Y CONVERSION A ENTERO
var tiempo_hora=parseInt(tiempo_1[0]);
var tiempo_minutos=parseInt(tiempo_1[1]);
min_a_sumar = parseInt(min_a_sumar);
var min_sum = (min_a_sumar+tiempo_minutos)%60;
var hora_sum =(min_a_sumar+tiempo_minutos)/60;
tiempo_hora=tiempo_hora+parseInt(hora_sum);
tiempo_minutos=min_sum;
	if ((tiempo_minutos%60)==0)
	{  tiempo_minutos="00";}

tiempo_hora=tiempo_hora.toString(); //pasar a string
	if (tiempo_hora.length==1) 
  {  tiempo_hora="0"+tiempo_hora; }

tiempo_minutos = tiempo_minutos < 10 && tiempo_minutos > 0 ? '0'+tiempo_minutos : tiempo_minutos;

//MOSTRAR EN DIV
  document.getElementById('tituloHora').innerHTML = "<b>Inicio Cita:</b> "+tiempo+" - <b>Fin Cita:</b> "+tiempo_hora+":"+tiempo_minutos;
}

</script>