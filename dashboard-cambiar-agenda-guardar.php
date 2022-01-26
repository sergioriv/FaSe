<?php include'config.php';

$fecha_inicio = explode('-', $_POST['fecha_inicio']);
$fecha_fin = explode('-', $_POST['fecha_fin']);

$fecha_inicio_mostrar = str_replace('-', '/', $_POST['fecha_inicio']);
$fecha_fin_mostrar = str_replace('-', '/', $_POST['fecha_fin']);


$verificacion = $con->query("SELECT COUNT(*) AS cant FROM citas WHERE ct_anoCita='$fecha_fin[0]' AND ct_mesCita='$fecha_fin[1]' AND ct_diaCita='$fecha_fin[2]' AND ct_estado IN(0,1)")->fetch_assoc();

if( $verificacion['cant'] > 0 ) {
	echo '<div class="contenedorAlerta"><input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, el dia <b>'.$fecha_fin_mostrar.'</b> ya se encuntra ocupado.</div><div class="close">&times;</div></label></div>';
} else {
	
	$query_citas_inicio = $con->query("SELECT * FROM citas WHERE ct_anoCita='$fecha_inicio[0]' AND ct_mesCita='$fecha_inicio[1]' AND ct_diaCita='$fecha_inicio[2]'");
	while($row_citas_inicio = $query_citas_inicio->fetch_assoc()){

		$citaID_inicio = $row_citas_inicio['IDCita'];

		$fecha_fin_int = $fecha_fin[0].$fecha_fin[1].$fecha_fin[2];
		$fecha_orden = $fecha_fin[0].$fecha_fin[1].$fecha_fin[2].$row_citas_inicio['ct_horaCitaDe'];

		$query = $con->query("UPDATE citas SET ct_anoCita='$fecha_fin[0]', ct_mesCita='$fecha_fin[1]', ct_diaCita='$fecha_fin[2]', ct_fechaInicio='$fecha_fin_int', ct_fechaFin='$fecha_fin_int', ct_fechaOrden='$fecha_orden' WHERE IDCita = '$citaID_inicio'");

		if(!$query){ return false; }
	}

	if($query){ 
?>
		<script type="text/javascript">
			dashComparativo();
			$('#msj-comparativo').html('<input type="radio" id="alertExito"><label class="alerta exito" for="alertExito"><div>Sus cambios han sido guardados con exito.</div><div class="close">&times;</div></label>');

			$('#consultoriosModal').modal('hide');
		</script>
<?php
	} else {
?>
		<script type="text/javascript">
			dashComparativo();
			$('#msj-comparativo').html('<input type="radio" id="alertError"><label class="alerta error" for="alertError"><div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div><div class="close">&times;</div></label>');

			$('#consultoriosModal').modal('hide');
		</script>
<?php
	}
}

?>