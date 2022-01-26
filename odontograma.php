<?php include'config-lobby.php';
$pacienteID = 100;
$fechaOdontograma = date("Ymd");
?>
<!DOCTYPE html>
<html>
<head>
	<?php include'header.php'; include'footer.php'; ?>
</head>
<body>
<style type="text/css">
.conveciones{
	width: 200px;
}
.convenciones label{
	font-size: 16px;
}
.tableOdonto{
	border-collapse: collapse;
	margin-right: 5px;
	margin-bottom: 20px;
	background: url('img/diente.jpeg') no-repeat;
    background-size: 100% 82%;
    background-position-y: bottom;
}
.tableOdonto td{
	width: 15px;
	height: 15px;
}
.tableOdonto .odontogramaPart{
	font-size: 10px;
	text-align: center;
	align-items: center;
	cursor: pointer;
}
.tituloDiente{
	text-align: center;
    font-size: 11px;
    font-weight: bold;
}
</style>

<div class="contenedorPrincipal">

		<div>
			<select id="fechaOdontograma">
				<option value="20181002">2018-10-02</option>
				<option value="20181003">2018-10-03</option>
				<option value="20181004" selected>2018-10-04</option>
			</select>

			<div class="titulo">Convenciones</div>
			<div style="display: flex;
					    flex-direction: row;
					    flex-wrap: wrap;
					    justify-content: space-between;">	
				<?php $convenciones = $con->query("SELECT * FROM convenciones");
				while ($convencionesRow = $convenciones->fetch_assoc()) {
				?>
				<div class="conveciones">
					<input type="radio" name="convencion" id="convencion<?php echo $convencionesRow['IDConvencion'] ?>" value="<?php echo $convencionesRow['cv_simbolo'] ?>" color="<?php echo $convencionesRow['cv_color'] ?>" valor="<?php echo $convencionesRow['IDConvencion'] ?>">
					<label for="convencion<?php echo $convencionesRow['IDConvencion'] ?>"><?php echo $convencionesRow['cv_nombre']." ( ".$convencionesRow['cv_simbolo']." )" ?></label>
				</div>
				<?php
				}
				?>
			</div>

			<div id="esquemaOdontograma"></div>
		</div>

	<div id="showResultsd"></div>
</div>

<script type="text/javascript">


cargarOdontograma();

$("#fechaOdontograma").change( function() {
	cargarOdontograma();
} );

function cargarOdontograma(){
	var valFecha = $("#fechaOdontograma").val();
	$.ajax({
			type: "POST",
			url: "odontograma-esquema.php",
			data: {valFecha:valFecha,pacienteID:<?php echo $pacienteID ?>},
			cache: false,
			success: function(datos){
				$('#esquemaOdontograma').html(datos);
			}
		});
};


</script>
</body>
</html>