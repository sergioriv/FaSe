<?php
/* ERROR */
if($_SESSION['consultoriosExito']==1){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Error al guardar, inténtelo nuevamente.<br>Si el error persiste, ponte en contacto con el Administrador.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* GUARDAR */
if($_SESSION['consultoriosExito']==2){ ?>
	<input type="radio" id="alertExito">
	<label class="alerta exito" for="alertExito">
		<div>Se ha guardado con exito.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* GUARDAR CAMBIOS*/
if($_SESSION['consultoriosExito']==3){ ?>
	<input type="radio" id="alertExito">
	<label class="alerta exito" for="alertExito">
		<div>Sus cambios han sido guardados con exito.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR PACIENTE */
if($_SESSION['consultoriosExito']==4){ ?>
	<input type="radio" id="alertExito">
	<label class="alerta error" for="alertExito">
		<div><?php
			$pacienteEliminadoSql = $con->query("SELECT pc_nombres, IDPaciente FROM pacientes WHERE 
			IDPaciente = '$_SESSION[consultoriosPacienteEliminado]'");
			$pacienteEliminadoRow = $pacienteEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado al paciente <b><?php echo $pacienteEliminadoRow['pc_nombres'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosPacienteEliminado'] ?>&t=paciente'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR DOCTOR */
if($_SESSION['consultoriosExito']==5){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$doctorEliminadoSql = $con->query("SELECT dc_nombres, IDDoctor FROM doctores WHERE 
			IDDoctor = '$_SESSION[consultoriosDoctorEliminado]'");
			$doctorEliminadoRow = $doctorEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado al doctor <b><?php echo $doctorEliminadoRow['dc_nombres'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosDoctorEliminado'] ?>&t=doctor'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR MATERIAL */
if($_SESSION['consultoriosExito']==6){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$materialEliminadoSql = $con->query("SELECT IDMaterial, mt_codigo, mt_nombre FROM materiales WHERE 
			IDMaterial = '$_SESSION[consultoriosMaterialEliminado]'");
			$materialEliminadoRow = $materialEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el Item <b><?php echo $materialEliminadoRow['mt_nombre'] ?></b> con código <b><?php echo $materialEliminadoRow['mt_codigo'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosMaterialEliminado'] ?>&t=material'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR SUCURSAL */
if($_SESSION['consultoriosExito']==7){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$sucursalEliminadoSql = $con->query("SELECT IDSucursal, sc_nombre FROM sucursales WHERE IDSucursal = '$_SESSION[consultoriosSucursalEliminado]'");
			$sucursalEliminadoRow = $sucursalEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado la Sucursal <b><?php echo $sucursalEliminadoRow['sc_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosSucursalEliminado'] ?>&t=sucursal'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR PROVEEDOR */
if($_SESSION['consultoriosExito']==8){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$proveedorEliminadoSql = $con->query("SELECT IDProveedor, pr_nombre, pr_nit FROM proveedores WHERE IDProveedor = '$_SESSION[consultoriosProveedorEliminado]'");
			$proveedorEliminadoRow = $proveedorEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado al Proveedor <b><?php echo $proveedorEliminadoRow['pr_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosProveedorEliminado'] ?>&t=proveedor'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR EPS */
if($_SESSION['consultoriosExito']==9){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$epsEliminadoSql = $con->query("SELECT IDEps, eps_nombre FROM eps WHERE IDEps = '$_SESSION[consultoriosEpsEliminado]'");
			$epsEliminadoRow = $epsEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado la EPS <b><?php echo $epsEliminadoRow['eps_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosEpsEliminado'] ?>&t=eps'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR CIUDAD */
if($_SESSION['consultoriosExito']==10){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$ciudadEliminadoSql = $con->query("SELECT IDCiudad, cd_nombre FROM ciudades WHERE IDCiudad = '$_SESSION[consultoriosCiudadEliminado]'");
			$ciudadEliminadoRow = $ciudadEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado la Ciudad <b><?php echo $ciudadEliminadoRow['cd_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosCiudadEliminado'] ?>&t=ciudad'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR TRATAMIENTO */
if($_SESSION['consultoriosExito']==11){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$tratamientoEliminadoSql = $con->query("SELECT IDTratamiento, tr_nombre FROM tratamientos WHERE IDTratamiento = '$_SESSION[consultoriosTratamientoEliminado]'");
			$tratamientoEliminadoRow = $tratamientoEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el Tratamiento <b><?php echo $tratamientoEliminadoRow['tr_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosTratamientoEliminado'] ?>&t=tratamiento'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR TIPO DE TRATAMIENTO */
if($_SESSION['consultoriosExito']==12){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$tipoIdentiEliminadoSql = $con->query("SELECT IDTipoIdentificacion, ti_nombre FROM tiposidentificacion WHERE IDTipoIdentificacion = '$_SESSION[consultoriosTipoIdentiEliminado]'");
			$tipoIdentiEliminadoRow = $tipoIdentiEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el Tipo de Identificación <b><?php echo $tipoIdentiEliminadoRow['ti_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosTipoIdentiEliminado'] ?>&t=tipoIdenti'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR CITA */
if($_SESSION['consultoriosExito']==13){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$citaEliminadoSql = $con->query("SELECT citas.IDCita, citas.ct_idPaciente, pacientes.IDPaciente, pacientes.pc_nombres, 
		citas.ct_anoCita, citas.ct_mesCita, citas.ct_diaCita, citas.ct_horaCita FROM citas, pacientes WHERE citas.ct_idPaciente = pacientes.IDPaciente AND citas.IDcita = '$_SESSION[consultoriosCitaEliminado]'");
			$citaEliminadoRow = $citaEliminadoSql->fetch_assoc();
		?>
			Se ha Cancelado la cita del paciente <b><?php echo $citaEliminadoRow['pc_nombres'].' | '.$citaEliminadoRow['ct_anoCita'].'/'.$citaEliminadoRow['ct_mesCita'].'/'.$citaEliminadoRow['ct_diaCita'].' '.$citaEliminadoRow['ct_horaCita'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosCitaEliminado'] ?>&t=cita'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR USUARIO DE INVENTARIO */
if($_SESSION['consultoriosExito']==14){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$usrInventarioEliminadoSql = $con->query("SELECT IDUserInventario, ui_nombres FROM usuariosinventario WHERE IDUserInventario = '$_SESSION[consultoriosUserInventario]'");
			$usrInventarioEliminadoRow = $usrInventarioEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el Usuario de Inventario <b><?php echo $usrInventarioEliminadoRow['ui_nombres'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosUserInventario'] ?>&t=usInventario'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR USUARIO DE CITAS */
if($_SESSION['consultoriosExito']==17){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$usrCitasEliminadoSql = $con->query("SELECT IDUserCitas, uc_nombres FROM usuarioscitas WHERE IDUserCitas = '$_SESSION[consultoriosUserCitas]'");
			$usrCitasEliminadoRow = $usrCitasEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el Usuario de Citas <b><?php echo $usrCitasEliminadoRow['uc_nombres'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosUserCitas'] ?>&t=usCitas'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR ESPECIALIDAD */
if($_SESSION['consultoriosExito']==18){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$especialidadEliminadoSql = $con->query("SELECT IDEspecialidad, esp_nombre FROM especialidades WHERE IDEspecialidad = '$_SESSION[consultoriosEspecialidadEliminado]'");
			$especialidadEliminadoRow = $especialidadEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado la Especialidad <b><?php echo $especialidadEliminadoRow['esp_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosEspecialidadEliminado'] ?>&t=especialidad'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR COMBO */
if($_SESSION['consultoriosExito']==19){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$comboEliminadoSql = $con->query("SELECT * FROM tratamientos WHERE IDTratamiento = '$_SESSION[consultoriosComboEliminado]'");
			$comboEliminadoRow = $comboEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el combo <b><?php echo $comboEliminadoRow['tr_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosComboEliminado'] ?>&t=combo'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR CONVENIO */
if($_SESSION['consultoriosExito']==20){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$convenioEliminadoSql = $con->query("SELECT * FROM convenios WHERE IDConvenio = '$_SESSION[consultoriosConvenioEliminado]'");
			$convenioEliminadoRow = $convenioEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el convenio <b><?php echo $convenioEliminadoRow['cnv_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosConvenioEliminado'] ?>&t=convenio'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR VENDEDOR */
if($_SESSION['consultoriosExito']==21){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div><?php
			$vendedorEliminadoSql = $con->query("SELECT * FROM vendedores WHERE IDVendedor = '$_SESSION[consultoriosVendedorEliminado]'");
			$vendedorEliminadoRow = $vendedorEliminadoSql->fetch_assoc();
		?>
			Se ha eliminado el vendedor <b><?php echo $vendedorEliminadoRow['vn_nombre'] ?></b>.<a class="deshacer" onclick="location.href='activar-guardar.php?id=<?php echo $_SESSION['consultoriosVendedorEliminado'] ?>&t=vendedor'">Deshacer</a>
		</div>
		<div class="close">&times;</div>
	</label>
<?php }



/* ELIMINAR ABONO */
if($_SESSION['consultoriosExito']==15){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Abono anulado.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* ELIMINAR EGRESO */
if($_SESSION['consultoriosExito']==22){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Egreso anulado.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* SUGERENCIA ENVIADA */
if($_SESSION['consultoriosExito']==16){ ?>
	<input type="radio" id="alertExito">
	<label class="alerta exito" for="alertExito">
		<div>Sugerencia enviada.</div>
		<div class="close">&times;</div>
	</label>
<?php }

/* FALLO DE ENVIO DE EMAIL */
if($_SESSION['consultoriosExito']==100){ ?>
	<input type="radio" id="alertError">
	<label class="alerta error" for="alertError">
		<div>Ocurrio un error con el sistema de envio de correos. <br>La contraseña del usuario es: <b><?php echo $_SESSION['consultoriosMSJPASS']; ?></b></div>
		<div class="close">&times;</div>
	</label>
<?php }

$_SESSION['consultoriosExito']=0;
$_SESSION['consultoriosPacienteEliminado']=0;
$_SESSION['consultoriosDoctorEliminado']=0;
$_SESSION['consultoriosMaterialEliminado']=0;
$_SESSION['consultoriosSucursalEliminado']=0;
$_SESSION['consultoriosProveedorEliminado']=0;
$_SESSION['consultoriosEpsEliminado']=0;
$_SESSION['consultoriosCiudadEliminado']=0;
$_SESSION['consultoriosTratamientoEliminado']=0;
$_SESSION['consultoriosTipoIdentiEliminado']=0;
$_SESSION['consultoriosCitaEliminado']=0;
$_SESSION['consultoriosUserInventario']=0;

$_SESSION['consultorioTmpConvenioID'] = 0;
$_SESSION['consultorioTmpPresupuestoID'] = 0;
$_SESSION['consultorioTmpPlanID'] = 0;

$_SESSION['FaSe_editID'] = 0;

?>