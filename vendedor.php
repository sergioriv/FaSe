<?php include'config.php'; include'pagination-modal-params.php';

$id = $_POST['id'];

$vendedorRow = $con->query("SELECT * FROM vendedores WHERE IDVendedor = '$id'")->fetch_assoc();
?>
<div class="modal-header">  
  <a class="close" data-dismiss="modal">&times;</a>  
  <h4 class="modal-title"><?php if($id){ echo 'Vendedor: '.$vendedorRow['vn_nombre']; } else {echo'Nuevo Vendedor';} ?></h4>
</div>
<form class="form" id="formVendedor" method="post" action="vendedor-guardar.php">
	<div class="modal-body">

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Información</label>
<?php if($id){ ?>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Mis referidos</label>
<?php } ?>
			<div class="contenidoTab">

				<div class="divForm" id="content-1">
					<input type="text" name="nombre" value="<?php echo $vendedorRow['vn_nombre'] ?>" class="formulario__modal__input" data-label="Nombre" required>
					<input type="text" name="telefono" value="<?php echo $vendedorRow['vn_telefono'] ?>" class="formulario__modal__input" data-label="Teléfono">
				</div>

<?php if($id){ ?>
				<div class="divForm" id="content-2">
					<?php $vnReferidosQuery = "SELECT * FROM citas AS ct
									INNER JOIN pacientes AS pc ON ct.ct_idPaciente = pc.IDPaciente
									INNER JOIN tratamientos AS tr ON ct.ct_idTratamiento = tr.IDTratamiento
									WHERE pc.pc_idReferido = 'V-$id' AND ct.ct_inicial = '1' ORDER BY ct.ct_fechaOrden DESC ";

					$rowCountVnReferidos = $con->query($vnReferidosQuery)->num_rows;

					//Initialize Pagination class and create object
					    $pagConfig = array(
							'totalRows' => $rowCountVnReferidos,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationVnReferidos'
						);
					    $pagination =  new Pagination($pagConfig);

					$vnReferidosSql = $con->query($vnReferidosQuery." LIMIT $numeroResultados");

					if($rowCountVnReferidos>0){
					?>
					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><span class="cantRegistros" id="countVnReferidos">Cantidad: [<?php echo $rowCountVnReferidos ?>]</span></div>
						<div class="titulo_optional_search form">
							<a class="consultorioDescargar" data-page="vendedor_referidos" data-rango-de="vnReferidoRangoDe" data-rango-hasta="vnReferidoRangoHasta" data-rango-id="<?= $id ?>"><i class="fa fa-download"></i>Descargar</a>
							<input type="date" id="vnReferidoRangoDe" class="formulario__modal__input" data-label="Fecha de" onchange="paginationVnReferidos();">
							<input type="date" id="vnReferidoRangoHasta" class="formulario__modal__input" data-label="Fecha hasta" onchange="paginationVnReferidos();">
						</div>
					</div>
					<?php } ?>
							
					<div id="showResultsVnReferidos">
						<table class="tableList">
							<thead>
								<tr>
									<th class="columnaCorta">Fecha</th>
									<th>Paciente</th>
									<th>Tratamiento</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
								<?php while($vnReferidosRow = $vnReferidosSql->fetch_assoc()){ 
									$fechaVnReferido = $vnReferidosRow['ct_anoCita'].'/'.$vnReferidosRow['ct_mesCita'].'/'.$vnReferidosRow['ct_diaCita'];
								?>
								<tr>
									<td align="center"><?php echo $fechaVnReferido ?></td>
									<td><?php echo $vnReferidosRow['pc_nombres'] ?></td>
									<td><?php echo $vnReferidosRow['tr_nombre'] ?></td>
									<td align="right"><?php echo '$'.number_format($vnReferidosRow['ct_costo'], 0, ".", ",")  ?></td>
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
	</div>
	   
	<div class="modal-footer">  
		
			<input type="hidden" name="id" value="<?php echo $id ?>">
			<a class="boton boton-secundario" data-dismiss="modal">Cancelar</a> 
			<button class="boton boton-primario">Guardar</button>
		
	</div>
</form>

<script type="text/javascript" src="js/label-modal.js"></script>
<script>
validar('#formVendedor');

<?php if($id){ ?>



		function paginationVnReferidos(page_num) {
			page_num = page_num?page_num:0;
			var vnReferidoRangoDe = $('#vnReferidoRangoDe').val();
			var vnReferidoRangoHasta = $('#vnReferidoRangoHasta').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/vnReferidosData.php',
		        data:'page='+page_num+'&id='+<?php echo $id ?>+'&vnReferidoRangoDe='+vnReferidoRangoDe+'&vnReferidoRangoHasta='+vnReferidoRangoHasta,
		        success: function (html) {
		            $('#showResultsVnReferidos').html(html);
		        }
		    });
		}

<?php } ?>
</script>