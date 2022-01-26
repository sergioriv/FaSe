<?php include'config.php'; include'pagination-modal-params.php';

$ordenID = $_POST['ordenID'];
$ver = $_POST['ver'];

$ordenEntrada = $con->query("SELECT * FROM ordenesentrada WHERE IDOrdenEntrada = '$ordenID'")->fetch_assoc();

	if($ver == 1){
		$id = $ordenEntrada['ore_idProveedor'];
	} else {
		$id = $_POST['id'];
	}

$proveedorRow = $con->query("SELECT pr_nombre FROM proveedores WHERE IDProveedor = '$id'")->fetch_assoc();

if($ordenID > 0){
	$button = 'Guardar';
	$action = 'save';
} else {
	$button = 'Siguiente';
	$action = 'next';
}

$tittleForm = '';
if($id){
	$tittleForm = ' | Proveedor: '.$proveedorRow['pr_nombre'];
}



if($ver == 1){
	$disabled = 'disabled readonly';
	$classInput = 'formulario__modal__input';
	$js_modal = 'js/label-modal.js';
} else {
	$disabled = '';
	$classInput = 'formulario__input';
	$js_modal = 'js/label.js';
}

?>

<?php if($ver == 1){ ?>

<div class="modal-header">  
	<a class="close" data-dismiss="modal">&times;</a>  
	<h4 class="modal-title">Orden de Entrada<?= $tittleForm ?></h4>
</div>
<form class="form">

<?php } else { ?>

<form class="form" method="post" action="orden-entrada-guardar.php" id="formOrdenEntrada">
	<div class="modal-header modal-header-form">
	  <div class="titulo tituloSecundario">Orden de Entrada<?= $tittleForm ?></div>
	  <button class="boton boton-primario"><?= $button ?></button>
	</div>

<?php } ?>

	<div class="modal-body">
		<div class="divForm">

			<div id="msj-inventario" class="contenedorAlerta"></div>

		<?php if(!$id){ ?>

				<div class="container1Part">
					<select name="proveedor" class="<?= $classInput ?>" data-label="Proveedor" <?= $disabled ?> required>
						<option selected hidden value="">-- Seleccionar --</option>
						<?php
							$proveedoresSql = $con->query("SELECT IDProveedor, pr_nombre FROM proveedores WHERE pr_idClinica = '$sessionClinica' AND pr_estado='1' ORDER BY pr_nombre");
			            	while($proveedorRow = $proveedoresSql->fetch_assoc()){	            	
			            		echo "<option value=".$proveedorRow['IDProveedor'].">".$proveedorRow['pr_nombre']."</option>";
							}
			            ?>
		            </select>
				</div>

		<?php } else { ?>

				<input type="hidden" name="proveedor" <?= $disabled ?> value="<?= $id ?>">

		<?php } ?>
			<div class="container3PartForm">
				<input type="text" name="orden" class="<?= $classInput ?>" data-label="# Orden" value="<?= $ordenEntrada['ore_numeroOrden'] ?>" <?= $disabled ?> required>
				<span></span>
				<input type="text" name="factura" class="<?= $classInput ?>" data-label="# Factura" value="<?= $ordenEntrada['ore_numeroFactura'] ?>" <?= $disabled ?> required>
			</div>
			<div class="container3PartForm">
				<div class="container3PartForm">
					<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" max="<?php echo date('Y-m-d') ?>" name="facturaFecha" id="facturaFecha" class="<?= $classInput ?>" data-label="Fecha de Factura" value="<?= $ordenEntrada['ore_facturaFecha'] ?>" <?= $disabled ?> required>
					<span></span>
					<input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="facturaFechaVencimiento" id="facturaFechaVencimiento" class="<?= $classInput ?>" data-label="Vencimiento de Factura" value="<?= $ordenEntrada['ore_facturaFechaVencimiento'] ?>" <?= $disabled ?> required>
				</div>
				<span></span>
				<input type="text" name="facturaValor" id="facturaValor" class="<?= $classInput ?>" data-label="Valor Factura" value="<?= $ordenEntrada['ore_facturaValor'] ?>" <?= $disabled ?> required>
			</div>

