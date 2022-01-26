<?php include'config.php'; include'encrypt.php';

$mesesArray = array('0' => '0', 
		'1' => 'Enero', 
		'2' => 'Febrero', 
		'3' => 'Marzo', 
		'4' => 'Abril', 
		'5' => 'Mayo', 
		'6' => 'Junio', 
		'7' => 'Julio', 
		'8' => 'Agosto', 
		'9' => 'Septiembre', 
		'10' => 'Octubre', 
		'11' => 'Noviembre', 
		'12' => 'Diciembre'
	);

$citaID = $_POST['citaID'];
$date = $_POST['date'];

$citaSql = $con->query("SELECT * FROM citas AS ct
	INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
	INNER JOIN doctores AS dc ON ct.ct_idDoctor = dc.IDDoctor
	WHERE IDCita = '$citaID'")->fetch_assoc();

$concentimientoSql = $con->query("SELECT * FROM concentimientos WHERE ctm_idCita = '$citaID'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion = '$citaSql[pc_idIdentificacion]'")->fetch_assoc();
if($tipoDocumento){

	if($citaSql['pc_idSexo']==1){
		$identificacionPaciente = 'identificado con ';
	} else {
		$identificacionPaciente = 'identificada con ';
	}

	$identificacionPaciente .= '<b>'.$tipoDocumento['ti_nombre'].' '.$citaSql['pc_identificacion'].'</b>';
} else {
	$identificacionPaciente .= "identificado como aparece al pie de mi firma";
}
?>

<style type="text/css">
	.table_concentimiento li{
		margin-left: 30px;
		padding: 5px 0;
	}
	.table_concentimiento td{
		padding: 5px 0;
	}
</style>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Concentimiento Informado</h4>
</div>
<div class="form">

<div class="modal-body divForm">

	<div id="msj-cita-concentimiento" class="contenedorAlerta"></div>
<!--[CDATA[
	<div class="containerPart titulo tituloSecundario">
		<span></span>
		<a href="cita-pdf.php?q=<?= encrypt( 'id='.$citaID ) ?>"><i class="fa fa-download"></i>Descargar</a>
	</div>
-->
	<table class="table_concentimiento">
		<tr>
			<td>
				Yo, <b><?= $citaSql['pc_nombres'] ?></b> <?= $identificacionPaciente ?>, declaro que he sido informado por el odontólogo Dr. <b><?= $citaSql['dc_nombres'] ?></b> después de haber realizado el examen diagnóstico de los procedimientos odontológicos que se van a realizar, que me ha explicado en forma suficiente y adecuada en que consiste el tratamiento y cuáles son sus consecuencias, ventajas, riesgos posibles complicaciones o molestias que puedan presentarse, ya que no puede asegurarse que el tratamiento tenga éxito en todos los casos.
			</td>
		</tr>
		<tr>
			<td>Declaro que se sido informado por el doctor abajo firmante de lo siguiente:</td>
		</tr>
		<tr>
			<td>
				<ol>
					<li>Que es conveniente que en mi situación proceda a realizar tratamiento    odontológico en los dientes definidos en el plan de tratamiento.</li>
					<li>Que el propósito principal del tratamiento odontológico es la eliminación del   tejido cariado, afectado, infamado infectado o enfermo que puede precisar de diferentes tipos de técnicas y tratamientos.</li>
					<li>Que el tratamiento implica la administración de anestésico local que consiste en proporcionar mediante una inyección sustancias que provocan un bloqueo reversible de los nervios de tal manera que se inhibe transitoriamente la sensibilidad con el fin de realizar el tratamiento sin dolor.</li>
					<li>Que la anestesia produce sensación de acorchamiento del labio o de la cara, que normalmente van a desaparecer en dos o tres horas, aunque con menos frecuencia en menor tiempo.</li>
					<li>Que la colocación de la anestesia puede producir en el punto en el que se administre la inyección, ulceración de la mucosa y dolor, y menos frecuentemente, limitaciones en el movimiento de apertura de la boca, que pueden requerir tratamiento ulterior, y que la anestesia puede provocar baja de tensión, que en los casos menos frecuentes puede provocar un sincope o fibrilación ventricular, que deben tratarse posteriormente.</li>
					<li>Que, aunque de mis antecedentes personales no se deducen posibles alergias al agente anestésico, la anestesia puede provocar urticaria, dermatitis, asma, edema angioneurótico (asfixia), que en casos extremos puede requerir tratamiento urgente.</li>
					<li>Que las obturaciones en amalgama, resina o monómero consisten en limpiar el tejido cariado o enfermo de la cavidad dentaria y rellenarla posteriormente con el propósito de restaurar los tejidos dentarios duros y proteger la pulpa para conservar el diente y su función restableciendo siempre que sea posible la estética adecuada.

						<ul>
							<li>Que es posible que se produzca mayor sensibilidad, sobre todo al frio, que normalmente desaparecerá de modo espontáneo.</li>
							<li>Que es recomendable volver si se advierten signos de movilidad o alteraciones en la mordida, pues en este caso es preciso ajustar la oclusión para aliviar el dolor y evitar el trauma.</li>
							<li>Comprendo que el sellado hermético de las obturaciones puede reactivar procesos infecciosos que hagan necesaria la endodoncia y que especialmente si la caries es profunda o extensa, el diente quedara frágil y podrá ser necesario llevar a cabo otro tipo de reconstrucción o corona que no están incluido en el POS.</li>
							<li>También comprendo que es posible que no me encuentre satisfecho con la forma y el color del diente después del procedimiento, ya que las cualidades de los materiales nunca será idénticas al tejido sano.</li>
						</ul>

					</li>
					<li>Que la endodoncia consiste en la eliminación de tejido palpar inflamado, infectado o enfermo y rellenar la cámara palpar y los tejidos radiculares con un material que selle la cavidad e impida el paso a las bacterias y toxinas infecciosas, conservando el diente.

						<ul>
							<li>Que a pesar de realizarse correctamente la técnica cabe la posibilidad de que la infección o el proceso quístico granulomatoso no se eliminen totalmente, por lo que puede ser necesario la cirugía apical, al cabo de algunas semanas, meses o incluso años, cuyo procedimiento no hace parte de POS. A pesar de realizarse correctamente la técnica, es posible que no se obtenga el relleno total de los conductos, por lo que también es necesario proceder a la repetición del tratamiento, como en el caso de que el relleno quede corto o largo.</li>
							<li>Que es muy posible que después de la endodoncia el diente cambie de color y se oscurezca, puede presentar dolor, e inflamación en la zona afectada, que debe tratarse con la medicación indicada.</li>
							<li>Que es posible que el diente en que se realice la endodoncia se debilite y tienda a fracturarse, por lo que puede ser necesario realizar coronas protésicas e insertar retenedores intraradiculares, que no están incluidos en el POS.</li>
						</ul>

					</li>
					<li>Que la exodoncia consiste en la aplicación de un fórceps a la corona practicando luxación con movimientos de lateralidad de manera que pueda desprender fácilmente del alvéolo donde está insertada.

						<ul>
							<li>Que, aunque se realizarán los medios diagnósticos requeridos, comprendo que es posible que los estados inflamatorios de los dientes a extraer puedan producir un proceso infeccioso que pueda requerir tratamiento con antibióticos y/o anti-inflamatorio, del mismo modo que en el curso del procedimiento pueda producirse una hemorragia que exigiría para detenerla, la colocación en el alvéolo de una sustancia o de sutura. También sé que, durante el procedimiento, pueda producirse la fractura de la corona o de la raíz y aunque no es frecuente heridas en la mucosa o en la mejilla o en la lengua, inserción de la raíz en el seno maxilar, o fracturas óseas que no dependen de la forma o modo de practicarse, ni de la correcta realización, sino que son imprevisibles, en cuyo caso el odontólogo tomara las medidas necesarias para continuar con el procedimiento.</li>
							<li>Que todo acto quirúrgico lleva implícitas una serie de complicaciones comunes y potencialmente serias que podrían requerir tratamientos complementarios tanto médicos como quirúrgicos.</li>
						</ul>

					</li>
				</ol>	
			</td>
		</tr>
		<tr>
			<td>Entiendo por lo tanto que en el curso del tratamiento pueden presentarse situaciones especiales e imprevistas y procedimientos adicionales. Por ello manifiesto que estoy satisfecho con la información recibida y comprendo el alcance y los riesgos del tratamiento y en tales condiciones consiento que se me practique el tratamiento que me ha explicado el odontólogo en la IPS <b><?= strtoupper($clinicaRow['cl_nombre']) ?></b> a los <?= date('d') ?> días del mes de <?= $mesesArray[date('n')] ?> de <?= date('Y') ?>.</td>
		</tr>
		<tr>
			<td>El suscrito odontólogo deja constancia que ha explicado la naturaleza, propósito, ventajas, riesgos, y alternativas de tratamiento y que ha respondido las preguntas formuladas por el paciente o persona responsable de este.</td>
		</tr>
	</table>

	<div class="containerFirmas">
								<div class="content_signature">
									<?php if($concentimientoSql['IDConcentimiento']){ ?>

										<?php if(!empty($concentimientoSql['ctm_firmaPaciente'])){ ?>
											<img src="<?php echo $concentimientoSql['ctm_firmaPaciente'] ?>">
										<?php } ?>

									    <div class="option_signature_pad">
											Firma Paciente
										</div>

									<?php } else { ?>

										<canvas id="signature_pad_concent_paciente" class="signature_pad" width=400 height=200></canvas>

										<div class="option_signature_pad">
											Firma Paciente
											<span id="clear_signature_concent_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
										</div>

										<input type="hidden" name="firma_concent_paciente" id="firma_concent_paciente">

									<?php } ?>
									
								</div>

								<div class="content_signature">
									<?php if($concentimientoSql['IDConcentimiento']){ ?>

										<?php if(!empty($concentimientoSql['ctm_firmaDoctor'])){ ?>
											<img src="<?php echo $concentimientoSql['ctm_firmaDoctor'] ?>">
										<?php } ?>

									    <div class="option_signature_pad">
											Firma Doctor
										</div>

									<?php } else { ?>

										<canvas id="signature_pad_concent_usuario" class="signature_pad" width=400 height=200></canvas>

										<div class="option_signature_pad">
											Firma Doctor
											<span id="clear_signature_concent_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
										</div>

										<input type="hidden" name="firma_concent_usuario" id="firma_concent_usuario">

									<?php } ?>
								</div>
							</div>
	
</div>

<div class="modal-footer">
	<?php if(!$concentimientoSql['IDConcentimiento']){ ?>
		<input type="hidden" id="ct_info_citaID" value="<?= $citaID ?>">
		<input type="hidden" id="ct_info_date" value="<?= $date ?>">

		<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a>
		<a class="boton boton-primario btn_cita_concentimiento_guardar">Guardar</a>
	<?php } else { ?>
		<a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
	<?php } ?>
</div>
   
</div>


<script type="text/javascript">
<?php if(!$concentimientoSql['IDConcentimiento']){ ?>

	// FIRMA PACIENTE
	var signaturePad_concent_paciente = new SignaturePad(document.querySelector('#signature_pad_concent_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_concent_paciente', function(){
		signaturePad_concent_paciente.clear();
		$('#firma_concent_paciente').val(null);
	});

	$(document).on('mouseup', '#signature_pad_concent_paciente', function(){
		$('#firma_concent_paciente').val( document.querySelector('#signature_pad_concent_paciente').toDataURL() );
	});

	// FIRMA USUARIO
	var signaturePad_concent_usuario = new SignaturePad(document.querySelector('#signature_pad_concent_usuario'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_concent_usuario', function(){
		signaturePad_concent_usuario.clear();
		$('#firma_concent_usuario').val(null);
	});

	$(document).on('mouseup', '#signature_pad_concent_usuario', function(){
		$('#firma_concent_usuario').val( document.querySelector('#signature_pad_concent_usuario').toDataURL() );
	});

<?php } ?>
</script>