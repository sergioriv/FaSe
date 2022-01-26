<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Reporte RIPS</h4>
</div>
<form class="form" id="formReporteSirho" method="post" action="reporte-sirho-excel.php">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">	
				<select name="anio" id="anio" class="formulario__modal__input top" data-label="Año">
					<option selected hidden value="">-- Seleccionar --</option>
					<?php 
						$file_anios = file_get_contents("extras/anios.json");
						$json_anios = json_decode($file_anios, true);

						foreach ($json_anios as $value) {
							echo "<option value=".$value['anio'].">".$value['anio']."</option>";
						}
					?>
				</select>
				<span></span>
				<select name="mes" id="mes" class="formulario__modal__input top" data-label="Mes">
					<option selected hidden value="">-- Seleccionar --</option>
					<option value="01">Enero</option>
					<option value="02">Febrero</option>
					<option value="03">Marzo</option>
					<option value="04">Abril</option>
					<option value="05">Mayo</option>
					<option value="06">Junio</option>
					<option value="07">Julio</option>
					<option value="08">Agosto</option>
					<option value="09">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select>
			</div>
		</div>
	</div>

	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Descargar</button>
		
	</div>

</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script>validar('#formReporteSirho');</script>