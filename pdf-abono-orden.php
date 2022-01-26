<?php //include'config.php';

$ciudad = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$clinicaRow[cl_idCiudad]'")->fetch_assoc();

$abonoPR = $con->query("SELECT * FROM ordenesabonos as pra 
            INNER JOIN ordenesentrada AS ore ON pra.pra_idOrden = ore.IDOrdenEntrada 
            INNER JOIN proveedores AS pr ON ore.ore_idProveedor = pr.IDProveedor
            WHERE pra.IDOrdenAbono = $abonoID")->fetch_assoc();

$ciudadProveedor = $con->query("SELECT * FROM ciudades WHERE IDCiudad = '$abonoPR[pr_idCiudad]'")->fetch_assoc();


    $usuario = $con->query("SELECT * FROM usuarios WHERE IDUsuario = '$abonoPR[pra_idUsuario]'")->fetch_assoc();
    $nombreUsuarioAbonoPR = '';
    $IDusuarioAbonoPR = $usuario['us_id'];
    if($usuario['us_idRol']==1){
        $usuarioAbono = $con->query("SELECT * FROM clinicas WHERE IDClinica='$IDusuarioAbonoPR'")->fetch_assoc();
        $nombreUsuarioAbonoPR = $usuarioAbono['cl_nombre'];
    } elseif($usuario['us_idRol']==2){
        $usuarioAbono = $con->query("SELECT * FROM sucursales WHERE IDSucursal='$IDusuarioAbonoPR'")->fetch_assoc();
        $nombreUsuarioAbonoPR = $usuarioAbono['sc_nombre'];
    } elseif($usuario['us_idRol']==3){
        $usuarioAbono = $con->query("SELECT * FROM doctores WHERE IDDoctor='$IDusuarioAbonoPR'")->fetch_assoc();
        $nombreUsuarioAbonoPR = $usuarioAbono['dc_nombres'];
    }

    $estadoAbono = '';
    $watermark = '';
    if($abonoPR['pra_estado']==0) { $estadoAbono = '(anulado) '; $watermark = 'backimg="img/anulado.png"'; }

    $formaPago = $con->query("SELECT * FROM fomaspago WHERE IDFormaPago = '$abonoPR[pra_idFormaPago]'")->fetch_assoc();

    if($abonoPR['pra_idFormaPago']==2){
        $bancoAbono = $con->query("SELECT * FROM bancos WHERE IDBanco = '$abonoPR[pra_idBanco]'")->fetch_assoc();
        $abonoFormaPago = $formaPago['fp_nombre'].'<br>'.$bancoAbono['bnc_codigo'].' | '.$abonoPR['pra_cheque'];
    } else {
        $abonoFormaPago = $formaPago['fp_nombre'];
    }


if( file_exists($clinicaRow['cl_logo']) ) {
    $imageLogo = '<img max-width="45mm" max-height="25mm" src="'.dirname(__FILE__)."/".$clinicaRow['cl_logo'] .'">';
}
?>

<style type="text/css">
	table.page_header { border: none; background: white; border-bottom: solid 1mm <?= $colorPrincipal ?>; padding: 2mm 5mm; }
    table.page_footer { width: 100%; border: none; background: white; border-top: solid 1mm <?= $colorPrincipal ?>; padding: 2mm; }

    .empresa{ font-size: 14px; text-transform: uppercase; font-weight: bold; }
    .logo { vertical-align: middle; text-align: right; width: 70mm; }
    .logo img { max-width: 45mm; max-height: 20mm; }
    .info { font-size: 10px; }
    .fecha{ font-size: 9px; width: 63mm; }
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
.tableList tbody td { padding: 0.5mm 1mm; vertical-align: super; }
.tableList tbody td.fijo{ width: 15mm; }
.alg-right { text-align: right; }
.separacion{
    background: rgb(240,240,240);
    width: 100%;
    height: 1px;
}
.newTable tbody th.th { font-size: 10px; width: 20mm; }
.newTable tbody td.td { font-size: 9px; width: 182mm; }
.newTable { text-align: left; }

.content_signature{
  position: relative;
  width: max-content;
  width: 200px;
  height: 100px;
  left: 23mm;
}
.content_signature .option_signature_pad{
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 2px 4px;
  box-sizing: border-box;
  font-size: 10px;
  border-top: 1px solid black;
}
</style>
<page backtop="28mm" backbottom="14mm" backleft="5mm" backright="5mm" pagegroup="new" <?= $watermark ?>>
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
                <td class="tituloDoc">Comprobante de Egreso #<?php echo $abonoPR['pra_consecutivo'] ?></td>
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
    
    <div class="tituloSeccion linea">Datos del Proveedor</div>
    <table class="tableList newTable">
        <tbody>
            <tr>
                <th class="th">Nombres:</th>
                <td class="td"><?= $abonoPR['pr_nombre'] ?></td>
            </tr>
            <tr>
                <th class="th">NIT:</th>
                <td class="td"><?= $abonoPR['pr_nit'] ?></td>
            </tr>
            <tr>
                <th class="th">Direccion:</th>
                <td class="td"><?= $ciudadProveedor['cd_nombre'].' '.$abonoPR['pr_direccion'] ?></td>
            </tr>
            <tr>
                <th class="th">Teléfono:</th>
                <td class="td"><?= $abonoPR['pr_telefonoFijo'] ?></td>
            </tr>
        </tbody>
    </table>

    <div class="tituloSeccion linea">Abono Realizado a # Factura: <?= $abonoPR['ore_numeroFactura'] ?></div>
        <table class="tableList">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Comentario</th>
                    <th>Forma Pago</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" style="width: 196mm;"></td>
                </tr>
                <tr>
                    <td align="center"><?php echo $abonoPR['pra_consecutivo'] ?></td>
                    <td align="center"><?php echo $abonoPR['pra_fechaCreacion']; ?></td>
                    <td class="fijo"><?php echo $nombreUsuarioAbonoPR; ?></td>
                    <td class="fijo"><?php echo $abonoPR['pra_comentario'] ?></td>
                    <td align="center"><?php echo $abonoFormaPago ?></td>
                    <td align="right"><?php echo $estadoAbono.'$'.number_format($abonoPR['pra_abono'], 0, ".", ","); ?></td>
                </tr>
            </tbody>
        </table>
</page>