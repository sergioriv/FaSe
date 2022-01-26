<?php include'config.php'; ?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title">Reporte citas pendientes</h4>
</div>
<form class="form" id="formReporteCitas" method="post" action="reporte-citas-excel.php">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="defecha" class="formulario__modal__input" data-label="Desde" required>
				<span></span>
				<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="hastafecha" class="formulario__modal__input" data-label="Hasta" required>
			</div>
		</div>
	</div>

	<div class="modal-footer">  
		
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Descargar</button>
		
	</div>

</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script>validar('#formReporteCitas');</script>