<?php include'config.php'; include'pagination-modal-params.php'; include'encrypt.php';

$abonoID = $_POST['id'];
$pacienteID = $_POST['pc'];
$tipo = $_POST['tp'];

$query = $con->query("UPDATE abonos SET ab_estado='0' WHERE IDAbono = '$abonoID'");
	if($query){
?>
			<div class="contenedorAlerta">
				<input type="radio" id="alertError">
				<label class="alerta error s" for="alertError">
					<div>Abono anulado.</div>
					<div class="close">&times;</div>
				</label>
			</div>
<?php } else {
?>
		<div class="contenedorAlerta">
			<input type="radio" id="alertError">
			<label class="alerta error s" for="alertError">
				<div>Error al anular, Intentelo nuevamente.</div>
				<div class="close">&times;</div>
			</label>
		</div>
<?php }

		if($tipo=='pc'){
							$deuda = 0;
							$deudaSql = $con->query("SELECT SUM(ct_costo) AS vt FROM citas WHERE ct_idPaciente = '$pacienteID' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();
							$abonosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idPaciente = '$pacienteID' AND ab_idSucursal>0 AND ab_estado='1'")->fetch_assoc();
							$deuda = $deudaSql['vt'] - $abonosSql['ab'];

							$abonosPacienteNum = $con->query("SELECT * as numAbonos FROM abonos WHERE ab_idPaciente='$pacienteID' AND ab_idSucursal>0")->num_rows;
?>
			
			<div class="titulo tituloSecundario tituloCenter">Estado de cuenta:<span id="estadoCuenta"><?php echo '$'.number_format($deuda, 0, ".", ","); ?></span></div>
						    <div class="containerPart">
						    	<?php if($deuda>0){ ?>	
						    		<div class="titulo tituloSecundario"><a class="consultorioAbono"><?php echo $iconoNuevo ?>Nuevo Abono</a></div>
							    <?php } else { echo "<span>&nbsp</span>";} if($abonosPacienteNum > 0){ ?>
								    <div class="titulo tituloSecundario">
								    	<a href="paciente-abonos-pdf.php?q=<?= encrypt( 'id='.$pacienteID ) ?>"><i class="fa fa-download"></i>Historial abonos</a>
								    </div>
								<?php } ?>
						    </div>
						    <?php $abonosPacienteQuery = "SELECT * FROM abonos, usuarios, sucursales WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idPaciente='$pacienteID' ORDER BY abonos.IDAbono DESC";

								$rowCountPcAbonos = $con->query($abonosPacienteQuery)->num_rows;

								//Initialize Pagination class and create object
								    $pagConfig = array(
										'totalRows' => $rowCountPcAbonos,
									    'perPage' => $numeroResultados,
										'link_func' => 'paginationPcAbonos'
									);
								    $pagination =  new Pagination($pagConfig);

								$abonosPacienteSql = $con->query($abonosPacienteQuery." LIMIT $numeroResultados");

						    ?>
						    <div id="showResultsPcAbonos">
					    		<table class="tableList">
					    			<thead>
					    				<tr>
					    					<th class="estado">&nbsp</th>
					    					<th>#</th>
					    					<th class="columnaCorta">Fecha</th>
					    					<th>Usuario</th>
					    					<th>Sucursal</th>
					    					<th>Forma Pago</th>
					    					<th align="right">Valor</th>
					    					<th>&nbsp</th>
					    				</tr>
					    			</thead>
					    			<tbody>
					    				<?php
					    					while($abonosPacienteRow = $abonosPacienteSql->fetch_assoc()){
					    						$nombreUsuarioAbono = '';
					    						$IDusuarioAbono = $abonosPacienteRow['us_id'];
					    						if($abonosPacienteRow['us_idRol']==1){
					   								$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
					    						} elseif($abonosPacienteRow['us_idRol']==2){
					   								$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
					    						} elseif($abonosPacienteRow['us_idRol']==3){
					   								$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
					   								$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
					    						}

					    						if($abonosPacienteRow['ab_estado']==1){
					    							$estadoAbono = 'estadoNeutro';
					    						} else {
					    							$estadoAbono = 'estadoCancelado';
					    						}

					    						if($abonosPacienteRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
												} else { $iUS = ''; $cUS = ''; }

												if($abonosPacienteRow['sc_estado']==0){ $iSC = $iconW; $cSC = 'elementoEliminado';
												} else { $iSC = ''; $cSC = ''; }

												$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosPacienteRow[ab_idFormaPago]'")->fetch_assoc();

												if($abonosPacienteRow['ab_idFormaPago']==2){
													$bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosPacienteRow[ab_idBanco]'")->fetch_assoc();
													$abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosPacienteRow['ab_cheque'];
												} else {
													$abonoFormaPago = $formaPago['fp_nombre'];
												}
					    				?>
					    				<tr>
					    					<td class="estado <?php echo $estadoAbono ?>"></td>
					    					<td align="right"><?php echo $abonosPacienteRow['ab_consecutivo'] ?></td>
					    					<td align="center"><?php echo $abonosPacienteRow['ab_fechaCreacion'] ?></td>
					    					<td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
					    					<td class="<?php echo $cSC ?>"><?php echo $iSC.$abonosPacienteRow['sc_nombre'] ?></td>
					    					<td align="center"><?php echo $abonoFormaPago ?></td>
					    					<td align="right"><?php echo '$'.number_format($abonosPacienteRow['ab_abono'], 0, ".", ","); ?></td>
					    					<td class="tableOption">
					    						<a href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosPacienteRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
					    						<?php if($abonosPacienteRow['ab_estado']==1){ ?>
					    							<a class="consultorioAbonoEditar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>"><?php echo $iconoEditar ?></a>
										    		<a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosPacienteRow['IDAbono'] ?>" pc="<?php echo $pacienteID ?>"><?php echo $iconoEliminar ?></a>
										    	<?php } ?>
										    </td>
					    				</tr>
					    				<?php } ?>
					    			</tbody>
					    		</table>
					    		<?php echo $pagination->createLinks(); ?>
					    	</div>
<?php
		} elseif($tipo=='sc'){ $sucursalID = $_POST['sucursalID'];

					$abonosSucuralQuery = "SELECT * FROM abonos, usuarios, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idSucursal = '$sucursalID' ORDER BY abonos.IDAbono DESC";

					$rowCountScAbonos = $con->query($abonosSucuralQuery)->num_rows;
					$pagConfig = array(
						'totalRows' => $rowCountScAbonos,
					    'perPage' => $numeroResultados,
						'link_func' => 'paginationScAbonos'
					);
				    $pagination =  new Pagination($pagConfig);
				    $abonosSucuralSql = $con->query($abonosSucuralQuery." LIMIT $numeroResultados");
?>

					<div class="containerPart">
						<span></span>
						<div class="titulo tituloSecundario">
							<?php if($rowCountScAbonos > 0){ ?>
						   		<a href="sucursal-abonos-pdf.php?q=<?= encrypt( 'id='.$sucursalID ) ?>"><i class="fa fa-download"></i>Historial abonos</a>
						   	<?php } ?>
						</div>
					</div>
					<div id="showResultsScAbonos">
						<table class="tableList">
							<thead>
								<tr>
						    		<th class="estado">&nbsp</th>
									<th>#</th>
						    		<th class="columnaCorta">Fecha</th>
						    		<th>Usuario</th>
						    		<th>Paciente</th>
						    		<th>Forma Pago</th>
						    		<th align="right">Valor Abono</th>
						    		<th>&nbsp</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									while($abonosSucuralRow = $abonosSucuralSql->fetch_assoc()){

										$nombreUsuarioAbono = '';
						    			$IDusuarioAbono = $abonosSucuralRow['us_id'];
						    			if($abonosSucuralRow['us_idRol']==1){
						   					$usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
						    			} elseif($abonosSucuralRow['us_idRol']==2){
						   					$usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
						    			} elseif($abonosSucuralRow['us_idRol']==3){
						   					$usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
						   					$nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
						    			}

						    			if($abonosSucuralRow['ab_estado']==1){
						    				$estadoAbono = 'estadoNeutro';
						    			} else {
						    				$estadoAbono = 'estadoCancelado';
						    			}

						    			if($abonosSucuralRow['us_estado']==0){ $iUS = $iconW; $cUS = 'elementoEliminado';
										} else { $iUS = ''; $cUS = ''; }

						    			if($abonosSucuralRow['pc_estado']==0){ $iPC = $iconW; $cPC = 'elementoEliminado';
										} else { $iPC = ''; $cPC = ''; }

										$formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosSucuralRow[ab_idFormaPago]'")->fetch_assoc();

                                        if($abonosSucuralRow['ab_idFormaPago']==2){
                                            $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosSucuralRow[ab_idBanco]'")->fetch_assoc();
                                            $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosSucuralRow['ab_cheque'];
                                        } else {
                                            $abonoFormaPago = $formaPago['fp_nombre'];
                                        }
								?>
								<tr>
						    		<td class="estado <?php echo $estadoAbono ?>"></td>
									<td align="right"><?php echo $abonosSucuralRow['ab_consecutivo'] ?></td>
						    		<td align="center"><?php echo $abonosSucuralRow['ab_fechaCreacion'] ?></td>
						    		<td class="<?php echo $cUS ?>"><?php echo $iUS.$nombreUsuarioAbono ?></td>
						    		<td class="<?php echo $cPC ?>"><?php echo $iPC.$abonosSucuralRow['pc_nombres'] ?></td>
                                    <td align="center"><?php echo $abonoFormaPago ?></td>
						    		<td align="right" class="columnaCorta"><?php echo '$'.number_format($abonosSucuralRow['ab_abono'], 0, ".", ","); ?></td>
						    		<td class="tableOption">
						    			<a href="paciente-abono-pdf.php?q=<?= encrypt( 'id='.$abonosSucuralRow['IDAbono'] ) ?>"><i class="fa fa-download"></i></a>
									   	<?php if($abonosSucuralRow['ab_estado']==1){ ?>
						    				<a class="consultorioAbonoEditar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEditar ?></a>
											<a title="Anular" class="anularAbono eliminar" id="<?php echo $abonosSucuralRow['IDAbono'] ?>" pc="<?php echo $abonosSucuralRow['IDPaciente'] ?>"><?php echo $iconoEliminar ?></a>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php echo $pagination->createLinks(); ?>
					</div>


<?php
		} elseif($tipo=='ab'){

			if($query){ $_SESSION['consultoriosExito']=15; }
			else { $_SESSION['consultoriosExito']=1; }
			header("Location:$_SESSION[concultoriosAntes]");

		}

?>
		<script type="text/javascript">

			$(document).ready(function() {
			    setTimeout(function() {
			        $(".s").fadeOut(500);
			    },3000);
			});
			
		</script>