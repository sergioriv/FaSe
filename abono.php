<?php include'config.php';

$abonoID = $_POST['abonoID'];
$abonoSQL = $con->query("SELECT * FROM abonos WHERE IDAbono = '$abonoID'")->fetch_assoc();

$tipo = $_POST['tp'];
if($tipo=='ab'){ $pacienteID = $abonoSQL['ab_idPaciente']; }
else { $pacienteID = $_POST['pacienteID']; }

$sucursalID = $_POST['sucursalID'];

$deuda = 0;
$deudaSql = $con->query("SELECT ct_costo FROM citas WHERE ct_idPaciente = '$pacienteID' AND ct_inicial='1' AND ct_estado IN(0,1)");
while($deudaRow = $deudaSql->fetch_assoc()){
	$deuda += $deudaRow['ct_costo'];
}
$abonosSql = $con->query("SELECT * FROM abonos WHERE ab_idPaciente = '$pacienteID' AND ab_estado='1'");
while($abonosRow = $abonosSql->fetch_assoc()){
	$deuda -= $abonosRow['ab_abono'];
}

if($abonoID){ $deuda += $abonoSQL['ab_abono']; }

$estado_cuenta = '$'.number_format($deuda, 0, ".", ",");

$valorMax = $deuda;

if( $deuda < $abonoSQL['ab_abono'] ){
	$valorMax = $abonoSQL['ab_abono'];
}
?>

<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($abonoID){ echo 'Recibo de caja #'.$abonoSQL['ab_consecutivo']; } else { echo 'Nuevo abono - Estado de cuenta actual '.$estado_cuenta; } ?></h4>
</div>
<form class="form" method="post" action="abono-guardar.php" id="formAbono">
	<div class="modal-body">
		<div class="divForm">
			<div class="container1PartForm">
				<select name="sucursal" id="sucursal" class="formulario__modal__input" data-label="Sucursal" required>
					<option <?php if(!$id){ echo "selected"; } ?> hidden value="">-- Seleccionar --</option>
					<?php
						$sucursalesSql = $con->query("SELECT * FROM sucursales WHERE sc_idClinica = '$sessionClinica' AND sc_estado='1' ORDER BY sc_nombre");
		            	while($sucursalesRow = $sucursalesSql->fetch_assoc()){
		            		$sucursalSelected = '';
		            		if($sucursalesRow['IDSucursal']==$abonoSQL['ab_idSucursal']){ $sucursalSelected = "selected"; }
		            		echo "<option value=".$sucursalesRow['IDSucursal']." ".$sucursalSelected.">".$sucursalesRow['sc_nombre']."</option>";						
						}
		            ?>
	            </select>
			</div>
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
				<input type="text" name="abono" id="abono" value="<?php echo $abonoSQL['ab_abono'] ?>" class="formulario__modal__input" data-label="Valor Abonar" required>
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
			<div class="container1PartForm">
				<textarea name="comentario" rows="3" class="formulario__modal__input" data-label="Comentario"><?php echo $abonoSQL['ab_comentario'] ?></textarea>
			</div>

			<div class="containerFirmas">
				<div class="content_signature">
					<?php if(!empty($abonoID)){ ?>

						<?php if(!empty($abonoSQL['ab_firmaPaciente'])){ ?>
							<img src="<?php echo $abonoSQL['ab_firmaPaciente'] ?>">
						<?php } ?>

					    <div class="option_signature_pad">
							Firma Paciente
						</div>

					<?php } else { ?>

						<canvas id="signature_pad_abono_paciente" class="signature_pad" width=400 height=200></canvas>

						<div class="option_signature_pad">
							Firma Paciente
							<span id="clear_signature_abono_paciente" title="Limpiar"><i class="fa fa-times"></i></span>
						</div>

						<input type="hidden" name="firma_abono_paciente" id="firma_abono_paciente">

					<?php } ?>
				</div>

				<div class="content_signature">
					<?php if(!empty($abonoID)){ ?>

						<?php if(!empty($abonoSQL['ab_firmaUsuario'])){ ?>
							<img src="<?php echo $abonoSQL['ab_firmaUsuario'] ?>">
						<?php } ?>

					    <div class="option_signature_pad">
							Firma Usuario
						</div>

					<?php } else { ?>

						<canvas id="signature_pad_abono_usuario" class="signature_pad" width=400 height=200></canvas>

						<div class="option_signature_pad">
							Firma Usuario
							<span id="clear_signature_abono_usuario" title="Limpiar"><i class="fa fa-times"></i></span>
						</div>

						<input type="hidden" name="firma_abono_usuario" id="firma_abono_usuario">

					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	   
	<div class="modal-footer">  
			
			<input type="hidden" name="abonoID" value="<?php echo $abonoID ?>">
			<input type="hidden" name="pacienteID" value="<?php echo $pacienteID ?>">
			<input type="hidden" name="sucursalID" value="<?php echo $sucursalID ?>">
			<input type="hidden" name="tipo" value="<?php echo $tipo ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>

	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formAbono');
	$('#abono').number( true, 0 );

<?php if($abonoSQL['ab_idFormaPago']==2){ ?>
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

<?php if(empty($abonoID)){ ?>

	/* PACIENTE */
	var signaturePad_abono_paciente = new SignaturePad(document.querySelector('#signature_pad_abono_paciente'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_abono_paciente', function(){
		signaturePad_abono_paciente.clear();
		$('#firma_abono_paciente').val(null);
	});

	$(document).on('mouseup', '#signature_pad_abono_paciente', function(){
		$('#firma_abono_paciente').val( document.querySelector('#signature_pad_abono_paciente').toDataURL() );
	});

	/* USUARIO */
	var signaturePad_abono_usuario = new SignaturePad(document.querySelector('#signature_pad_abono_usuario'), {
	  backgroundColor: 'rgba(255, 255, 255, 0)',
	  penColor: 'rgb(0, 0, 0)'
	});

	$(document).on('click', '#clear_signature_abono_usuario', function(){
		signaturePad_abono_usuario.clear();
		$('#firma_abono_usuario').val(null);
	});

	$(document).on('mouseup', '#signature_pad_abono_usuario', function(){
		$('#firma_abono_usuario').val( document.querySelector('#signature_pad_abono_usuario').toDataURL() );
	});
<?php } ?>





<?php if($tipo!='ab'){ ?>
	$('#formAbono').submit(function() {
		var abonoValor = $('#abono');
		var abonoSucursal = $('#sucursal');
		var abonoFormaPago = $('#formaPago');

		var abonoBanco = $('#banco');
		var abonoCheque = $('#bancocheque');

		var deuda = <?= $valorMax ?>;

		if(abonoValor.val()>0 && abonoSucursal.val()>0 && abonoFormaPago.val()>0){
			
			if(abonoFormaPago.val() == 2){
				if (abonoBanco.val() == 0 || abonoCheque.val().trim() == ""){
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
		                $('.listAbonos').html(data);
		                $("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
			//}
		}
	});
<?php } ?>

</script>