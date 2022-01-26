<?php include'config.php';

$ordenID = $_POST['orden'];
if(empty($ordenID)){

	$egresoID = $_POST['egresoID'];
	$egresoSQL = $con->query("SELECT * FROM ordenesabonos WHERE IDOrdenAbono = '$egresoID'")->fetch_assoc();

	$ordenID = $egresoSQL['pra_idOrden'];
}

$cuentaOrden = 0;
	$abonosCuenta = $con->query("SELECT SUM(pra_abono) AS abonos FROM ordenesabonos WHERE pra_idOrden = '$ordenID' AND pra_estado = 1")->fetch_assoc();

	$facturaCuenta = $con->query("SELECT ore_numeroOrden, ore_numeroFactura, ore_facturaValor, pr_nombre FROM ordenesentrada AS ore
			INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
			WHERE IDOrdenEntrada = '$ordenID' AND ore_estado = 1")->fetch_assoc();

	$cuentaOrden = $facturaCuenta['ore_facturaValor'] - $abonosCuenta['abonos'];

	$saldoFactura = '$'.number_format($cuentaOrden, 2, '.', ',');

if( $cuentaOrden >= $egresoSQL['pra_abono'] ){
	$valorMax = $cuentaOrden;
} else {
	$valorMax = $egresoSQL['pra_abono'];
}
?>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?= $facturaCuenta['pr_nombre'].' | # Orden: '.$facturaCuenta['ore_numeroOrden'].' | # Factura: '.$facturaCuenta['ore_numeroFactura'].' | Saldo Factura: '.$saldoFactura ?></h4>
</div>
<form class="form" method="post" action="orden-abono-guardar.php" id="formOrdenAbono">
	<div class="modal-body">
		<div class="divForm">
			<div class="container3PartForm">
				<select name="formaPago" id="formaPago" class="formulario__modal__input" data-label="Forma de Pago" required>
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php $formasPago = $con->query("SELECT * FROM fomaspago");
						while($formasPagoRow = $formasPago->fetch_assoc()){
							$formaPagoSelected = '';
		            		if($formasPagoRow['IDFormaPago']==$egresoSQL['pra_idFormaPago']){ $formaPagoSelected = "selected"; }
							echo "<option value=".$formasPagoRow['IDFormaPago']." ".$formaPagoSelected.">".$formasPagoRow['fp_nombre']."</option>";
						}
					?>
				</select>
				<span></span>
				<input type="text" name="abono" id="abono" min="1" class="formulario__modal__input" data-label="Valor Abonar" value="<?= $egresoSQL['pra_abono'] ?>" required>
			</div>
			<div class="container3PartForm" id="formaPagoCheque">
				<select name="banco" id="banco" class="formulario__modal__input" data-label="Banco" required>
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php $bancos = $con->query("SELECT * FROM bancos");
						while($bancosRow = $bancos->fetch_assoc()){
							$bancoSelected = '';
		            		if($bancosRow['IDBanco']==$egresoSQL['pra_idBanco']){ $bancoSelected = "selected"; }
							echo "<option value=".$bancosRow['IDBanco']." ".$bancoSelected.">".$bancosRow['bnc_codigo']." | ".$bancosRow['bnc_nombre']."</option>";
						}
					?>
				</select>
				<span></span>
				<input type="text" name="cheque" id="bancocheque" class="formulario__modal__input" data-label="Número del Cheque" value="<?= $egresoSQL['pra_cheque'] ?>" required>
			</div>
			<div class="container1PartForm">
				<textarea name="comentario" rows="3" class="formulario__modal__input" data-label="Comentario"><?= $egresoSQL['pra_comentario'] ?></textarea>
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">  
			
			<input type="hidden" name="ordenID" value="<?php echo $ordenID ?>">
			<input type="hidden" name="egresoID" value="<?php echo $egresoID ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>

	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formOrdenAbono');
	$('#abono').number( true, 0 );

<?php if($egresoSQL['pra_idFormaPago']==2){ ?>
	$("#formaPagoCheque").show();
<?php } else { ?>
	$("#formaPagoCheque").hide();
<?php } ?>

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


	$('#formOrdenAbono').submit(function() {
		var abonoValor = $('#abono');
		var abonoFormaPago = $('#formaPago');

		var abonoBanco = $('#banco');
		var abonoCheque = $('#bancocheque');

		var cuentaOrden = <?= $valorMax ?>;

		if(abonoValor.val()>0 && abonoFormaPago.val()>0){
			
			if(abonoFormaPago.val() == 2){
				if (abonoBanco.val() == 0 || abonoCheque.val().trim() == ""){
					return false;
				}
			}
			if(abonoValor.val()>cuentaOrden){
				abonoValor.addClass('validar');
				return false;
			} else {
				abonoValor.removeClass('validar');
				
			   	$.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#msj-factura').html(data);
		                $("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
			}
		}
	});


</script>