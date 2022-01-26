<?php include'../config.php'; $pacienteID = $_POST['pacienteID']; ?>

							<option selected hidden value="-1">-- Seleccionar --</option>
							<optgroup label="Tratamientos activos">
							<?php
								$tratamientosActivosSql = $con->query("SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' AND citas.ct_inicial = '1' AND citas.ct_terminado = '1' AND tratamientos.tr_estado='1' ORDER BY citas.ct_fechaOrden DESC");
								$tratamientosActivosNum = $tratamientosActivosSql->num_rows;
								if($tratamientosActivosNum>0){
									while($tratamientosActivosRow = $tratamientosActivosSql->fetch_assoc()){
										echo "<option value=".$tratamientosActivosRow['IDTratamiento'].">".$tratamientosActivosRow['tr_nombre']."</option>";		
									}
								} else {
									echo "<option disabled >Ninguno</option>";
								}
							?>
							</optgroup>


							<optgroup label="Tratamientos de presupuestos">
							<?php
								$tratamientosConvenio = $con->query("SELECT IDPresupuestoTrata, pp_idConvenio, cnv_nombre, cnv_descuento, tr_nombre, ppt_idTratamiento, ppt_precio FROM presupuestos AS pp
								INNER JOIN convenios AS cnv ON pp.pp_idConvenio = cnv.IDConvenio
								INNER JOIN presupuestotratamientos AS ppt ON ppt.ppt_idPresupuesto = pp.IDPresupuesto
								INNER JOIN tratamientos AS tr ON ppt.ppt_idTratamiento = tr.IDTratamiento
								WHERE pp_idPaciente = '$pacienteID'
									AND pp_estado=1 
									AND pp_aprobado=1 
									AND tr_estado=1
									AND ppt_activo=1 
									ORDER BY cnv_nombre ASC, tr_nombre ASC
								");
								$tratamientosConvenioNum = $tratamientosConvenio->num_rows;
								$countTratamientosConvenio = 0;
								if($tratamientosConvenioNum>0){

					            	while($rowTratamientosConvenio = $tratamientosConvenio->fetch_assoc()){

					            		$precioTratamientoConvenio = $rowTratamientosConvenio['ppt_precio'] - ( ( $rowTratamientosConvenio['ppt_precio'] * $rowTratamientosConvenio['cnv_descuento'] ) /100 );

					            		$precioTratamientoConvenioView = '$'.number_format($precioTratamientoConvenio, 2, ",", ".");

					            		if($rowTratamientosConvenio['pp_idConvenio'] > 1000){
					            			$tratamientoConvenioView = $rowTratamientosConvenio['cnv_nombre'].' - '.$rowTratamientosConvenio['tr_nombre'].' - '.$precioTratamientoConvenioView;
					            		} else {
					            			$tratamientoConvenioView = $rowTratamientosConvenio['tr_nombre'].' - '.$precioTratamientoConvenioView;
					            		}

					            		

					            		$TratamientoConveniosActivo = $con->query("SELECT COUNT(*) AS activos FROM citas WHERE ct_idPaciente='$pacienteID' 
					            				AND ct_idTratamiento='$rowTratamientosConvenio[ppt_idTratamiento]' 
					            				
					            				AND ct_inicial=1 
					            				AND ct_terminado=1")->fetch_assoc();

										if($TratamientoConveniosActivo['activos']==0){
											echo "<option value=".$rowTratamientosConvenio['ppt_idTratamiento']." data-valor=".$precioTratamientoConvenio." data-presupuesto='1' data-tratamiento=".$rowTratamientosConvenio['IDPresupuestoTrata'].">".$tratamientoConvenioView."</option>";
											$countTratamientosConvenio++;
										}
									}

									if($countTratamientosConvenio==0){
										echo "<option disabled >Activos - en proceso</option>";
									}
					            } else {
									echo "<option disabled >Ninguno</option>";
								}

							?>
							</optgroup>


							<optgroup label="Combos">		
							<?php
								$combosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica = '$sessionClinica' AND tr_estado='1' AND tr_combo='1' ORDER BY tr_nombre");
				            	while($combosRow = $combosSql->fetch_assoc()){

				            			echo "<option value=".$combosRow['IDTratamiento']." data-combo='1'>".$combosRow['tr_nombre']."</option>";	
				            						            		
								}
				            ?>
				            </optgroup>


				            <optgroup label="Tratamientos libres">		
							<?php
								$tratamientosSql = $con->query("SELECT * FROM tratamientos WHERE tr_idClinica = '$sessionClinica' AND tr_estado='1' AND tr_combo='0' ORDER BY tr_nombre");
				            	while($tratamientosRow = $tratamientosSql->fetch_assoc()){

				            		$tratamientosActivos = $con->query("SELECT * FROM citas, tratamientos WHERE citas.ct_idTratamiento = tratamientos.IDTratamiento AND citas.ct_idPaciente = '$pacienteID' AND citas.ct_inicial = '1' AND citas.ct_terminado = '1' AND tratamientos.IDTratamiento = '$tratamientosRow[IDTratamiento]'");
				            		if($tratamientosActivos->num_rows == 0) {
				            			echo "<option value=".$tratamientosRow['IDTratamiento']." data-valor=".$tratamientosRow['tr_precio'].">".$tratamientosRow['tr_nombre'].' - '.'$'.number_format($tratamientosRow['tr_precio'], 2, ",", ".")."</option>";	
				            		}				            		
								}
				            ?>
				            </optgroup>