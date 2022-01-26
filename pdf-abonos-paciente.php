<?php //include'config.php';

$pacienteRow = $con->query("SELECT * FROM pacientes WHERE IDPaciente = '$pacienteID'")->fetch_assoc();

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$tipoDocumento = $con->query("SELECT * FROM tiposidentificacion WHERE IDTipoIdentificacion='$pacienteRow[pc_idIdentificacion]'")->fetch_assoc();
$ciudadPaciente = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$pacienteRow[pc_idCiudad]'")->fetch_assoc();

$telefonoPaciente = '';
if($pacienteRow['pc_telefonoCelular']!=""){
    $telefonoPaciente = $pacienteRow['pc_telefonoCelular'];
} else {
    $telefonoPaciente = $pacienteRow['pc_telefonoFijo'];
}

$deuda = 0; $sumatoria = 0;
$deudaSql = $con->query("SELECT SUM(ct_costo) AS vt FROM citas WHERE ct_idPaciente = '$pacienteID' AND ct_inicial='1' AND ct_estado IN(0,1)")->fetch_assoc();
$abonosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idPaciente = '$pacienteID'  AND ab_idSucursal>0 AND ab_estado='1'")->fetch_assoc();
$deuda = $deudaSql['vt'] - $abonosSql['ab'];
$sumatoria = $abonosSql['ab'];


if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img max-width="45mm" max-height="25mm" src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'">';
}
?>
<style type="text/css">
	table.page_header { border: none; background-color: white; border-bottom: solid 1mm <?= $colorPrincipal ?>; padding: 2mm 5mm; }
    table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1mm <?= $colorPrincipal ?>; padding: 2mm; }

    .empresa{ font-size: 14px; text-transform: uppercase; font-weight: bold; }
    .logo { vertical-align: middle; text-align: right; width: 70mm; }
    .logo img { max-width: 45mm; max-height: 20mm; }
    .info { font-size: 10px; }
    .fecha{ font-size: 9px; width: 70mm; }
    .tituloDoc{ font-size: 12px; font-weight: bold; text-align: center; width: 64mm; }

.titulo {
  color: rgb(30,30,30);
}
.tituloSecundario {
  font-size: 13px;
  font-weight: bold;
  display: inline-flex;
}
.top { margin: 1mm 0mm; }
.tituloSeccion{
    color:black;
    font-size: 11px;
    font-weight: bold;
    padding-bottom: 1mm;
    margin-bottom: 1mm;
}
.linea {
    border-bottom: 1px solid rgb(240,240,240);
}
.paddingTop{
    padding-top: 1mm;
}
.tableList {
  width: 196mm;
  border-collapse: collapse;
  color: rgb(50,50,50);
  margin-bottom: 20px;
}
.tableList thead {
  text-align: center;
  font-size: 11px;
}
.tableList thead th {padding: 10px 5px;}
.tableList tbody {font-size: 10px;}
.tableList thead tr { background: rgb(230,230,230); }
.tableList tbody tr:nth-child(even){background: rgb(245,245,245); }
.tableList tbody tr:nth-child(odd){background: rgb(254,254,254); }
.tableList tbody td { padding: 0.5mm 1mm; }
.tableList tbody td.fijo{ width: 30mm; }
.alg-right { text-align: right; }
.separacion{
    background: rgb(240,240,240);
    width: 100%;
    height: 1px;
}
.newTable tbody th.th { font-size: 10px; width: 20mm; }
.newTable tbody td.td { font-size: 9px; width: 182mm; }
.newTable { text-align: left; }
</style>
<page backtop="28mm" backbottom="14mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <table class="page_header" cellspacing="0">
            <tr>                
                <td colspan="2" class="empresa"><?= $clinicaRow['cl_nombre'] ?></td>
                <td rowspan="3" class="logo"><?= $imageLogo ?></td>
            </tr>
            <tr>
                <td colspan="2" class="info">
                    NIT: <?= $clinicaRow['cl_nit'] ?>
                    <br>
                    <?= $ciudad['cd_nombre'].' '.$clinicaRow['cl_direccion'].'' ?>
                    <br>
                    <?= $clinicaRow['cl_correo'] ?>
                </td>
            </tr>
            <tr>
                <td class="fecha"><?= $hoyAno.'/'.$hoyMes.'/'.$hoyDia ?></td>
                <td class="tituloDoc">Recibos de Caja</td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer" cellspacing="0">
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>

    <div class="tituloSeccion linea">Datos del Paciente</div>
    <table class="tableList newTable">
        <tbody>
            <tr>
                <th class="th">Nombres:</th>
                <td class="td"><?= $pacienteRow['pc_nombres'] ?></td>
            </tr>
            <tr>
                <th class="th">Documento:</th>
                <td class="td"><?= $tipoDocumento['ti_nombre'].' '.$pacienteRow['pc_identificacion'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadPaciente['cd_nombre'].' '.$pacienteRow['pc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $telefonoPaciente ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Abonos Realizados</div>
        <table class="tableList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Sucursal</th>
                    <th>Forma Pago</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="width: 202mm;"></td>
                </tr>
                <?php $abonosPaciente = $con->query("SELECT * FROM abonos, usuarios, sucursales WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idSucursal = sucursales.IDSucursal AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idPaciente='$pacienteID' ORDER BY abonos.IDAbono DESC");
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

                        $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosPacienteRow[ab_idFormaPago]'")->fetch_assoc();

                        if($abonosPacienteRow['ab_idFormaPago']==2){
                            $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosPacienteRow[ab_idBanco]'")->fetch_assoc();
                            $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosPacienteRow['ab_cheque'];
                        } else {
                            $abonoFormaPago = $formaPago['fp_nombre'];
                        }
                ?>
                <tr>
                    <td align="center"><?php echo $abonosPacienteRow['ab_consecutivo'] ?></td>
                    <td align="center"><?php echo $abonosPacienteRow['ab_fechaCreacion']; ?></td>
                    <td class="fijo"><?php echo $nombreUsuarioAbono; ?></td>
                    <td class="fijo"><?php echo $abonosPacienteRow['sc_nombre'] ?></td>
                    <td align="center"><?php echo $abonoFormaPago ?></td>
                    <td align="right"><?php echo $estadoAbono.'$'.number_format($abonosPacienteRow['ab_abono'], 0, ".", ","); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="6" class="linea"></td>
                </tr>
                <tr>
                    <th class="paddingTop" align="right" colspan="5">Total:</th>
                    <th class="paddingTop" align="right"><?php echo '$'.number_format($sumatoria, 0, ".", ","); ?></th>
                </tr>
                <tr>
                    <th align="right" colspan="5">Estado de cuenta:</th>
                    <th align="right"><?php echo '$'.number_format($deuda, 0, ".", ","); ?></th>
                </tr>
            </tbody>
        </table>
</page>