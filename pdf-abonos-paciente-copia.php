<?php include'config.php';

$pacienteID = $_GET['id'];
$paciente = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();

$ciudadPC = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$paciente[pc_idCiudad]'")->fetch_assoc();

$deuda = 0; $sumatoria = 0;
$deudaSql = $con->query("SELECT SUM(ct_costo) AS vt FROM citas WHERE ct_idPaciente = '$pacienteID' AND ct_inicial='1'")
    ->fetch_assoc();
$abonosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idPaciente = '$pacienteID' AND ab_estado='1'")
    ->fetch_assoc();
$deuda = $deudaSql['vt'] - $abonosSql['ab'];
$sumatoria = $abonosSql['ab'];
?>
<style type="text/css">
	table.page_header { /*  ANCHO TOTAL 216mm */
        width: 216mm;
        margin: -5mm;
	   	border-bottom: solid 2px <?php echo $colorPrincipal ?>;
	   	text-align: center;
        vertical-align: bottom;
        padding-bottom: 2mm;
	}
	table.page_footer {
        width: 216mm;
        margin-left: -5mm;
		border-top: solid 2px <?php echo $colorPrincipal ?>;
		text-align: right;
	}

	.logo { width: 64mm; height: 30mm; vertical-align: middle; padding: 0mm 2mm; }
	.logo img { max-width: 100%; max-height: 25mm; margin-top: 2mm; }
	.empresa { width: 135mm; font-size: 16px; padding: 0mm 2mm; }
    .infoEmpresa { width: 135mm;; font-size: 11px; padding: 0mm 2mm; }
	.fecha { font-size: 10px; padding: 0mm 2mm; }
	.titulo { font-size: 14px; padding: 0mm 2mm; }
	.tableContenido {
        width: 206mm;
        max-width: 206mm;
        margin-bottom: 10mm;
    }

    .colTit {
        width: 205mm;
        padding: 2mm 0mm;
        border-bottom: 1px solid gray;
        text-align: left;
    }   
    .top { padding-top: 2mm; }
    .colSub {
        width: 8mm;
        text-align: left;
        padding: 1mm;
    }
    .colLar {
        width: 100mm;
        vertical-align: middle;
    }
    .colCor {
        width: 50mm;
        vertical-align: middle;
    }
    .colUsr {
        width: 30mm;
    }

    .pag{ width: 100%; padding-top: 2mm; }
    .tableContenido th { font-size: 11px; vertical-align: top; }
    .tableContenido td { font-size: 9px; text-transform: uppercase; }
    .arriba { vertical-align: top; }
    .ab th { padding-bottom: 2mm; }
    .ab td { padding: 1mm; }
    .center { text-align: center; }
    .right { text-align: right; }
</style>
<page backtop="35mm" backbottom="10mm" backleft="0mm" backright="0mm">
    <page_header>
        <table class="page_header">
            <tr><!-- rowspan="2" -->
                <th rowspan="3" class="logo"><img src="<?php echo $clinicaRow['cl_logo'] ?>"></th>
			    <th colspan="2" class="empresa"><?php echo $clinicaRow['cl_nombre']; ?></th>
            </tr>
            <tr>
                <td colspan="2" class="infoEmpresa"><?php echo 'NIT '.$clinicaRow['cl_nit'].'<br>'.$clinicaRow['cl_direccion']; ?></td>
            </tr>
            <tr>
            	<th class="titulo">Recibos de Caja</th>
            	<td class="fecha"><?php echo $hoyDia.'/'.$hoyMes.'/'.$hoyAno ?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td class="pag">pág. [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    </page_footer>

    <table class="tableContenido">
    	<tr>
    		<th class="colTit" colspan="4">Paciente</th>
    	</tr>
    	<tr>
    		<th class="colSub top">Nombres:</th>
    		<td class="top colLar" colspan="3"><?php echo $paciente['pc_nombres'] ?></td>
    	</tr>
    	<tr>
    		<th class="colSub">Documento:</th>
    		<td class="colCor"><?php echo $paciente['pc_identificacion'] ?></td>
            <th class="colSub">Ciudad:</th>
            <td class="colCor"><?php echo $ciudadPC['cd_nombre'] ?></td>
    	</tr>
    	<tr>
    		
    		<th class="colSub">Dirección:</th>
    		<td class="colLar" colspan="3"><?php echo $paciente['pc_direccion'] ?></td>
    	</tr>
    	<tr>
    		<th class="colSub">Celular:</th>
    		<td class="colCor"><?php echo $paciente['pc_telefonoCelular'] ?></td>
    		<th class="colSub">Teléfono:</th>
    		<td class="colCor"><?php echo $paciente['pc_telefonoFijo'] ?></td>
    	</tr>
    	<tr>
    		<th class="colSub">Correo<br>Electrónico:</th>
    		<td class="colLar" colspan="3"><?php echo $paciente['pc_correo'] ?></td>
    	</tr>
    </table>
    <table class="tableContenido ab">
    	<tr>
    		<th class="colTit" colspan="5">Abonos Realizados</th>
    	</tr>
    	<tr>
            <td class="top center">#</td>
    		<th class="top center">Fecha</th>
    		<th class="top center colUsr">Usuario</th>
            <th class="top center colUsr">Sucursal</th>
    		<th class="top center">Valor</th>
    	</tr>
   		<?php $abonosPaciente = $con->query("SELECT * FROM abonos, usuarios, sucursales WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idPaciente='$pacienteID' ORDER BY abonos.IDAbono ASC");
    		while($abonosPacienteRow = $abonosPaciente->fetch_assoc()){
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

                $estadoAbono = '';
                if($abonosPacienteRow['ab_estado']==0) { $estadoAbono = '(anulado) '; }
    	?>
    	<tr>
            <td class="arriba right"><?php echo $abonosPacienteRow['ab_consecutivo'] ?></td>
    		<td class="arriba"><?php echo $abonosPacienteRow['ab_fechaCreacion']; ?></td>
    		<td class="arriba colUsr"><?php echo $nombreUsuarioAbono; ?></td>
            <td class="arriba colUsr"><?php echo $abonosPacienteRow['sc_nombre'] ?></td>
    		<td class="arriba right"><?php echo $estadoAbono.'$'.number_format($abonosPacienteRow['ab_abono'], 2, ".", ","); ?></td>
    	</tr>
    	<?php } ?>
        <tr>
            <th class="right" colspan="4">Total:</th>
            <th class="right"><?php echo '$'.number_format($sumatoria, 2, ".", ","); ?></th>
        </tr>
    	<tr>
    		<th class="right" colspan="4">Estado de cuenta:</th>
    		<th class="right"><?php echo '$'.number_format($deuda, 2, ".", ","); ?></th>
    	</tr>
    </table>
</page>