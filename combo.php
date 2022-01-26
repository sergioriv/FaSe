<?php include'config.php'; include'pagination-modal-params.php';

$id = $_POST['id'];
$guardar = $_POST['guardar'];

if($guardar == 1){
	foreach ($_POST as $clave => $valor) {
	$_SESSION['consultoriosQuery'][$clave] = $valor;
	}

	extract($_SESSION['consultoriosQuery']);

	$precio = str_replace(',', '', $precio);

	if(!$_POST['id']){

		$query = $con->query("INSERT INTO tratamientos SET tr_idClinica='$sessionClinica', tr_idFase='1000', tr_combo='1', tr_nombre='$nombre', tr_estado='1', tr_fechaCreacion='$fechaHoy'");
		$id = $con->insert_id;

		if($query){ $_SESSION['consultoriosExito']=2; } else { $_SESSION['consultoriosExito']=1; }

		unset($_SESSION['consultoriosQuery']);

	} else {

		$id = $_POST['id'];
		$query = $con->query("UPDATE tratamientos SET tr_nombre='$nombre' WHERE IDTratamiento='$id'");
		if($query){ $_SESSION['consultoriosExito']=3; } else { $_SESSION['consultoriosExito']=1; }

		unset($_SESSION['consultoriosQuery']);
?>
		<script type="text/javascript">
			setTimeout("location.href = '<?php echo $_SESSION[concultoriosAntes] ?>'");
		</script>
<?php
	}

}



$comboRow = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Combo: '.$comboRow['tr_nombre']; }else{echo'Nuevo Combo';} ?></h4>
</div>
<form class="form" id="formCombo" method="post" action="combo.php">
	<div class="modal-body">
		<div class="divForm">

			<input type="text" name="nombre" id="nombre" value="<?php echo $comboRow['tr_nombre'] ?>" class="formulario__modal__input" data-label="Nombre">
		
		<?php if($id){ ?>
			<div id="tratamientosCombo">
				<div id="msj-comboTratamiento"></div>
				<?php  	
				$comboTratamientosQuery = "SELECT * FROM tratamientos AS tr 
				INNER JOIN combotratamientos AS cbt ON cbt.cbt_idTratamiento = tr.IDTratamiento 
				INNER JOIN fases AS fs ON tr.tr_idFase = fs.IDFase
				INNER JOIN cups ON tr.tr_idCups = cups.IDCups
				WHERE cbt_idCombo = '$id' AND tr_combo = '0' ORDER BY tr_nombre ASC";

			    $rowCount = $con->query($comboTratamientosQuery)->num_rows;

			//Initialize Pagination class and create object
			    $pagConfig = array(
					'totalRows' => $rowCount,
				    'perPage' => $numeroResultados,
					'link_func' => 'paginationComboTratamientos'
				);
			    $pagination =  new Pagination($pagConfig);

				$comboTratamientosSql = $con->query($comboTratamientosQuery." LIMIT $numeroResultados"); 
			    ?>
				<div id="nuevoTratamientoCombo">
					<div class="container7PartFormInput contRips">
						<select id="combo-fase" class="formulario__modal__input" data-label="Fase" required>
							<option value="" selected hidden>-- Seleccionar --</option>
							<?php $fasesSelSql = $con->query("SELECT * FROM fases WHERE fs_idClinica IN(0,$sessionClinica) AND NOT IDFase = 1000 ORDER BY IDFase");
								while($fasesSelRow = $fasesSelSql->fetch_assoc()){
									echo "<option value=".$fasesSelRow['IDFase'].">".$fasesSelRow['fs_nombre']."</option>";
								}
							?>
						</select>
						<span></span>
						<select id="combo-tratamiento" class="formulario__modal__input" data-label="Tratamiento" required>
							<option value="" selected hidden>-- Seleccionar fase --</option>
							
						</select>
			            <span></span>
						<input type="text" id="combo-trata-precio" class="formulario__modal__input" data-label="Valor" value="">
			            <span></span>
			            <a class="boton boton-primario guardarTratamientoCombo" data-combo="<?php echo $id ?>">Agregar</a>
			        </div>
				</div>
				<div class="titulo tituloSecundario"><a id="agregarTratamiento"><?php echo $iconoNuevo ?>Agregar tratamiento</a></div>
				
					<div id="showResultsComboTratamientos">
						<table class="tableList">
			        		<thead>
			        			<tr>
			        				<th class="columnaCorta">Precio</th>
			        				<th>CUP</th>
			        				<th>Fase</th>
			        				<th>Tratamiento</th>
			        				<th>&nbsp</th>
			        			</tr>
			        		</thead>
			            	<tbody>
			        	<?php while($comboTratamientosRow = $comboTratamientosSql->fetch_assoc()){

				        		if($comboTratamientosRow['tr_estado']==0){ $iTR = $iconW; $cTR = 'elementoEliminado';
									} else { $iTR = ''; $cTR = ''; }
			        	?>
				        		<tr class="<?php echo $cTR ?>">
				        			<td align="right"><?php echo '$'.number_format($comboTratamientosRow['cbt_precio'], 0, ".", ","); ?></td>
				        			<td align="center"><?php echo $comboTratamientosRow['cup_codigo'] ?></td>
				        			<td><?php echo $comboTratamientosRow['fs_nombre'] ?></td>
				        			<td><?php echo $iTR.$comboTratamientosRow['tr_nombre']; ?></td>
				        			<td class="tableOption">
				        				<a title="Eliminar" id="<?php echo $comboTratamientosRow['IDComboTrata'] ?>" t="comboTratamiento" class="eliminarTratamientoCombo eliminar" data-combo="<?php echo $id ?>"><?php echo $iconoEliminar ?></a>
				        			</td>
					           	</tr>
			        	<?php } ?>
			        		</tbody>
			        	</table>
			        	<?php echo $pagination->createLinks(); ?>
			        </div>
			</div>
		<?php } ?>
		</div>
	</div>
	   
	<div class="modal-footer">  

			<input type="hidden" name="guardar" value="1">
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script type="text/javascript" src="js/jquery.number.js"></script>
<script type="text/javascript">
validar('#formCombo');
$('#combo-trata-precio').number( true, 0 );

	$('#formCombo').submit(function() {
		var comboNombre = $('#nombre');

		if(comboNombre.val()!=""){

			   	$.ajax({
		            type: 'POST',
		            url: $(this).attr('action'),
		            data: $(this).serialize(),
		            // Mostramos un mensaje con la respuesta de PHP
		            success: function(data) {
		                $('#consultoriosDetails').html(data);
		                //$("#consultoriosModal").modal('hide');
		            }
		        })
		        return false;
		}
	});

