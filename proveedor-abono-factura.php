<?php include'config.php';

echo $facturaID = $_POST['facturaID'];
echo '|';
$facturaSql = $con->query("SELECT me_factura, me_facturaValor, IDProveedor, pr_nombre FROM materialesentrada AS me INNER JOIN proveedores AS pr ON me.me_idProveedor = pr.IDProveedor WHERE IDMatEntrada = '$facturaID' ")->fetch_assoc();

$deuda = 0;

$facturaAbonos = $con->query("SELECT SUM(pra_abono) AS abonos FROM proveedores_abonos WHERE pra_idProveedor = '$facturaSql[IDProveedor]' AND pra_idFactura = '$facturaID'")->fetch_assoc();

$deuda = $facturaSql['me_facturaValor'] - $facturaAbonos['abonos'];
echo $deuda;
?>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?= $facturaSql['pr_nombre'].' | # Factura: '.$facturaSql['me_factura'] ?></h4>
</div>
<form class="form" method="post" action="proveedor-abono-guardar.php" id="formProveedorAbono">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<select name="formaPago" id="formaPago" class="formulario__modal__input" data-label="Forma de Pago" required>
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php $formasPago = $con->query("SELECT * FROM fomaspago");
						while($formasPagoRow = $formasPago->fetch_assoc()){
							$formaPagoSelected = '';
		            		if($formasPagoRow['IDFormaPago']==$abonoSQL['ab_idFormaPago']){ $formaPagoSelected = "selected"; }
							echo "<option value=".$formasPagoRow['IDFormaPago']." ".$formaPagoSelected.">".$formasPagoRow['fp_nombre']."</option>";
						}
					?>
				</select>
				<span></span>
				<input type="text" name="abono" id="abono" class="formulario__modal__input" data-label="Valor Abonar" required>
			</div>
			<div class="container3PartForm" id="formaPagoCheque">
				<select name="banco" id="banco" class="formulario__modal__input" data-label="Banco" required>
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php $bancos = $con->query("SELECT * FROM bancos");
						while($bancosRow = $bancos->fetch_assoc()){
							$bancoSelected = '';
		            		if($bancosRow['IDBanco']==$abonoSQL['ab_idBanco']){ $bancoSelected = "selected"; }
							echo "<option value=".$bancosRow['IDBanco']." ".$bancoSelected.">".$bancosRow['bnc_codigo']." | ".$bancosRow['bnc_nombre']."</option>";
						}
					?>
				</select>
				<span></span>
				<input type="text" name="cheque" id="bancocheque" value="<?php echo $abonoSQL['ab_cheque'] ?>" class="formulario__modal__input" data-label="Número del Cheque" required>
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">  
			
			<input type="hidden" name="facturaID" value="<?php echo $facturaID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>

	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formProveedorAbono');
	$('#abono').number( true, 2 );

$("#formaPagoCheque").hide();

$("#formaPago").change(function() {
	var formaPagoVal = $(this).val();
	if(formaPagoVal == 2){
    	$("#formaPagoCheque").show();
    } else {
    	$("#formaPagoCheque").hide();
    	$('#banco option').prop('selected', function() {
	        return this.defaultSelected;
	    });
    	$("#bancocheque").val('');
    	$("#bancocheque").removeClass("formulario__modal__input__action");
    	$("#bancocheque").addClass("formulario__modal__input__action__focus");
    }
});


	$('#formProveedorAbono').submit(function() {
		var abonoValor = $('#abono');
		var abonoFormaPago = $('#formaPago');

		var abonoBanco = $('#banco');
		var abonoCheque = $('#bancocheque');

		var deuda = <?= $deuda ?>;

		if(abonoValor.val()>0 && abonoFormaPago.val()>0){
			
			if(abonoFormaPago.val() == 2){
				if (abonoBanco.val() == 0){
					return false;
				}
				if (abonoCheque.val().trim() == ""){
					return false;
				}
			}
			if(abonoValor.val()>deuda){
				//abonoValor.addClass('validar');
				return false;
			} //else {
				//abonoValor.removeClass('validar');
				
			   	$.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#showResultsFacturaProveedor').html(data);
		                $("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
			//}
		}
	});


</script>