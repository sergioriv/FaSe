<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php include'header.php';
			include'footer.php'; ?>
</head>
<body>
	<div class="contenedorPrincipal">

		<div id="msj-factura" class="contenedorAlerta"><?php include'mensajes.php'; ?></div>

		<div class="contenedorTabs">
			<input id="tab-1" type="radio" name="tab-group" checked />
			<label for="tab-1" class="labelTab">Recibos de caja</label>
			<input id="tab-2" type="radio" name="tab-group" />
			<label for="tab-2" class="labelTab">Comprobantes de Egreso</label>

			<div class="contenidoTab">
				<div id="content-1">
					<?php $abonosQuery = "SELECT * FROM abonos, usuarios, sucursales, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' ORDER BY abonos.IDAbono DESC";
					//sucursales.sc_nombre ASC,
					$rowCount = $con->query($abonosQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCount,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationRecibosCaja'
						);
					    $pagination =  new Pagination($pagConfig);

					$abonosSql = $con->query($abonosQuery." LIMIT $numeroResultados");
					?>

					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a href="excel-recibos-caja.php"><i class="fa fa-download"></i>Descargar reporte</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchRecibosCaja" list="abonos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationRecibosCaja();">
						</span>
					</div>

					<div id="showResultsRecibosCaja">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado">&nbsp</th>
									<th>#</th>
									<th class="columnaCorta">Fecha</th>
									<th>Usuario</th>
									<th>Sucursal</th>
									<th>Paciente</th>
									<th>Forma Pago</th>
									<th align="right">Valor</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($abonosRow = $abonosSql->fetch_assoc()){

										$nombreUsuarioAbono = '';
								    	$IDusuarioAbono = $abonosRow['us_id'];
								    	if($abonosRow['us_idRol']==1){
								   			$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")
								   			->fetch_assoc();
								   			$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
								    	} elseif($abonosRow['us_idRol']==2){
								   			$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")
								   			->fetch_assoc();
								   			$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
								    	} elseif($abonosRow['us_idRol']==3){
								   			$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")
								   			->fetch_assoc();
								   			$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
								    	}

								    	if($abonosRow['ab_estado']==1){
								    		$estadoAbono = 'estadoNeutro';
								    	} else {
								    		$estadoAbono = 'estadoCancelado';
								    	}

								    	if($abonosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
										} else { $iUS = ''; $cUS = ''; }
										if($abonosRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
										} else { $iSC = ''; $cSC = ''; }
										if($abonosRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
										} else { $iPC = ''; $cPC = ''; }

										$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosRow[ab_idFormaPago]'")->fetch_assoc();

										if($abonosRow['ab_idFormaPago']==2){
			                                $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosRow[ab_idBanco]'")->fetch_assoc();
			                                $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosRow['ab_cheque'];
			                            } else {
			                                $abonoFormaPago = $formaPago['fp_nombre'];
			                            }
								?>
								<tr>
								    <td class="estado <?php echo $estadoAbono ?>"></td>
								    <td align="right"><?php echo $abonosRow['ab_consecutivo'] ?></td>
								    <td align="center"><?php echo $abonosRow['ab_fechaCreacion'] ?></td>
								    <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
								    <td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosRow['sc_nombre'] ?></td>
								    <td class="<?php echo $cPC  ?>"><?php echo $iPC.$abonosRow['pc_nombres'] ?></td>
								    <td align="center"><?php echo $abonoFormaPago ?></td>
								    <td align="right"><?php echo '$'.number_format($abonosRow['ab_abono'], 0, ".", ","); ?></td>
								    <td class="tableOption">
								    	<a title="Descargar" href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
			                            <?php if($abonosRow['ab_estado']==1){ ?>
			                                <a title="Editar" class="consultorioAbonoEditar" id="<?php echo $abonosRow['IDAbono'] ?>"><?php echo $iconoEditar ?></a>
			                                <a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosRow['IDAbono'] ?>"><?php echo $iconoEliminar ?></a>
			                            <?php } ?>
									   </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
					
				</div>


				<div id="content-2">
					<?php $egresosQuery = "SELECT * FROM ordenesabonos AS pra
								INNER JOIN usuarios AS us ON pra.pra_idUsuario = us.IDUsuario
								INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada
								INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
								WHERE pra_idClinica = '$sessionClinica'
								ORDER BY IDOrdenAbono DESC";
					//sucursales.sc_nombre ASC,
					$rowCount = $con->query($egresosQuery)->num_rows;
						$pagConfig = array(
							'totalRows' => $rowCount,
						    'perPage' => $numeroResultados,
							'link_func' => 'paginationEgresos'
						);
					    $pagination =  new Pagination($pagConfig);

					$egresosSql = $con->query($egresosQuery." LIMIT $numeroResultados");
					?>

					<div class="tituloBuscador">
						<div class="titulo tituloSecundario"><a href="excel-comprobantes-egreso.php"><i class="fa fa-download"></i>Descargar reporte</a></div>
						<span>
							<i class="fa fa-search" aria-hidden="true"></i>
							<input type="text" id="searchComprobantesEgreso" list="abonos" class="buscador" placeholder="Buscar . . ." onkeyup="paginationEgresos();">
						</span>
					</div>

					<div id="showResultsComprobantesEgreso">
						<table class="tableList">
							<thead>
								<tr>
									<th class="estado">&nbsp</th>
									<th>#</th>
									<th class="columnaCorta">Fecha</th>
									<th>Usuario</th>
									<th>Proveedor</th>
									<th># Factura</th>
									<th>Forma Pago</th>
									<th align="right">Valor</th>
									<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php while($egresosRow = $egresosSql->fetch_assoc()){

										$nombreUsuarioEgreso = '';
								    	$IDusuarioEgreso = $egresosRow['us_id'];
								    	if($egresosRow['us_idRol']==1){
								   			$usuarioEgreso = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioEgreso'")
								   			->fetch_assoc();
								   			$nombreUsuarioEgreso = $usuarioEgreso['cl_nombre'];
								    	} elseif($egresosRow['us_idRol']==2){
								   			$usuarioEgreso = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioEgreso'")
								   			->fetch_assoc();
								   			$nombreUsuarioEgreso = $usuarioEgreso['sc_nombre'];
								    	} elseif($egresosRow['us_idRol']==3){
								   			$usuarioEgreso = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioEgreso'")
								   			->fetch_assoc();
								   			$nombreUsuarioEgreso = $usuarioEgreso['dc_nombres'];
								    	}

								    	if($egresosRow['pra_estado']==1){
								    		$estadoEgreso = 'estadoNeutro';
								    	} else {
								    		$estadoEgreso = 'estadoCancelado';
								    	}

								    	if($egresosRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
										} else { $iUS = ''; $cUS = ''; }

										$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$egresosRow[pra_idFormaPago]'")->fetch_assoc();

									        	if($egresosRow['pra_idFormaPago']==2){
													$bancoEgreso = $con->query("SELECT * FROM bancos WHERE IDBanco = '$egresosRow[pra_idBanco]'")->fetch_assoc();
													$egresoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoEgreso['bnc_codigo'].' | '.$egresosRow['pra_cheque'];
												} else {
													$egresoFormaPago = $formaPago['fp_nombre'];
												}
								?>
								<tr>
								    <td class="estado <?php echo $estadoEgreso ?>"></td>
								    <td align="right"><?php echo $egresosRow['pra_consecutivo'] ?></td>
								    <td align="center"><?php echo $egresosRow['pra_fechaCreacion'] ?></td>
								    <td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioEgreso ?></td>
								    <td><?php echo $egresosRow['pr_nombre'] ?></td>
								    <td align="center"><?php echo $egresosRow['ore_numeroFactura'] ?></td>
								    <td align="center"><?= $egresoFormaPago ?></td>
								    <td align="right"><?php echo '$'.number_format($egresosRow['pra_abono'], 0, ".", ","); ?></td>
								    <td class="tableOption">
								    	<a title="Descargar" href="orden-abono-pdf.php?q=<?= encrypt( 'id='.$egresosRow['IDOrdenAbono'] ) ?>"><i class="fa fa-download"></i></a>
			                            <?php if($egresosRow['pra_estado']==1){ ?>
			                                <a title="Editar" class="consultorioEgresoEditar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEditar ?></a>
			                                <a title="Anular" class="anularEgreso eliminar" id="<?php echo $egresosRow['IDOrdenAbono'] ?>"><?php echo $iconoEliminar ?></a>
			                            <?php } ?>
									   </td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>
					
				</div>

			</div>
		</div>
	</div>

	<div id="consultoriosModal" class="modal fade">  
      <div class="modal-dialog">  
        <div class="modal-content" id="consultoriosDetails"></div>  
      </div>  
 	</div>

	<script type="text/javascript">
		function paginationRecibosCaja(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchRecibosCaja').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/recibosCajaData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsRecibosCaja').html(html);
		        }
		    });
		}

		function paginationEgresos(page_num) {
		    page_num = page_num?page_num:0;
		    var busqueda = $('#searchComprobantesEgreso').val();
		    $.ajax({
		        type: 'POST',
		        url: 'get/comprobantesEgresoData.php',
		        data:'page='+page_num+'&busqueda='+busqueda,
		        success: function (html) {
		            $('#showResultsComprobantesEgreso').html(html);
		        }
		    });
		}

		$(document).on('click', '.consultorioAbonoEditar', function(){
			var consultoriosId = $(this).attr("id");
			if(consultoriosId != '')
			{
			   	$.ajax({
			       	url:"abono.php",  
			        method:"POST", 
			        data:{abonoID:consultoriosId,tp:'ab'},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
			    });
			}
		});
		$(document).on('click', '.anularAbono', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"paciente-abono-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId,tp:'ab'},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});

		$(document).on('click', '.consultorioEgresoEditar', function(){
			var consultoriosId = $(this).attr("id");
			if(consultoriosId != '')
			{
			   	$.ajax({
			       	url:"orden-abono-factura.php",  
			        method:"POST", 
			        data:{egresoID:consultoriosId},
			        success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
			    });
			}
		});
		$(document).on('click', '.anularEgreso', function(){  
			var consultoriosId = $(this).attr("id");
		    if(consultoriosId != '')
		    {  
		    	$.ajax({
		        	url:"orden-abono-eliminar.php",  
		            method:"POST",  
		            data:{id:consultoriosId},
		            success:function(data){  
						$('#consultoriosDetails').html(data);  
						$('#consultoriosModal').modal('show'); 
					}
		    	});  
			}            
		});
	</script>
</body>
</html>