<?php if($id){ ?>
	$("#nuevoTratamientoCombo").hide();
	$("#agregarTratamiento").click(function() {
		$("#agregarTratamiento").parent().remove();
        $("#nuevoTratamientoCombo").show();
        $("#agregarTratamiento").hide();
    });

	function paginationComboTratamientos(page_num) {
		page_num = page_num?page_num:0;
		$.ajax({
			type: 'POST',
			url: 'get/comboTratamientosData.php',
			data:'page='+page_num+'&id='+<?php echo $id ?>,
			success: function (html) {
				$('#showResultsComboTratamientos').html(html);
			}
		});
	}
/*
			$(document).on('click', '.consultorioComboTratamiento', function(){
				$.ajax({
					url:"combo-tratamiento.php",  
					method:"POST", 
					data:{tp:'mt',id:<?php echo $id ?>},
					success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
				});
			});
*/ /*
		$(document).on('click', '.consultorioEliminarComboTratamiento', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId > 0)
		    {  
		    	$.ajax({
		        	url:"combo-tratamiento-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
*/

	$( "#combo-fase" ).change(function() {
		var valor = $(this).val();

		$.ajax({
			url: 'extras/planTratamientos.php',
			type: 'POST',
			data: {faseID:valor},
			success:function(data){  
				$('#combo-tratamiento').html(data);
			}
		})
		
	});

	$( "#combo-tratamiento" ).change(function() {
		$( "#combo-tratamiento option:selected" ).each(function() {
			//alert( $( this ).attr('data-precio') );
			$('#combo-trata-precio').focus();
		    $('#combo-trata-precio').val( $( this ).attr('data-precio') );
		})
	});
		
<?php } ?>
</script>