<?php if($ordenID){ 

			$inventarioQuery = "SELECT sc.sc_nombre, mt.IDMaterial, mt.mt_codigo, mt.mt_nombre, me.* FROM materialesentrada AS me
					INNER JOIN materiales AS mt ON me.me_idMaterial = mt.IDMaterial
		            INNER JOIN sucursales AS sc ON me.me_idSucursal = sc.IDSucursal
		            WHERE me_idOrdenEntrada = '$ordenID' AND me_estado = '1' ORDER BY IDMatEntrada DESC ";

			$rowCount = $con->query($inventarioQuery)->num_rows;
				$pagConfig = array(
					'totalRows' => $rowCount,
				    'perPage' => $numeroResultados,
					'link_func' => 'paginationInventarioOrden'
				);
			    $pagination =  new Pagination($pagConfig);

			$inventarioSql = $con->query($inventarioQuery." LIMIT $numeroResultados");

	?>

<?php if($ver == 0){ ?>
			<div class="titulo tituloSecundario"><a id="agregarInventarioOrden"><?php echo $iconoNuevo ?>Agregar Item</a></div>
<?php } ?>

			<div id="showResultsInventarioOrden">
				<table class="tableList">
			        <thead>
			        	<th>Usuario</th>
			        	<th>Sucursal</th>
			        	<th>Item</th>
			        	<th># Lote</th>
			        	<th>Reg. Invima</th>
			        	<th>Cant.</th>
			        	<th class="columnaCorta">Vencimiento</th>
			        </thead>
			        <tbody>
			        	<?php while($inventarioRow = $inventarioSql->fetch_assoc()){

			        			$rol = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$inventarioRow[me_idUsuario]'")->fetch_assoc();
			        			$usID = $rol['us_id'];
			        			if($rol['us_idRol']==1){
			        				$usuario = $con->query("SELECT * FROM clinicas WHERE IDClinica = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['cl_nombre'];
			        			} else if($rol['us_idRol']==2){
			        				$usuario = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['sc_nombre'];
			        			} else {
			        				$usuario = $con->query("SELECT * FROM doctores WHERE IDDoctor = '$usID'")->fetch_assoc();
			        				$nombreUsuario = $usuario['dc_nombres'];
			        			}
			        	?>
							<tr>
								<td><?= $nombreUsuario ?></td>
								<td><?= $inventarioRow['sc_nombre'] ?></td>
							<?php if($ver == 1){ ?>
								<td><a class="verItem" data-id="<?= $inventarioRow['IDMaterial'] ?>"><?= $inventarioRow['mt_codigo'].' | '.$inventarioRow['mt_nombre'] ?></a></td>
							<?php } else { ?>
								<td><?= $inventarioRow['mt_codigo'].' | '.$inventarioRow['mt_nombre'] ?></td>
							<?php } ?>
								<td align="center"><?= $inventarioRow['me_numeroLote'] ?></td>
								<td align="center"><?= $inventarioRow['me_invima'] ?></td>
								<td align="center"><?= $inventarioRow['me_cantidad'] ?></td>
								<td align="center"><?= $inventarioRow['me_fechaVencimiento'] ?></td>
							</tr>
			        	<?php
			        	}
			        	?>
			        </tbody>
			    </table>
			    <?php echo $pagination->createLinks(); ?>
			</div>
<?php } ?>
		</div>
	</div>

<?php if($ver == 0){ ?>	   
	<div class="modal-footer">  

			<input type="hidden" name="ordenID" value="<?= $ordenID ?>">
			<input type="hidden" name="action" value="<?= $action ?>">
		  	<button class="boton boton-primario"><?= $button ?></button>
		
	</div>
<?php } else { ?>
	<div class="modal-footer">  
		<a class="boton boton-secundario" data-dismiss="modal">Cerrar</a>
	</div>
<?php } ?>

</form>

<script type="text/javascript" src="<?= $js_modal ?>"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">

	$('#facturaValor').number( true, 0 );

<?php if($ver == 0){ ?>
	$('#formOrdenEntrada').submit(function() {
		var fecha1 = $('#facturaFecha');
		var fecha2 = $('#facturaFechaVencimiento');

		var Nfecha1 = fecha1.val().replace('-', '');
		Nfecha1 = Nfecha1.replace('-', '');
		Nfecha1 = Nfecha1.replace('/', '');
		Nfecha1 = Nfecha1.replace('/', '');

		var Nfecha2 = fecha2.val().replace('-', '');
		Nfecha2 = Nfecha2.replace('-', '');
		Nfecha2 = Nfecha2.replace('/', '');
		Nfecha2 = Nfecha2.replace('/', '');

		if ( Number(Nfecha1) > Number(Nfecha2) ){

			fecha1.addClass('validar');
			fecha2.addClass('validar');
			return false;
		}

			fecha1.removeClass('validar');
			fecha2.removeClass('validar');

			$.ajax({
			    type: 'POST',
			    url: $(this).attr('action'),
			    data: $(this).serialize(),
				// Mostramos un mensaje con la respuesta de PHP
			    success: function(data) {
			        $('#msj-inventario').html(data);
			    }
			})        
			return false;
		});
<?php } 

if($ordenID){ ?>

	function paginationInventarioOrden(page_num) {
		page_num = page_num?page_num:0;
		$.ajax({
			type: 'POST',
			url: 'get/inventarioOrdenData.php',
			data:'page='+page_num+'&id='+<?= $ordenID ?>,
			success: function (html) {
				$('#showResultsInventarioOrden').html(html);
			}
		});
	}

	<?php if($ver == 0){ ?>
		$(document).on('click', '#agregarInventarioOrden', function(){
			$.ajax({
				url:"material-entrada.php",
				method:"POST", 
				data:{orden:<?= $ordenID ?>},
				success:function(data){  
					$('#consultoriosDetails').html(data);  
					$('#consultoriosModal').modal('show'); 
				}
			});
		});
	<?php } elseif($ver == 1){ ?>

		$(document).on('click', '.verItem', function(){
			var itemId = $(this).attr("data-id");
			$.ajax({
				url:"material.php",
				method:"POST", 
				data:{id:itemId},
				success:function(data){  
					$('.contenedorPrincipal').html(data);
					$('#consultoriosModal').modal('hide'); 
				}
			});
		});

	<?php }

} ?>

</script>