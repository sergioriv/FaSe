<?php include'config.php';

$sucursal = $con->query("SELECT * FROM sucursales WHERE IDSucursal = '$sucursalID'")->fetch_assoc();

$ciudadSC = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$sucursal[sc_idCiudad]'")->fetch_assoc();

$ingresos = 0;
$ingresosSql = $con->query("SELECT SUM(ab_abono) AS ab FROM abonos WHERE ab_idSucursal = '$sucursalID' AND ab_estado='1'")
    ->fetch_assoc();
$ingresos = $ingresosSql['ab'];

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

    <div class="tituloSeccion linea">Datos de la Sucursal</div>
    <table class="tableList newTable">
        <tbody>
            <tr>
                <th class="th">Nombres:</th>
                <td class="td"><?= $sucursal['sc_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadSC['cd_nombre'].' '.$sucursal['sc_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $sucursal['sc_telefonoFijo'] ?></td>
            </tr>
            <tr>
                <th class="th">Correo<br>Electrónico:</th>
                <td class="td"><?= $sucursal['sc_correo'] ?></td>
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
                    <th>Paciente</th>
                    <th>Forma Pago</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="width: 202mm;"></td>
                </tr>
                <?php $abonosSucursal = $con->query("SELECT * FROM abonos, usuarios, pacientes WHERE abonos.ab_idUsuario = usuarios.IDUsuario AND abonos.ab_idPaciente = pacientes.IDPaciente AND abonos.ab_idClinica='$sessionClinica' AND abonos.ab_idSucursal='$sucursalID' ORDER BY abonos.IDAbono ASC");
                    while($abonosSucursalRow = $abonosSucursal->fetch_assoc()){
                        $nombreUsuarioAbono = '';
                        $IDusuarioAbono = $abonosSucursalRow['us_id'];
                        if($abonosSucursalRow['us_idRol']==1){
                            $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbono'")->fetch_assoc();
                            $nombreUsuarioAbono = $usuarioAbono['cl_nombre'];
                        } elseif($abonosSucursalRow['us_idRol']==2){
                            $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbono'")->fetch_assoc();
                            $nombreUsuarioAbono = $usuarioAbono['sc_nombre'];
                        } elseif($abonosSucursalRow['us_idRol']==3){
                            $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbono'")->fetch_assoc();
                            $nombreUsuarioAbono = $usuarioAbono['dc_nombres'];
                        }

                        $estadoAbono = '';
                        if($abonosSucursalRow['ab_estado']==0) { $estadoAbono = '(anulado) '; }

                        $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonosSucursalRow[ab_idFormaPago]'")->fetch_assoc();

                        if($abonosSucursalRow['ab_idFormaPago']==2){
                            $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonosSucursalRow[ab_idBanco]'")->fetch_assoc();
                            $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonosSucursalRow['ab_cheque'];
                        } else {
                            $abonoFormaPago = $formaPago['fp_nombre'];
                        }
                ?>
                <tr>
                    <td align="center"><?php echo $abonosSucursalRow['ab_consecutivo'] ?></td>
                    <td align="center"><?php echo $abonosSucursalRow['ab_fechaCreacion']; ?></td>
                    <td class="fijo"><?php echo $nombreUsuarioAbono; ?></td>
                    <td class="fijo"><?php echo $abonosSucursalRow['pc_nombres'] ?></td>
                    <td align="center"><?php echo $abonoFormaPago ?></td>
                    <td align="right"><?php echo $estadoAbono.'$'.number_format($abonosSucursalRow['ab_abono'], 0, ".", ","); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="6" class="linea"></td>
                </tr>
                <tr>
                    <th align="right" colspan="5" class="paddingTop"><b>Total:</b></th>
                    <th align="right" class="paddingTop"><b><?php echo '$'.number_format($ingresos, 0, ".", ","); ?></b></th>
                </tr>
            </tbody>
        </table>

</page>